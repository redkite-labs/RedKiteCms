<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager;

use Symfony\Component\HttpKernel\KernelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterException;

/**
 * Manages an url to be used when in CMS mode and for production mode
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlUrlManager implements AlUrlManagerInterface
{
    protected $kernel = null;
    protected $factoryRepository = null;
    protected $seoRepository = null;
    protected $permalink = null;
    protected $internalUrl = null;
    protected $productionRoute = null;
    protected $error = null;

    /**
     * Constructor
     *
     * @param KernelInterface              $kernel
     * @param AlFactoryRepositoryInterface $factoryRepository
     */
    public function __construct(KernelInterface $kernel, AlFactoryRepositoryInterface $factoryRepository)
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
            $language = $this->fetchAlLanguage($language);
            $page = $this->fetchAlPage($page);

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
                $this->productionRoute = $this->generateRoute($seo->getAlLanguage(), $seo->getAlPage());
            }
        }

        return $this;
    }

    private function generateRoute(AlLanguage $language, AlPage $page)
    {
        return sprintf('_%s_%s', $language->getLanguage(), str_replace("-", "_", $page->getPageName()));
    }

    private function generateDefaultUrlTokens()
    {
        $frontController = $this->kernel->getEnvironment() . '.php';

        return sprintf('/%s/backend/', $frontController);
    }

    private function fetchAlLanguage($language)
    {
        if ($language instanceof AlLanguage) {
            return $language;
        }

        $languageRepository = $this->factoryRepository->createRepository('Language');

        if (is_string($language)) {
            $language = $languageRepository->fromLanguageName($language);
            $this->checkNull($language, 'The requested language has not been found');

            return $language;
        }

        if (is_numeric($language)) {
            $language = $languageRepository->fromPK($language);
            $this->checkNull($language, 'The requested language has not been found');

            return $language;
        }

        throw new InvalidParameterException('The given parameter to fetch a language is not valid');
    }

    private function fetchAlPage($page)
    {
        if ($page instanceof AlPage) {
            return $page;
        }

        $pageRepository = $this->factoryRepository->createRepository('Page');

        if (is_string($page)) {
            $page = $pageRepository->fromPageName($page);
            $this->checkNull($page, 'The requested page has not been found');

            return $page;
        }

        if (is_numeric($page)) {
            $page = $pageRepository->fromPK($page);
            $this->checkNull($page, 'The requested page has not been found');

            return $page;
        }

        throw new InvalidParameterException('The given parameter to fetch a page is not valid');
    }

    private function checkNull($object, $message)
    {
        if (null === $object) {
            throw new InvalidParameterException($message);
        }
    }
}
