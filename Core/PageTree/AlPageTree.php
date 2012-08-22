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
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\RepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlPageQuery;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery;
use AlphaLemon\ThemeEngineBundle\Core\Repository\AlThemeQuery;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\AlRepeatedSlotsManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel;
use AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper;

/**
 * Extends the bas AlPageTree object to fetch page information from the database
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageTree extends BaseAlPageTree
{
    protected $alPage = null;
    protected $alLanguage = null;
    protected $alSeo = null;
    protected $alTheme = null;
    protected $factoryRepository = null;
    protected $languageRepository = null;
    protected $pageRepository = null;
    protected $themeRepository = null;
    protected $seoRepository = null;
    protected $templateManager;
    protected $locatedAssets = array('css' => array(), 'js' => array());
    protected $isValidLanguage = false;
    protected $isValidPage = false;
    protected $parameterSchema = array('%s.%s_%s', '%s.%s_%s.cms');
    protected $themesCollectionWrapper;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param AlFactoryRepositoryInterface $factoryRepository
     * @param AlThemesCollectionWrapper $themesCollectionWrapper
     */
    public function __construct(ContainerInterface $container,
                                AlFactoryRepositoryInterface $factoryRepository,
                                AlThemesCollectionWrapper $themesCollectionWrapper = null)
    {
        $this->themesCollectionWrapper = (null === $themesCollectionWrapper) ? $container->get('alphalemon_cms.themes_collection_wrapper') : $themesCollectionWrapper;
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
        $this->pageRepository = $this->factoryRepository->createRepository('Page');
        $this->seoRepository = $this->factoryRepository->createRepository('Seo');
        $this->themeRepository = $this->factoryRepository->createRepository('Theme');

        parent::__construct($container); // , $templateManager->getPageBlocks()
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
    public function getAlSeo()
    {
        return $this->alSeo;
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
     * Sets the template manager
     *
     * @param AlTemplateManager $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree
     */
    public function setTemplateManager(AlTemplateManager $v)
    {
        $this->templateManager = $v;

        return $this;
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

    public function getTemplate()
    {
        return (null !== $this->templateManager) ? $this->templateManager->getTemplate() : $this->template;
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
    public function setUp()
    {
        try
        {
            $this->alLanguage = $this->setupLanguageFromSession();
            $this->alPage = $this->setupPageFromRequest();
            if (null === $this->alLanguage || null === $this->alPage) {
                return null;
            }

            if(null === $this->initTheme())
            {
                return null;
            }

            $this->templateManager = $this->themesCollectionWrapper->assignTemplate($this->alTheme->getThemeName(), $this->alPage->getTemplateName());
            
            $this->refresh($this->alLanguage->getId(), $this->alPage->getId());

            return $this;
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
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree
     */
    public function refresh($idLanguage, $idPage)
    {
        $this->alLanguage = $this->languageRepository->fromPK($idLanguage);
        $this->alPage = $this->pageRepository->fromPK($idPage);

        if (null === $this->alTheme) {
            if(null === $this->initTheme())
            {
                return null;
            }
        }

        $this->templateManager = $this->themesCollectionWrapper->assignTemplate($this->alTheme->getThemeName(), $this->alPage->getTemplateName());

        $this->pageBlocks = $this->templateManager
                    ->getPageBlocks()
                    ->setIdLanguage($idLanguage)
                    ->setIdPage($idPage)
                    ->refresh();

        $this->templateManager
                    ->setPageBlocks($this->pageBlocks)
                    //->setTemplateSlots($this->template->getTemplateSlots())
                    ->refresh();

        $this->alSeo = $this->seoRepository->fromPageAndLanguage($idLanguage, $idPage);
        $this->setUpMetaTags($this->alSeo);

        return $this;
    }

    private function initTheme()
    {
        $this->alTheme = $this->themeRepository->activeBackend();
        if (null === $this->alTheme) {
            return null;
        }

        return true;
    }

    /**
     * Returns the template's slots
     *
     * @return array
     */
    public function getBlockManagers($slotName)
    {
        $templateManager = $this->themesCollectionWrapper->getTemplateManager();
        if (null !== $templateManager) {
            $slotManager = $templateManager->getSlotManager($slotName);
            if (null !== $slotManager) {
                return $slotManager->getBlockManagers();
            }
        }
        
        return array(); 
    }    


    /**
     * {@ inheritdoc}
     */
    protected function mergeAssets($method, $assetType, $type)
    {
        $template = $this->getTemplate();
        if(null === $template) return array();

        $assetsCollection = $template->$method();
        if(null !== $assetsCollection) {
            // When a block has examined, it is saved in this array to avoid parsing it again
            $appsAssets = array();
            $assetsCollection = clone($assetsCollection);
            $blocks = $this->pageBlocks->getBlocks();
            foreach ($blocks as $slotBlocks) {
                foreach ($slotBlocks as $block) {
                    $className = $block->getClassName();
                    if (!in_array($className, $appsAssets)) {
                        foreach ($this->parameterSchema as $parameterSchema) {
                            $parameter = sprintf($parameterSchema, strtolower($className), $type, $assetType);
                            $assetsCollection->addRange(($this->container->hasParameter($parameter)) ? $this->container->getParameter($parameter) : array());
                        }

                        $appsAssets[] = $className;
                    }

                    $method = 'get'. ucfirst($type) . ucfirst($assetType);
                    $method = substr($method, 0, strlen($method) - 1);
                    $assetsCollection->addRange(explode(',', $block->$method()));
                }
            }

            return $assetsCollection;
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

        $alLanguage = ((int)$language > 0) ? $this->languageRepository->fromPK($language) : $this->languageRepository->fromLanguageName($language);
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

        $seo = $this->seoRepository->fromPermalink($pageName);
        if (null === $seo) {
            $seo = $this->seoRepository->fromPageAndLanguage($pageName, $this->alLanguage->getId());
        }

        if (null === $seo) {
            $alPage = $this->pageRepository->fromPageName($pageName);
            if (null === $alPage) {
                $alPage = $this->pageRepository->fromPK($pageName);
                if (!$alPage) {
                    return null;
                }
            }
        }
        else {
            $alPage = $seo->getAlPage();
            $this->setUpMetaTags($seo);
        }

        $this->isValidPage = true;

        return $alPage;
    }

    protected function setUpMetaTags($seo)
    {
        if(null !== $seo) {
            $this->metaTitle = $seo->getMetaTitle();
            $this->metaDescription = $seo->getMetaDescription();
            $this->metaKeywords = $seo->getMetaKeywords();
        }
    }
}
