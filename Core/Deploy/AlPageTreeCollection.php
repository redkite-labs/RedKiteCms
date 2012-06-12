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
class AlPageTreeCollection implements Iterator
{
    private $pages = array();
    private $themeModel;
    private $languageModel;
    private $pageModel;
    private $templateManager;
    private $seoModel;

    public function  __construct(AlTemplateManager $templateManager, 
                                Propel\AlThemeModelPropel $themeModel, 
                                Propel\AlLanguageModelPropel $languageModel, 
                                Propel\AlPageModelPropel $pageModel,
                                Propel\AlSeoModelPropel $seoModel)
    {
        $this->templateManager = $templateManager;
        $this->themeModel = $themeModel;
        $this->languageModel = $languageModel;
        $this->pageModel = $pageModel;
        $this->seoModel = $seoModel;
        
        $this->setUp();
    }

    public function current()
    {
        return current($this->pages);
    }

    public function key()
    {
        return key($this->pages);
    }

    public function next()
    {
        return next($this->pages);
    }

    public function rewind()
    {
        return reset($this->pages);
    }

    public function valid()
    {
        return valid(current($this->pages) !== false);
    }

    /**
     * Sets up the PageTree objects from the saved languages and pages
     */
    protected function setUp()
    {
        $languages = $this->languageModel->activeLanguages();
        $pages = $this->pageModel->activePages();
        //$idMainLanguage = $this->languageModel->mainLanguage();

        // Cycles all the website's languages
        foreach($languages as $language)
        {
            // Cycles all the website's pages
            foreach($pages as $page)
            {
                $pageTree = new AlPageTree($this->container, $this->templateManager, $this->templateManager, $this->themeModel, $this->languageModel, $this->pageModel, $this->seoModel);
                $pageTree->refresh($language->getId(), $page->getId());

                /*
                if ($language->getId() == $idMainLanguage) {
                    $this->savePage($page, $language, clone($pageTree));
                }*/

                $this->pages[] = $pageTree;
            }
        }
    }
}
