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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\PageTree;

use AlphaLemon\PageTreeBundle\Core\PageTree\AlPageTree as BaseAlPageTree;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\OrmInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;

use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\AlRepeatedSlotsManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainer;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel;

/**
 * Extends the bas AlPageTree object to fetch page information from the database
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageTree extends BaseAlPageTree
{
    protected $alPage = null;
    protected $alLanguage = null;
    protected $alTheme = null;
    protected $languageModel;
    protected $pageModel;
    protected $themeModel;
    protected $seoModel;
    protected $templateManager;
    protected $locatedAssets = array('css' => array(), 'js' => array());
    protected $isValidLanguage = false;
    protected $isValidPage = false;
    protected $isValid;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param AlTemplateManager $templateManager
     * @param Orm\LanguageModelInterface $languageModel
     * @param Orm\PageModelInterface $pageModel
     * @param Orm\ThemeModelInterface $themeModel
     * @param Orm\SeoModelInterface $seoModel
     */
    public function __construct(ContainerInterface $container,
                                AlTemplateManager $templateManager,
                                Orm\LanguageModelInterface $languageModel = null,
                                Orm\PageModelInterface $pageModel = null,
                                Orm\ThemeModelInterface $themeModel = null,
                                Orm\SeoModelInterface $seoModel = null)
    {
        parent::__construct($container);

        $this->templateManager = $templateManager;
        $this->languageModel = (null === $languageModel) ? new Propel\AlLanguageModelPropel() : $languageModel;
        $this->pageModel = (null === $pageModel) ? new Propel\AlPageModelPropel() : $pageModel;
        $this->themeModel = (null === $themeModel) ? new Propel\AlThemeModelPropel() : $themeModel;
        $this->seoModel = (null === $seoModel) ? new Propel\AlSeoModelPropel() : $seoModel;
    }

    /**
     * Returns the current AlPage object
     *
     * @return AlPage
     */
    public function getAlPage()
    {
        return $this->alPage;
    }

    /**
     * Returns the current AlLanguage object
     *
     * @return AlLanguage
     */
    public function getAlLanguage()
    {
        return $this->alLanguage;
    }

    /**
     * Returns the current AlTheme object
     *
     * @return AlTheme
     */
    public function getAlTheme()
    {
        return $this->alTheme;
    }

    /**
     * Returns the current AlTemplateManager object
     *
     * @return AlTemplateManager
     */
    public function getTemplateManager()
    {
        return $this->templateManager;
    }

    /**
     * AlphaLemon is in CMS mode
     *
     * @return boolean
     */
    public function isCmsMode()
    {
        return true;
    }

    /**
     * Returns true when both page and language have been setted
     *
     * @return boolean
     */
    public function isValid()
    {
        return ($this->isValidPage && $this->isValidLanguage) ? true : false;
    }

    /**
     * Sets up the page tree object from the language and page objects passed as parameters. When one or both those parameters misses,
     * the PageTree is setted up from the current request and session
     *
     * @param   AlLanguage  $alLanguage The AlLanguage object to use or none
     * @param   AlPage      $alPage     The AlPage object to use or none
     * @return  null        Returns null when something goes wrong
     */

    /**
     * Sets up the page tree object from current request or session (symfony 2.0.x)
     *
     * @return null
     * @throws Exception
     */
    public function setup()
    {
        try
        {
            $this->alLanguage = $this->setupLanguageFromSession();
            $this->alPage = $this->setupPageFromRequest();
            if (null === $this->alLanguage || null === $this->alPage) {
                return null;
            }

            $this->alTheme = $this->themeModel->activeBackend();
            if (null === $this->alTheme) {
                return null;
            }

            $this->setThemeName($this->alTheme->getThemeName());
            $this->setTemplateName($this->alPage->getTemplateName());
            $this->refresh($this->alLanguage->getId(), $this->alPage->getId());

            $this->setContents($this->templateManager->slotsToArray(), true);

            // TODO
            //$rs = new AlRepeatedSlotsManager($this->container, $alTheme->getThemeName());
            //$rs->compareSlots($this->templateName, $this->getSlots(), true);
        }
        catch(\Exception $ex)
        {
            throw $ex;
        }
    }

    /**
     * Refreshes the page tree object with the given language and page ids
     *
     * @param int $idLanguage
     * @param int $idPage
     */
    public function refresh($idLanguage, $idPage)
    {
        $this->pageContentsContainer = $this->templateManager
                    ->getPageContentsContainer()
                    ->setIdLanguage($idLanguage)
                    ->setIdPage($idPage)
                    ->refresh();

        $this->templateManager
                    ->setPageContentsContainer($this->pageContentsContainer)
                    ->setTemplateSlots($this->templateSlots)
                    ->refresh();
    }

    /**
     * Overrides the base method to add extra functionalities
     *
     * @see   AlphaLemon\PageTreeBundle\Core\PageTree\AlPageTree->addContent()
     */
    public function addContent($slotName, array $content, $key = null)
    {
        parent::addContent($slotName, $content, $key);

        if (array_key_exists("ExternalJavascript", $content))
        {
            $javascripts = (!is_array($content["ExternalJavascript"])) ? ($content["ExternalJavascript"] != "") ? \explode(',', $content["ExternalJavascript"]) : array(): $content["ExternalJavascript"];

            foreach($javascripts as $javascript)
            {
                $this->addJavascript($javascript);
            }
        }

        if (array_key_exists("InternalJavascript", $content) && $content["InternalJavascript"] != "")
        {
            $this->appendInternalJavascript($content["InternalJavascript"]);
        }

        if (array_key_exists("ExternalStylesheet", $content))
        {
            $stylesheets = (!is_array($content["ExternalStylesheet"])) ? \explode(',', $content["ExternalStylesheet"]) : $content["ExternalStylesheet"];
            foreach($stylesheets as $stylesheet)
            {
                $this->addStylesheet($stylesheet);
            }
        }

        if (array_key_exists("InternalStylesheet", $content) && $content["InternalStylesheet"] != "")
        {
            $this->appendInternalStylesheet($content["InternalStylesheet"]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addStylesheet($value)
    {
        $assetName = basename($value);
        if ($value != "" && !in_array($assetName, $this->locatedAssets['css']))
        {
            $this->locatedAssets['css'][] = $assetName;
            $this->externalStylesheets[] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addJavascript($value)
    {
        $assetName = basename($value);
        if ($value != "" && !in_array($assetName, $this->locatedAssets['js']))
        {
            $this->locatedAssets['js'][] = $assetName;
            $this->externalJavascripts[] = $value;
        }
    }

    /**
     * Sets up the AlLanguage object from the current request or session (symfony 2.0.x)
     *
     * @return null|AlLanguage
     */
    protected function setupLanguageFromSession()
    {
        $request = $this->container->get('request');
        $language = $request->get('language');
        if (null === $language) {
            $session = $this->container->get('session');
            $language = method_exists ($session, "getLocale") ? $session->getLocale() : $request->getLocale();
        }

        $alLanguage = ((int)$language > 0) ? $this->languageModel->fromPK($language) : $this->languageModel->fromLanguageName($language);
        $this->isValidLanguage = true;

        return $alLanguage;
    }

    /**
     * Sets up the AlLanguage object from the current request
     *
     * @return null
     */
    protected function setupPageFromRequest()
    {
        if (null === $this->alLanguage) {
            return null;
        }

        $pageName = $this->container->get('request')->get('page');
        if (!$pageName || $pageName == "" || $pageName == "backend") {
            return null;
        }

        $seo = $this->seoModel->fromPermalink($pageName, $this->alLanguage->getId());
        if (null === $seo) {
            $seo = $this->seoModel->fromPageAndLanguage($pageName, $this->alLanguage->getId());
        }

        if (null === $seo) {
            $alPage = $this->pageModel->fromPageName($pageName);
            if (null === $alPage) {
                $alPage = $this->pageModel->fromPK($pageName);
                if (!$alPage) {
                    return null;
                }
            }
        }
        else {
            $alPage = $seo->getAlPage();
            $this->metaTitle = $seo->getMetaTitle();
            $this->metaDescription = $seo->getMetaDescription();
            $this->metaKeywords = $seo->getMetaKeywords();
        }

        $this->isValidPage = true;

        return $alPage;
    }

    /**
     * {@inheritDoc}
     */
    protected function setupPageTree()
    {
        parent::setupPageTree();

        if ($this->themeName != '' && $this->templateName != '') {
            $templateName = strtolower($this->templateName);
            $theme = preg_replace('/bundle$/', '', strtolower($this->themeName));

            $param = sprintf('themes.%s_%s.javascripts_cms', $theme, $templateName);
            if ($this->container->hasParameter($param))
            {
                $this->addJavascripts($this->container->getParameter($param));
            }

            $param = sprintf('themes.%s_%s.stylesheets_cms', $theme, $templateName);
            if ($this->container->hasParameter($param))
            {
                $this->addStylesheets($this->container->getParameter($param));
            }

            $kernel = $this->container->get('kernel');
            foreach ($kernel->getBundles() as $bundle)
            {
                $bundleName = preg_replace('/bundle$/', '', strtolower($bundle->getName()));

                $param = sprintf('%s.javascripts_cms', $bundleName);
                if ($this->container->hasParameter($param)) $this->addJavascripts($this->container->getParameter($param));

                $param = sprintf('%s.stylesheets_cms', $bundleName);
                if ($this->container->hasParameter($param)) $this->addStylesheets($this->container->getParameter($param));

                $param = sprintf('%s.%s_javascripts_cms', $bundleName, $templateName);
                if ($this->container->hasParameter($param)) $this->addJavascripts($this->container->getParameter($param));

                $param = sprintf('%s.%s_stylesheets_cms', $bundleName, $templateName);
                if ($this->container->hasParameter($param)) $this->addStylesheets($this->container->getParameter($param));
            }
        }
    }
}
