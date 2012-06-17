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
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;

use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\Finder\Finder;

use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel;
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
    private $themeModel;
    private $languageModel;
    private $pageModel;
    private $templateManager;
    private $seoModel;

    public function  __construct(ContainerInterface $container,
                                AlTemplateManager $templateManager = null,
                                Propel\AlLanguageModelPropel $languageModel = null,
                                Propel\AlPageModelPropel $pageModel = null,
                                Propel\AlThemeModelPropel $themeModel = null,
                                Propel\AlSeoModelPropel $seoModel = null)
    {
        $this->container = $container;
        $this->templateManager = (null === $templateManager) ? $container->get('template_manager') : $templateManager;
        $this->languageModel = (null === $languageModel) ? $container->get('language_model') : $languageModel;
        $this->pageModel = (null === $pageModel) ? $container->get('page_model') : $pageModel;
        $this->themeModel = (null === $themeModel) ? $container->get('theme_model') : $themeModel;
        $this->seoModel = (null === $seoModel) ? $container->get('seo_model') : $seoModel;

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
        $languages = $this->languageModel->activeLanguages();
        $pages = $this->pageModel->activePages();
        $theme = $this->themeModel->activeBackend();
        $themeName = $theme->getThemeName();

        // Cycles all the website's languages
        foreach($languages as $language)
        {
            // Cycles all the website's pages
            foreach($pages as $page)
            {
                $templateManager = clone($this->templateManager);
                $templateManager->getTemplate()
                        ->setThemeName($themeName)
                        ->setTemplateName($page->getTemplateName());

                $pageTree = new AlPageTree($this->container,
                        $templateManager,
                        $this->languageModel,
                        $this->pageModel,
                        $this->themeModel,
                        $this->seoModel);
                $pageTree->refresh($language->getId(), $page->getId());

                $this->pages[] = $pageTree;
            }
        }
    }
}
