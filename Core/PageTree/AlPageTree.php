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

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\PageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocksInterface;
use RedKiteLabs\RedKiteCmsBundle\Model\AlPage;
use RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Deprecated\RedKiteDeprecatedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\DataManager\DataManager;
use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface;

/**
 * Defines an object which stores all the web page information as a tree
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 * 
 * @method     AlPageTree getTheme() Returns the handled AlTheme object
 * @method     AlPageTree getTemplate() Returns the handled AlTemplate object
 * @method     AlPageTree getTemplateManager() Returns the handled AlTemplateManager object
 * @method     AlPageTree getPageBlocks() Returns the handled AlPageBlocks object
 * @method     AlPageTree getAlPage() Returns the handled AlPage object
 * @method     AlPageTree getAlLanguage() Returns the handled AlLanguage object
 * @method     AlPageTree getAlSeo() Returns the handled AlSeo object
 * @method     AlPageTree getExternalStylesheets() Returns the handled external stylesheets
 * @method     AlPageTree getInternalStylesheets() Returns the handled internal stylesheets
 * @method     AlPageTree getExternalJavascripts() Returns the handled external javascripts
 * @method     AlPageTree getInternalJavascripts() Returns the handled internal javascripts
 * @method     AlPageTree getMetaTitle() Returns the metatag Title attribute
 * @method     AlPageTree getMetaDescription() Returns the metatag Description attribute
 * @method     AlPageTree getMetaKeywords() Returns the metatag Keywords attribute
 */
class AlPageTree
{
    private $template = null;
    private $pageBlocks;
    private $metaTitle = "";
    private $metaDescription = "";
    private $metaKeywords = "";
    private $theme = null;
    private $templateManager;
    private $dispatcher;
    private $assetsManager;
    private $cmsMode = true;
    private $dataManager;

    /**
     * Constructor
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager $templateAssetsManager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventsDispatcher
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\DataManager\DataManager $dataManager
     */
    public function __construct(TemplateAssetsManager $templateAssetsManager, EventDispatcherInterface $eventsDispatcher = null, DataManager $dataManager = null)
    {
        $this->assetsManager = $templateAssetsManager;
        $this->dispatcher = $eventsDispatcher;
        if (null !== $dataManager) {
            $this->dataManager = $dataManager;
        }
    }
    
    /**
     * Sets the TemplateAssetsManager
     *  
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager $templateAssetsManager
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     */
    public function setTemplateAssetsManager(TemplateAssetsManager $templateAssetsManager)
    {
        $this->assetsManager = $templateAssetsManager;
        
        return $this;
    }
    
    /**
     * Sets the DataManager
     *  
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager $templateAssetsManager
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     */
    public function setDataManager(DataManager $dataManager)
    {
        $this->dataManager = $dataManager;
        
        return $this;
    }

    /**
     * Creates magic methods
     *
     * @param  string $name   the method name
     * @param  mixed  $params the values to pass to the called method
     * @return mixed  Depends on method called
     */
    public function __call($name, $params)
    {
        if (preg_match('/^(get)?(External)?([Styleshee|Javascrip]+ts)$/', $name, $matches)) {
            $method = $matches[0];
            $assets = $this->assetsManager->$method();
            if (null === $assets) {
                $assets = array();
            }
            
            return $assets;
        }

        if (preg_match('/^(get)?(Internal)?([Styleshee|Javascrip]+ts)$/', $name, $matches)) {
            $method = $matches[0];
            $assets = $this->assetsManager->$method();
            if (null === $assets) {
                return "";
            }
            
            return implode("\n", $assets);
        }

        if (preg_match('/^(get)?(Meta)?([Title|Description|Keywords]+)$/', $name, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];

            return $this->$property;
        }
        
        if (preg_match('/^(get)?Al?([Page|Language|Seo]+)$/', $name, $matches)) {
            $property = strtolower($matches[1]) . $matches[2];
            
            if (null === $this->dataManager) {
                return null;
            }
            
            return $this->dataManager->$property();
        }
        
        if (preg_match('/^(get)?([Theme|Template|TemplateManager|PageBlocks]+)$/', $name, $matches)) {
            $property = lcfirst($matches[2]);

            return $this->$property;
        }

        throw new \RuntimeException('Call to undefined method: AlPageTree->' . $name . '()');
    }

    /**
     * Returns true when RedKiteCms is in CMS mode
     *
     * @return boolean
     */
    public function isCmsMode()
    {
        return $this->cmsMode;
    }
    
    /**
     * Sets the RedKiteCms mode
     * 
     * @param boolean $value
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     */
    public function productionMode($value)
    {
        $this->cmsMode = ! $value;
        
        return $this;
    }

    /**
     * Returns the page's block managers
     *
     * @return array
     *
     * @api
     */
    
    /**
     * Returns the block managers handled by the PageTree object
     * 
     * @param string $slotName
     * @return array
     */
    public function getBlockManagers($slotName)
    {
        if (null === $this->templateManager) {
            return array();
        }
        
        $slotManager = $this->templateManager->getSlotManager($slotName);
        if (null === $slotManager) {
            return array();
        }

        return $slotManager->getBlockManagersCollection()->getBlockManagers();
    }

    /**
     * Sets up the PageTree object
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlTheme $theme
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager $templateManager
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocksInterface $pageBlocks
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate $template
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     */
    public function setUp(AlThemeInterface $theme, AlTemplateManager $templateManager, AlPageBlocksInterface $pageBlocks, AlTemplate $template = null)
    {
        $this->templateManager = $templateManager;
        $this->pageBlocks = $pageBlocks;
        $this->theme = $theme;
                
        try {
            $this->dispatch(PageTree\PageTreeEvents::BEFORE_PAGE_TREE_SETUP, new PageTree\BeforePageTreeSetupEvent($this));
            
            $language = $this->getAlLanguage();
            $page = $this->getAlPage();        
            if (null !== $language && null !== $page) {
                $this->pageBlocks->refresh($language->getId(), $page->getId());
            }

            $options = array();
            if (null !== $language) {
                $options["language"] = $language->getLanguageName();
            }

            $templateName = "";
            if (null !== $page) {
                $options["page"] = $page->getPageName();
                $templateName = $page->getTemplateName();            
            }

            $this->template = (null === $template) ? $this->theme->getTemplate($templateName) : $template;
            if (null === $this->template) {
                return;
            }

            $this->assetsManager
                ->withExtraAssets($this->cmsMode)
                ->setUp($this->template, $options)
            ;

            $this->templateManager
                 ->refresh($this->theme->getThemeSlots(), $this->template, $this->pageBlocks);

            $this->setUpMetaTags($this->getAlSeo());
            
            $this->dispatch(PageTree\PageTreeEvents::AFTER_PAGE_TREE_SETUP, new PageTree\AfterPageTreeSetupEvent($this));
                        
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Sets up the metatags section
     */
    protected function setUpMetaTags()
    {
        $seo = $this->getAlSeo();
                
        if (null !== $seo) {
            $this->metaTitle = $seo->getMetaTitle();
            $this->metaDescription = $seo->getMetaDescription();
            $this->metaKeywords = $seo->getMetaKeywords();
        }
    }
    
    private function dispatch($eventName, $event)
    {
        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch($eventName, $event);
        }
    }
    
    /**
     * Returns the container
     *
     * @return Symfony\Component\DependencyInjection\ContainerInterface
     * 
     * @deprecated since 1.1.0
     */
    public function getContainer()
    {
        throw new RedKiteDeprecatedException('pageTree->getContainer() has been deprecated');
    }

    /**
     * Sets the pageBlocks object
     *
     * @param  AlPageBlocksInterface                                   $v
     * @return \RedKiteLabs\ThemeEngineBundle\Core\PageTree\AlPageTree
     * 
     * @deprecated since 1.1.0
     */
    public function setPageBlocks(AlPageBlocksInterface $v)
    {
        throw new RedKiteDeprecatedException('pageTree->setPageBlocks() has been deprecated');
    }

    /**
     * Sets the AlPage object
     *
     * @param  AlPage                                                 $alPage
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     * 
     * @deprecated since 1.1.0
     */
    public function setAlPage(AlPage $alPage)
    {
        throw new RedKiteDeprecatedException('pageTree->setAlPage() has been deprecated');
    }

    /**
     * Sets the AlLanguage object
     *
     * @param  AlLanguage                                             $alLanguage
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     * 
     * @deprecated since 1.1.0
     */
    public function setAlLanguage(AlLanguage $alLanguage)
    {
        throw new RedKiteDeprecatedException('pageTree->setAlLanguage() has been deprecated');
    }

    /**
     * Sets the template manager
     *
     * @param  \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     * 
     * @deprecated since 1.1.0
     */
    public function setTemplateManager(AlTemplateManager $v)
    {
        throw new RedKiteDeprecatedException('pageTree->setTemplateManager() has been deprecated');
    }
    

    /**
     * @inheritdoc
     * 
     * @deprecated since 1.1.0
     */
    public function setTemplate(AlTemplate $v)
    {
        throw new RedKiteDeprecatedException('pageTree->setTemplate() has been deprecated');
    }

    /**
     * Refreshes the page tree object with the given language and page identities
     *
     * @param  int                                                    $idLanguage
     * @param  int                                                    $idPage
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     * 
     * @deprecated since 1.1.0
     */
    public function refresh($idLanguage, $idPage)
    {
        throw new RedKiteDeprecatedException('pageTree->refresh() has been deprecated. Use the setUp method instead');
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
     * 
     * @deprecated since 1.1.0
     */
    public function setMetatags(array $metatags)
    {
        throw new RedKiteDeprecatedException('pageTree->setMetatags() has been deprecated.');
    }
    
    /**
     * Returns true when both AlPage and AlLanguage have been setted
     *
     * @return boolean
     *
     * @deprecated since 1.1.0
     */
    public function isValid()
    {
        throw new RedKiteDeprecatedException('pageTree->isValid() has been deprecated.');
    }
}