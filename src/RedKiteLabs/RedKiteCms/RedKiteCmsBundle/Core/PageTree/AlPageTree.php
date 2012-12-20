<?php
/**
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

use AlphaLemon\ThemeEngineBundle\Core\PageTree\AlPageTree as BaseAlPageTree;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper;
use AlphaLemon\ThemeEngineBundle\Core\Theme\AlTheme;
use Symfony\Component\DependencyInjection\Container;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\PageTree;

/**
 * {@inheritdoc}
 * 
 * Extends the base AlPageTree object to fetch page information from the database
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageTree extends BaseAlPageTree
{
    protected $alPage = null;
    protected $alLanguage = null;
    protected $alSeo = null;
    protected $theme = null;
    protected $factoryRepository = null;
    protected $languageRepository = null;
    protected $pageRepository = null;
    protected $seoRepository = null;
    protected $templateManager;
    protected $dispatcher;
    protected $locatedAssets = array('css' => array(), 'js' => array());
    protected $extraAssetsSuffixes = array('cms');
    protected $themesCollectionWrapper;
    private $pageName = null;
    private $request = null;
    
    /**
     * Constructor
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper $themesCollectionWrapper
     * 
     * @api
     */
    public function __construct(ContainerInterface $container,
                                AlFactoryRepositoryInterface $factoryRepository,
                                AlThemesCollectionWrapper $themesCollectionWrapper = null)
    {
        $this->themesCollectionWrapper = (null === $themesCollectionWrapper) ? $container->get('alpha_lemon_cms.themes_collection_wrapper') : $themesCollectionWrapper;
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
        $this->pageRepository = $this->factoryRepository->createRepository('Page');
        $this->seoRepository = $this->factoryRepository->createRepository('Seo');
        $this->dispatcher = $container->get('event_dispatcher');

        parent::__construct($container);
    }

    /**
     * Returns the current AlPage object
     *
     * @return AlPage instance
     * 
     * @api
     */
    public function getAlPage()
    {
        return $this->alPage;
    }

    /**
     * Returns the current AlLanguage object
     *
     * @return AlLanguage instance
     * 
     * @api
     */
    public function getAlLanguage()
    {
        return $this->alLanguage;
    }

    /**
     * Returns the current AlSeo object
     *
     * @return AlSeo instance
     * 
     * @api
     */
    public function getAlSeo()
    {
        return $this->alSeo;
    }

    /**
     * Returns the current AlTheme object
     *
     * @return \AlphaLemon\ThemeEngineBundle\Core\Theme\AlTheme
     * 
     * @api
     */
    public function getTheme()
    {
        return $this->theme;
    }
    
    /**
     * Sets the template manager
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree
     * 
     * @api
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
     * 
     * @api
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
     * Returns true when both AlPage and AlLanguage have been setted
     *
     * @return boolean
     * 
     * @api
     */
    public function isValid()
    {
        return (null !== $this->alPage && null !== $this->alLanguage) ? true : false;
    }

    /**
     * Returns the current template
     *
     * @return \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate
     * 
     * @api
     */
    public function getTemplate()
    {
        return (null !== $this->templateManager) ? $this->templateManager->getTemplate() : $this->template;
    }
    
    /**
     * Sets up the page tree object from current request
     * 
     * @return null|\AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree
     * @throws \Exception
     */
    public function setUp()
    {
        try { 
            $this->dispatcher->dispatch(PageTree\PageTreeEvents::BEFORE_PAGE_TREE_SETUP, new PageTree\BeforePageTreeSetupEvent($this));
            
            $this->pageName = $this->getRequest()->get('page');
            if (!$this->pageName || $this->pageName == "" || $this->pageName == "backend") {
                return null;
            }

            $this->alSeo = $this->seoRepository->fromPermalink($this->pageName);
            $this->alLanguage = $this->setupLanguage();
            
            $this->alPage = $this->setupPage();
            if (null === $this->alLanguage || null === $this->alPage) {
                return null;
            }

            if (null === $this->initTheme()) {
                return null;
            }

            $this->templateManager = $this->themesCollectionWrapper->assignTemplate($this->theme->getThemeName(), $this->alPage->getTemplateName());
            $this->doRefresh();
            
            $this->dispatcher->dispatch(PageTree\PageTreeEvents::AFTER_PAGE_TREE_SETUP, new PageTree\AfterPageTreeSetupEvent($this));
            
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Refreshes the page tree object with the given language and page identities
     * 
     * @param int $idLanguage
     * @param int $idPage
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree
     */
    public function refresh($idLanguage, $idPage)
    {
        $this->dispatcher->dispatch(PageTree\PageTreeEvents::BEFORE_PAGE_TREE_REFRESH, new PageTree\BeforePageTreeRefreshEvent($this));
        
        $this->alLanguage = $this->languageRepository->fromPK($idLanguage);
        $this->alPage = $this->pageRepository->fromPK($idPage);

        if (null === $this->alSeo) { 
            $this->alSeo = $this->seoRepository->fromPageAndLanguage($idLanguage, $idPage);
        }
        
        if (null === $this->theme) {
            if (null !== $this->initTheme()) {
                $this->templateManager = $this->themesCollectionWrapper->assignTemplate($this->theme->getThemeName(), $this->alPage->getTemplateName());
            }
        }
        
        $this->doRefresh();
        
        $this->dispatcher->dispatch(PageTree\PageTreeEvents::AFTER_PAGE_TREE_REFRESH, new PageTree\AfterPageTreeRefreshEvent($this));
        
        return $this;
    }

    /**
     * Sets the external assets suffixes. These suffixes tells AlphaLemon CMS that there are some parameters
     * declared in the DIC that must be used when the CMS is active.
     *
     * By default, AlphaLemon CMS, lets you add a parameter that must be added only when the CMS
     * is active simply adding a .cms suffix to that parameter.
     *
     * For example, let's suppose you have a block with an absolute position declared. AlphaLemon CMS
     * has a fixed toolbar that has a certain height, so, to display that content properly, you must add a new
     * stylesheet that must be loaded only when you are in CMS mode. That task is achieved adding a parameter
     * suffixed with the ".cms" suffix (businesswebsitetheme.home.external_stylesheets.cms)
     *
     * @param  array $value
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree
     */
    public function setExtraAssetsSuffixes(array $value = array())
    {
        $this->extraAssetsSuffixes = $value;

        return $this;
    }

    /**
     * Returns the page's block managers
     *
     * @return array
     * 
     * @api
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
        if (null !== $assetsCollection) {
            // When a block has examined, it is saved in this array to avoid parsing it again
            $appsAssets = array();
            $assetsCollection = clone($assetsCollection);

            // merges extra assets from current theme
            $themeName = $template->getThemeName();
            $themeBasename = str_replace('Bundle', '', $themeName);
            $extensionAlias = Container::underscore($themeBasename);
            $parameter = sprintf('%s.%s.%s_%s', $extensionAlias, $template->getTemplateName(), $type, $assetType);
            $this->addExtraAssets($assetsCollection, $parameter);

            // merges assets from installed apps
            $templateSlots = array_keys($template->getSlots());
            $blocks = $this->pageBlocks->getBlocks();
            foreach ($blocks as $slotName => $slotBlocks) {
                if ( ! in_array($slotName, $templateSlots)) {
                    continue;
                }
                
                foreach ($slotBlocks as $block) {
                    $className = $block->getType();
                    if (!in_array($className, $appsAssets)) {
                        $parameterSchema = '%s.%s_%s';
                        $parameter = sprintf($parameterSchema, strtolower($className), $type, $assetType);
                        $this->addAssetsFromContainer($assetsCollection, $parameter);
                        $this->addExtraAssets($assetsCollection, $parameter);

                        $appsAssets[] = $className;
                    }

                    $method = 'get'. ucfirst($type) . ucfirst($assetType);
                    $method = substr($method, 0, - 1);
                    $assets = $block->$method();
                    if ($type == "external") {
                        $assetsCollection->addRange(explode(',', $assets));
                    } else {
                        $assetsCollection->add($assets);
                    }
                }
            }

            return $assetsCollection;
        }
    }

    /**
     * Sets up the AlLanguage object from the current request
     *
     * @return null|AlLanguage
     */
    protected function setupLanguage()
    {
        if (null !== $this->alSeo) {
            return $this->alSeo->getAlLanguage();
        }
        
        $request = $this->getRequest();
        $languageId = $request->get('languageId');        
        $alLanguage = (null === $languageId) ? $this->languageRepository->fromLanguageName($request->get('_locale')) : $this->languageRepository->fromPK($languageId);
        
        return $alLanguage;
    }

    /**
     * Sets up the AlLanguage object from the current request
     *
     * @return null
     */
    protected function setupPage()
    {
        if (null === $this->alLanguage) {
            return null;
        }
        
        if (null !== $this->alSeo) {
            return $this->alSeo->getAlPage();
        }

        $this->alSeo= $this->seoRepository->fromPageAndLanguage($this->pageName, $this->alLanguage->getId());
        if (null === $this->alSeo) {
            $pageId = $this->getRequest()->get('pageId');    
            $alPage = (null === $pageId) ? $this->pageRepository->fromPageName($this->pageName) : $this->pageRepository->fromPK($pageId);
        } else {
            $alPage = $this->alSeo->getAlPage();
            $this->setUpMetaTags();
        }

        if (null !== $alPage) {

            return $alPage;
        }

        return null;
    }

    /**
     * Sets up the metatags section
     *
     * @param AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo $seo
     */
    protected function setUpMetaTags()
    {
        if (null !== $this->alSeo) {
            $this->metaTitle = $this->alSeo->getMetaTitle();
            $this->metaDescription = $this->alSeo->getMetaDescription();
            $this->metaKeywords = $this->alSeo->getMetaKeywords();
        }
    }

    /**
     * Adds a range of assets to the assets collection
     *
     * @param \AlphaLemon\ThemeEngineBundle\Core\Asset\AlAssetCollection $assetsCollection
     * @param string $parameter The parameter to fetch from the Container
     */
    protected function addAssetsFromContainer(&$assetsCollection, $parameter)
    {
        $assetsCollection->addRange($this->container->hasParameter($parameter) ? $this->container->getParameter($parameter) : array());
    }

    /**
     * Adds to the assets collection the extra parameters defined by extraAssetsSuffixes
     *
     * @param \AlphaLemon\ThemeEngineBundle\Core\Asset\AlAssetCollection $assetsCollection
     * @param string $baseParam
     */
    protected function addExtraAssets(&$assetsCollection, $baseParam)
    {
        foreach ($this->extraAssetsSuffixes as $suffix) {
            $parameter = sprintf('%s.%s', $baseParam, $suffix);
            $this->addAssetsFromContainer($assetsCollection, $parameter);
        }
    }

    private function initTheme()
    {
        $themeName = $this->activeTheme->getActiveTheme();
        if (null === $themeName) {
            return $themeName;
        }

        $this->theme = new AlTheme($themeName);

        return true;
    }
    
    private function getRequest()
    {
        if (null === $this->request) {
            $this->request = $this->container->get('request');
        }
        
        return $this->request;
    }
    
    

    private function doRefresh()
    {
        if (null === $this->templateManager) {
            return;
        }
        
        $idLanguage = $this->alLanguage->getId();
        $idPage = $this->alPage->getId();

        $this->pageBlocks = $this->templateManager->getPageBlocks();
        if (null === $this->pageBlocks) {
            return;
        }
        
        $this->pageBlocks
             ->setIdLanguage($idLanguage)
             ->setIdPage($idPage)
             ->refresh();

        $this->templateManager
             ->setPageBlocks($this->pageBlocks)
             ->refresh();
        
        $this->setUpMetaTags($this->alSeo);
    }
}