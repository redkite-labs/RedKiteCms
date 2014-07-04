<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\DataManager;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Language;
use Symfony\Component\HttpFoundation\Request;

/**
 * DataManager is the objected deputed to handle the information related to a website
 * page, retrieved from a database
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class DataManager
{
    /** @var null|FactoryRepositoryInterface */
    private $factoryRepository = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface */
    private $seoRepository = null;
    /** @var null|Language */
    private $language = null;
    /** @var null|Page */
    private $page = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Seo */
    private $seo = null;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface $factoryRepository
     */
    public function __construct(FactoryRepositoryInterface $factoryRepository)
    {
        $this->factoryRepository = $factoryRepository;
    }

    /**
     * Returns the current Page object
     *
     * @return Page instance
     *
     * @api
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Returns the current Language object
     *
     * @return Language instance
     *
     * @api
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Returns the current Seo object
     *
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Seo instance
     *
     * @api
     */
    public function getSeo()
    {
        return $this->seo;
    }

    /**
     * Initializes the DataManager object from a request
     *
     * @param Request $request
     */
    public function fromRequest(Request $request)
    {
        $pageName = $request->get('page');
        $language = $request->get('_locale');
        $permalink = $request->get('permalink');
        $options = array(
            "pageName" => $pageName,
            "languageName" => $language,
            "permalink" => $permalink,
            "pageId" => (int) $request->get('pageId'),
            "languageId" => (int) $request->get('languageId'),
        );

        $this->fromOptions($options);
    }

    /**
     * Initializes the DataManager object from the database entities
     *
     * @param Language $language
     * @param Page     $page
     */
    public function fromEntities(Language $language = null, Page $page = null)
    {
        $this->language = $language;
        $this->page = $page;
        if (null !== $this->language && null !== $this->page) {
            $options = array(
                "languageId" => $this->language->getId(),
                "pageId" => $this->page->getId(),
            );
            $this->seo = $this->setupSeo($options);
        }
    }

    /**
     * Initializes the DataManager object from and array of options
     *
     * @param array $options
     */
    public function fromOptions(array $options)
    {
        $this->seo = $this->setupSeo($options);
        if (null !== $this->seo) {
            $this->language = $this->seo->getLanguage();
            $this->page = $this->seo->getPage();
        }
    }

    private function setupSeo(array $options)
    {
        $seo = null;
        $seoRepository = $this->seoRepository();
        if ($options["languageId"] != 0 && $options["pageId"] != 0) {
            $seo = $seoRepository->fromPageAndLanguage($options["languageId"], $options["pageId"]);

            if (null !== $seo) {
                return $seo;
            }
        }

        $seo = $seoRepository->fromLanguageAndPageNames($options["languageName"], $options["pageName"]);
        if (null !== $seo) {
            return $seo;
        }

        $seo = $seoRepository->fromPermalink($options["permalink"]);
        if (null !== $seo) {
            return $seo;
        }

        $seo = $seoRepository->fromPermalink($options["languageName"]);
        if (null !== $seo) {
            return $seo;
        }

        return $seoRepository->fromPermalink($options["pageName"]);
    }

    private function seoRepository()
    {
        if (null === $this->seoRepository) {
            $this->seoRepository = $this->factoryRepository->createRepository('Seo');
        }

        return $this->seoRepository;
    }
}
