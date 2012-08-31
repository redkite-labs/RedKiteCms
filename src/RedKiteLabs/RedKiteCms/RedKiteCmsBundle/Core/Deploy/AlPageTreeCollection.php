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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Deploy;

use Symfony\Component\Filesystem\Filesystem;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\ThemeEngineBundle\Model\AlTheme;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlPageQuery;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlBlockQuery;
use AlphaLemon\ThemeEngineBundle\Core\Repository\AlThemeQuery;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\Finder\Finder;

use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper;

/**
 * The base object that implements the methods to deploy the website from development (CMS) to production (the deploy bundle)
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageTreeCollection implements \Iterator, \Countable
{
    private $container = null;
    private $pages = array();
    private $factoryRepository = null;
    private $themeRepository = null;
    private $languageRepository = null;
    private $pageRepository = null;
    private $themesCollectionWrapper = null;
    private $seoRepository = null;

    public function  __construct(ContainerInterface $container,
            AlFactoryRepositoryInterface $factoryRepository,
            AlThemesCollectionWrapper $themesCollectionWrapper = null)
    {
        $this->container = $container;
        $this->themesCollectionWrapper = (null === $themesCollectionWrapper) ? $container->get('alphalemon_cms.themes_collection_wrapper') : $themesCollectionWrapper;
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
        $this->pageRepository = $this->factoryRepository->createRepository('Page');
        $this->seoRepository = $this->factoryRepository->createRepository('Seo');
        $this->themeRepository = $this->factoryRepository->createRepository('Theme');

        $this->setUp();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->pages);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->pages);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->pages);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return reset($this->pages);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return current($this->pages) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->pages);
    }

    /**
     * Returns the AlPageTree object stored at the requird key
     *
     * @param string $key
     * @return null|\AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree
     */
    public function at($key)
    {
        if(!array_key_exists($key, $this->pages)) {
            return null;
        }

        return $this->pages[$key];
    }

    /**
     * Fills up the PageTree collection traversing the saved languages and pages
     */
    protected function setUp()
    {
        $languages = $this->languageRepository->activeLanguages();
        $pages = $this->pageRepository->activePages();

        // Cycles all the website's languages
        foreach($languages as $language)
        {
            // Cycles all the website's pages
            foreach($pages as $page)
            {
                // Clones the current TemplateManager object and adds it to a new instance of
                // AlThemesCollectionWrapper, which will be passed to the new PageTree object
                $templateManager = clone($this->themesCollectionWrapper->getTemplateManager());
                $themesCollectionWrapper = new AlThemesCollectionWrapper($this->themesCollectionWrapper->getThemesCollection(), $templateManager);

                $pageTree = new AlPageTree($this->container,
                        $this->factoryRepository,
                        $themesCollectionWrapper);
                $pageTree->setExtraAssetsSuffixes()
                         ->refresh($language->getId(), $page->getId());

                $this->pages[] = $pageTree;
            }
        }
    }
}
