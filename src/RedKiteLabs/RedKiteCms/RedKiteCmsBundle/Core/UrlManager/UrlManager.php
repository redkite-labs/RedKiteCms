<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager;

use Symfony\Component\HttpKernel\KernelInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Language;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;

/**
 * This object is deputaed to format an url to be used when the CMS editor is active or for
 * the production environment
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class UrlManager implements UrlManagerInterface
{
    /** @var null|KernelInterface */
    protected $kernel = null;
    /** @var null|FactoryRepositoryInterface */
    protected $factoryRepository = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface */
    protected $seoRepository = null;
    protected $permalink = null;
    protected $internalUrl = null;
    protected $productionRoute = null;
    protected $error = null;

    /**
     * Constructor
     *
     * @param KernelInterface              $kernel
     * @param FactoryRepositoryInterface $factoryRepository
     *
     * @api
     */
    public function __construct(KernelInterface $kernel, FactoryRepositoryInterface $factoryRepository)
    {
        $this->kernel = $kernel;
        $this->factoryRepository = $factoryRepository;
        $this->seoRepository = $this->factoryRepository->createRepository('Seo');
    }

    /**
     * {@inheritdoc}
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * {@inheritdoc}
     */
    public function getInternalUrl()
    {
        return $this->internalUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductionRoute()
    {
        return $this->productionRoute;
    }

    /**
     * {@inheritdoc}
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * {@inheritdoc}
     */
    public function buildInternalUrl($language, $page)
    {
        try {
            $language = $this->fetchLanguage($language);
            $page = $this->fetchPage($page);

            $seo = $this->seoRepository->fromPageAndLanguage($language->getId(), $page->getId());
            if (null !== $seo) {
                $this->permalink = $seo->getPermalink();
                $this->internalUrl = $this->generateDefaultUrlTokens() . $this->permalink;
                $this->productionRoute = $this->generateRoute($language, $page);
            }
        } catch (\Exception $ex) {
            $this->error = $ex->getMessage();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function fromUrl($url)
    {
        if (!is_string($url)) {
            $this->error = "The url parameter must be a string";

            return $this;
        }

        $this->permalink = null;
        $this->internalUrl = null;
        $this->productionRoute = null;

        $defaultUrlTokens = $this->generateDefaultUrlTokens();
        if (strpos($url, ':') === false && $url != '#') {

            // It's just the permalink
            if (strpos($url, $defaultUrlTokens) === false) {
                $permalink = $url;
                $internalUrl = $defaultUrlTokens . $permalink;
            }
            // The url have been already normalized
            else {
                $permalink = str_replace($defaultUrlTokens, '', $url);
                $internalUrl = $url;
            }

            $seo = $this->seoRepository->fromPermalink($permalink);
            if (null !== $seo) {
                $this->permalink = $permalink;
                $this->internalUrl = $internalUrl;
                $this->productionRoute = $this->generateRoute($seo->getLanguage(), $seo->getPage());
            }
        }

        return $this;
    }

    /**
     * Generates an internal route name, from the language and the page
     *
     * @param  Language $language
     * @param  Page     $page
     * @return string
     */
    protected function generateRoute(Language $language, Page $page)
    {
        return sprintf('_%s_%s', $language->getLanguageName(), str_replace("-", "_", $page->getPageName()));
    }

    private function generateDefaultUrlTokens()
    {
        $frontController = $this->kernel->getEnvironment() . '.php';

        return sprintf('/%s/backend/', $frontController);
    }

    /**
     * @param  string|int|Language    $language
     * @return Language
     * @throws InvalidArgumentException
     */
    private function fetchLanguage($language)
    {
        if ($language instanceof Language) {
            return $language;
        }

        $languageRepository = $this->factoryRepository->createRepository('Language');

        if (is_string($language)) {
            $language = $languageRepository->fromLanguageName($language);
            $this->checkNull($language, 'exception_language_not_found');

            return $language;
        }

        if (is_numeric($language)) {
            $language = $languageRepository->fromPK($language);
            $this->checkNull($language, 'exception_language_not_found');

            return $language;
        }

        throw new InvalidArgumentException('exception_language_not_retrieved');
    }

    /**
     * @param  string|int|Page        $page
     * @return Page
     * @throws InvalidArgumentException
     */
    private function fetchPage($page)
    {
        if ($page instanceof Page) {
            return $page;
        }

        $pageRepository = $this->factoryRepository->createRepository('Page');

        if (is_string($page)) {
            $page = $pageRepository->fromPageName($page);
            $this->checkNull($page, 'exception_page_not_found');

            return $page;
        }

        if (is_numeric($page)) {
            $page = $pageRepository->fromPK($page);
            $this->checkNull($page, 'exception_page_not_found');

            return $page;
        }

        throw new InvalidArgumentException('exception_page_not_retrieved');
    }

    private function checkNull($object, $message)
    {
        if (null === $object) {
            throw new InvalidArgumentException($message);
        }
    }
}
