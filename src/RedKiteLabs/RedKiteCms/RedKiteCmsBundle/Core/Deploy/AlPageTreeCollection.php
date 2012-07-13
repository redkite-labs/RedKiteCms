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

/**
 * The base object that implements the methods to deploy the website from development (CMS) to production (the deploy bundle)
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageTreeCollection implements \Iterator, \Countable
{
    private $container;
    private $pages = array();
    private $themeRepository;
    private $languageRepository;
    private $pageRepository;
    private $themesCollectionWrapper;
    private $seoRepository;

    public function  __construct(ContainerInterface $container,                                
                                AlThemesCollectionWrapper $themesCollectionWrapper = null,
                                //AlTemplateManager $templateManager = null,
                                Propel\AlLanguageRepositoryPropel $languageRepository = null,
                                Propel\AlPageRepositoryPropel $pageRepository = null,
                                Propel\AlThemeRepositoryPropel $themeRepository = null,
                                Propel\AlSeoRepositoryPropel $seoRepository = null)
    {
        $this->container = $container;
        //$this->templateManager = (null === $templateManager) ? $container->get('template_manager') : $templateManager;
        $this->themesCollectionWrapper = (null === $themesCollectionWrapper) ? $container->get('alphalemon_cms.themes_collection_wrapper') : $themesCollectionWrapper;
        $this->languageRepository = (null === $languageRepository) ? $container->get('language_model') : $languageRepository;
        $this->pageRepository = (null === $pageRepository) ? $container->get('page_model') : $pageRepository;
        $this->themeRepository = (null === $themeRepository) ? $container->get('theme_model') : $themeRepository;
        $this->seoRepository = (null === $seoRepository) ? $container->get('seo_model') : $seoRepository;

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
        $theme = $this->themeRepository->activeBackend();
        $themeName = $theme->getThemeName();

        // Cycles all the website's languages
        foreach($languages as $language)
        { 
            // Cycles all the website's pages
            foreach($pages as $page)
            {
                /*
                $templateManager = clone($this->templateManager);
                $templateManager->getTemplate()
                        ->setThemeName($themeName)
                        ->setTemplateName($page->getTemplateName());*/

                /*
                $theme = $this->themes->getTheme($themeName);
                $template = $theme->getTemplate($page->getTemplateName());
                $templateManager = new AlTemplateManager($this->container->get('event_dispatcher'), $template);*/
                
                //$templateManager = $this->themesCollectionWrapper->assignTemplate($themeName, $page->getTemplateName());

                $pageTree = new AlPageTree($this->container,
                        $this->themesCollectionWrapper,
                        $this->languageRepository,
                        $this->pageRepository,
                        $this->themeRepository,
                        $this->seoRepository);
                $pageTree->refresh($language->getId(), $page->getId());

                $this->pages[] = $pageTree;
            }
        }
    }
}
