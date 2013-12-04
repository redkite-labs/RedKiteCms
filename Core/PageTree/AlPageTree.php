<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\PageTree;

use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme;
use Symfony\Component\DependencyInjection\Container;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\PageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocksInterface;
use RedKiteLabs\RedKiteCmsBundle\Model\AlPage;
use RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage;

/**
 * Defines an object which stores all the web page information as a tree
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlPageTree 
{
    protected $container = null;
    protected $template = null;
    protected $pageBlocks;
    protected $metaTitle = "";
    protected $metaDescription = "";
    protected $metaKeywords = "";
    protected $activeTheme;
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
    protected $blockManagerFactory = null;
    private $pageName = null;
    private $request = null;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface                              $container
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface   $factoryRepository
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper $themesCollectionWrapper
     *
     * @api
     */
    public function __construct(ContainerInterface $container,
                                AlFactoryRepositoryInterface $factoryRepository,
                                AlThemesCollectionWrapper $themesCollectionWrapper = null)
    {
        $this->themesCollectionWrapper = (null === $themesCollectionWrapper) ? $container->get('red_kite_cms.themes_collection_wrapper') : $themesCollectionWrapper;
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
        $this->pageRepository = $this->factoryRepository->createRepository('Page');
        $this->seoRepository = $this->factoryRepository->createRepository('Seo');
        $this->dispatcher = $container->get('event_dispatcher');
        $this->templateManager = $this->themesCollectionWrapper->getTemplateManager();
        $this->blockManagerFactory = $container->get('red_kite_cms.block_manager_factory');
        
        $this->container = $container;
        $this->activeTheme = $this->container->get('red_kite_cms.active_theme');
    }

    /**
     * Returns the container
     *
     * @return Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the pageBlocks object
     *
     * @param AlPageBlocksInterface $v
     * @return \RedKiteLabs\ThemeEngineBundle\Core\PageTree\AlPageTree
     */
    public function setPageBlocks(AlPageBlocksInterface $v)
    {
        $this->pageBlocks = $v;

        return $this;
    }

    /**
     * Returns the current pageBlocks object
     *
     * @return AlPageBlocksInterface
     */
    public function getPageBlocks()
    {
        return $this->pageBlocks;
    }


    /**
     * Sets the page metatags
     *
     *
     * The metatags array could have the following keys:
     *
     *      - title
     *      - description
     *      - keywords
     *
     * @param array $metatags
     */
    public function setMetatags(array $metatags)
    {
        if(array_key_exists('title', $metatags)) $this->metaTitle = $metatags['title'];
        if(array_key_exists('description', $metatags)) $this->metaDescription = $metatags['description'];
        if(array_key_exists('keywords', $metatags)) $this->metaKeywords = $metatags['keywords'];

        return $this;
    }

    /**
     * Catches the methods to manage assets and metatags
     *
     * @param string $name the method name
     * @param mixed $params the values to pass to the called method
     * @return mixed Depends on method called
     */
    public function __call($name, $params)
    {
        if(preg_match('/^(add)?(External)?([Styleshee|Javascrip]+t)$/', $name, $matches))
        {
            $method = $matches[0];
            $this->getTemplate()->$method($params[0]);

            return $this;
        }

        if(preg_match('/^(add)?(External)?([Styleshee|Javascrip]+ts)$/', $name, $matches))
        {
            $method = $matches[0];
            $this->getTemplate()->$method($params);

            return $this;
        }

        if(preg_match('/^(get)?(External)?([Styleshee|Javascrip]+ts)$/', $name, $matches))
        {
            return $this->getAssets($matches[0], strtolower($matches[3]), strtolower($matches[2]));
        }

        if(preg_match('/^(get)?(Internal)?([Styleshee|Javascrip]+ts)$/', $name, $matches))
        {
            return implode("", $this->getAssets($matches[0], strtolower($matches[3]), strtolower($matches[2])));
        }

        if(preg_match('/^(get)?(Meta)?([Title|Description|Keywords]+)$/', $name, $matches))
        {
            $property = strtolower($matches[2]) . $matches[3];

            return $this->$property;
        }

        if(preg_match('/^(set)?(Meta)?([Title|Description|Keywords]+)$/', $name, $matches))
        {
            $property = strtolower($matches[2]) . $matches[3];
            $this->$property = $params[0];

            return $this;
        }

        throw new \RuntimeException('Call to undefined method: ' . $name);
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
     * Sets the AlPage object
     * 
     * @param AlPage $alPage
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     *
     * @api
     */
    public function setAlPage(AlPage $alPage)
    {
        $this->alPage = $alPage;

        return $this;
    }

    /**
     * Sets the AlLanguage object
     * 
     * @param AlLanguage $alLanguage
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     *
     * @api
     */
    public function setAlLanguage(AlLanguage $alLanguage)
    {
        $this->alLanguage = $alLanguage;

        return $this;
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
     * @return \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme
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
     * @param  \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
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
     * RedKiteCms is in CMS mode
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
     * @inheritdoc
     */
    public function setTemplate(AlTemplate $v)
    {
        $this->templateManager
            ->setTemplate($v)
            ->refresh()
        ;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @api
     */
    public function getTemplate()
    {
        if (null !== $this->templateManager) {
            return $this->templateManager->getTemplate();
        }
        
        return $this->template;
    }

    /**
     * Sets up the page tree object from current request
     *
     * @return null|\RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     * @throws \Exception
     */
    public function setUp()
    {
        try {
            $this->dispatcher->dispatch(PageTree\PageTreeEvents::BEFORE_PAGE_TREE_SETUP, new PageTree\BeforePageTreeSetupEvent($this));

            $request = $this->getRequest();
            $this->pageName = $request->get('page');
            if ( ! $this->pageName || $this->pageName == "" || $this->pageName == "backend") {
                return null;
            }

            $this->alLanguage = $this->setupLanguage();
            $this->alPage = $this->setupPage();
            if (null === $this->alLanguage || null === $this->alPage) {
                $this->alSeo = $this->seoRepository->fromPermalink($this->pageName);
                if (null === $this->alSeo) {
                    $permalink = $request->get('_locale');
                    $this->alSeo = $this->seoRepository->fromPermalink($permalink);
                    if (null === $this->alSeo) {
                        return null;
                    }
                }
                $this->alLanguage = $this->alSeo->getAlLanguage();
                $this->alPage = $this->alSeo->getAlPage();
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
     * @param  int                                                      $idLanguage
     * @param  int                                                      $idPage
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
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
     * Sets the external assets suffixes. These suffixes tells RedKiteCms that there are some parameters
     * declared in the DIC that must be used when the CMS is active.
     *
     * By default, RedKiteCms, lets you add a parameter that must be added only when the CMS
     * is active simply adding a .cms suffix to that parameter.
     *
     * For example, let's suppose you have a block with an absolute position declared. RedKiteCms
     * has a fixed toolbar that has a certain height, so, to display that content properly, you must add a new
     * stylesheet that must be loaded only when you are in CMS mode. That task is achieved adding a parameter
     * suffixed with the ".cms" suffix (businesswebsitetheme.home.external_stylesheets.cms)
     *
     * @param  array                                                    $value
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
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
     * {@inheritdoc}
     */
    protected function mergeAssets($method, $assetType, $type)
    {
        $template = $this->getTemplate();
        if (null === $template) {
            return array();
        }

        $assetsCollection = $template->$method();
        if (null !== $assetsCollection) {

            $assetsCollection = clone($assetsCollection);

            // merges extra assets from current theme
            $themeName = $template->getThemeName();
            $themeBasename = str_replace('Bundle', '', $themeName);
            $extensionAlias = Container::underscore($themeBasename);
            $parameter = sprintf('%s.%s.%s_%s', $extensionAlias, $template->getTemplateName(), $type, $assetType);
            $this->addExtraAssets($assetsCollection, $parameter);

            // merges assets for theme engine registered listeners
            $registeredListeners = $this->container->get('red_kite_labs_theme_engine.registed_listeners');
            
            foreach ($registeredListeners as $registeredListener) {
                // Assets from page_renderer.before_page_rendering listeners
                $parameter = sprintf('%s.page.%s_%s', $registeredListener, $type, $assetType);
                $this->addAssetsFromContainer($assetsCollection, $parameter);

                // Assets from page_renderer.before_[language]_rendering listeners
                if (null !== $this->alLanguage) {
                    $parameter = sprintf('%s.%s.%s_%s', $registeredListener, $this->alLanguage->getLanguageName(), $type, $assetType);
                    $this->addAssetsFromContainer($assetsCollection, $parameter);
                }

                // Assets from page_renderer.before_[page]_rendering listeners
                if (null !== $this->alPage) {
                    $parameter = sprintf('%s.%s.%s_%s', $registeredListener, $this->alPage->getPageName(), $type, $assetType);
                    $this->addAssetsFromContainer($assetsCollection, $parameter);
                }
            }

            $this->mergeAppBlocksAssets($assetsCollection, $type, $assetType);

            $slots = $template->getSlots();
            if (null !== $slots && ! empty($slots)) {
                $templateSlots = array_keys($slots);
                $blocks = $this->pageBlocks->getBlocks();
                foreach ($blocks as $slotName => $slotBlocks) {

                    if ( ! in_array($slotName, $templateSlots)) {
                        continue;
                    }

                    foreach ($slotBlocks as $block) {
                        //$className = $block->getType();
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
            }
            
            // Sets back the collection to the Template
            $method = 's' . substr($method, 1);
            if (substr($method, -1) != 's') {
                $method .= 's';
            }
            $template->$method($assetsCollection);
            
            return $assetsCollection;
        }
    }
    
    protected function mergeAppBlocksAssets($assetsCollection, $type, $assetType)
    {
        // When a block has examined, it is saved in this array to avoid parsing it again
        $appsAssets = array();

        // merges assets from installed apps  
        $availableBlocks = $this->blockManagerFactory->getAvailableBlocks();     
        foreach ($availableBlocks as $className) {
            if ( ! in_array($className, $appsAssets)) {                    
                $parameterSchema = '%s.%s_%s';
                $parameter = sprintf($parameterSchema, strtolower($className), $type, $assetType);
                $this->addAssetsFromContainer($assetsCollection, $parameter);
                $this->addExtraAssets($assetsCollection, $parameter);

                $appsAssets[] = $className;
            }
        }
    }

    /**
     * Sets up the AlLanguage object from the current request
     *
     * @return null|AlLanguage
     */
    protected function setupLanguage()
    {
        $request = $this->getRequest();
        $languageId = $request->get('languageId');

        if ((null !== $this->alSeo && null === $languageId) || null !== $this->alSeo && $this->alSeo->getAlLanguage()->getId() == $languageId) {
            return $this->alSeo->getAlLanguage();
        }

        return (null === $languageId || (int)$languageId == 0) ? $this->languageRepository->fromLanguageName($request->get('_locale')) : $this->languageRepository->fromPK($languageId);
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
        
        $pageId = (int)$this->getRequest()->get('pageId');
        $this->alSeo= $this->seoRepository->fromPageAndLanguage($this->alLanguage->getId(), $pageId);
        if (null === $this->alSeo) {            
            return ($pageId == 0) ? $this->pageRepository->fromPageName($this->pageName) : $this->pageRepository->fromPK($pageId);
        } 
        
        $alPage = $this->alSeo->getAlPage();
        $this->setUpMetaTags();
        
        return $alPage;
    }

    /**
     * Sets up the metatags section
     *
     * @param RedKiteLabs\RedKiteCmsBundle\Model\AlSeo $seo
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
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetCollection $assetsCollection
     * @param string                                                     $parameter        The parameter to fetch from the Container
     */
    protected function addAssetsFromContainer(&$assetsCollection, $parameter)
    {
        if ( ! $this->container->hasParameter($parameter)) {
            return;
        }
        
        $assets = $this->container->getParameter($parameter);
        $assetsCollection->addRange($assets);
    }

    /**
     * Adds to the assets collection the extra parameters defined by extraAssetsSuffixes
     *
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetCollection $assetsCollection
     * @param string                                                     $baseParam
     */
    protected function addExtraAssets(&$assetsCollection, $baseParam)
    {
        foreach ($this->extraAssetsSuffixes as $suffix) {
            $parameter = sprintf('%s.%s', $baseParam, $suffix);
            $this->addAssetsFromContainer($assetsCollection, $parameter);
        }
    }



    /**
     * Returns an array that contains the absolute path of each asset
     *
     * @param string $method The method to retrieve the current ArrayObject tha stores the requiredassets
     * @param string $assetType The assets type (stylesheet/javascript)
     * @param string $type The required type (internal/external)
     * @return array
     */
    protected function getAssets($method, $assetType, $type)
    {
        $assetsCollection = $this->mergeAssets($method, $assetType, $type);
        if(null === $assetsCollection) {
            return array();
        }

        $assets = array();
        foreach($assetsCollection as $asset)
        {
            $absolutePath = $asset->getAbsolutePath();
            $originalAsset = $asset->getAsset();
            $assets[] = ($type == 'external') ? (empty($absolutePath)) ? $originalAsset : $absolutePath : $originalAsset;
        }

        return $assets;
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
