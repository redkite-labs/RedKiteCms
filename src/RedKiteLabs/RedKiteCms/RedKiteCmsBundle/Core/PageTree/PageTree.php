<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template\TemplateManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\PageTree as PageTreeEvent;
use RedKiteLabs\ThemeEngineBundle\Core\Template\Template;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\PageBlocksInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\DataManager\DataManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface;

/**
 * Defines an object which stores all the web page information as a tree
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @method \RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme getTheme() Returns the handled Theme object
 * @method Template getTemplate() Returns the handled Template object
 * @method TemplateManager getTemplateManager() Returns the handled TemplateManager object
 * @method \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\PageBlocks getPageBlocks() Returns the handled PageBlocks object
 * @method \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page getPage() Returns the handled Page object
 * @method \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Language getLanguage() Returns the handled Language object
 * @method \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Seo getSeo() Returns the handled Seo object
 * @method PageTree getExternalStylesheets() Returns the handled external stylesheets
 * @method PageTree getInternalStylesheets() Returns the handled internal stylesheets
 * @method PageTree getExternalJavascripts() Returns the handled external javascripts
 * @method PageTree getInternalJavascripts() Returns the handled internal javascripts
 * @method string getMetaTitle() Returns the metatag Title attribute
 * @method string getMetaDescription() Returns the metatag Description attribute
 * @method string getMetaKeywords() Returns the metatag Keywords attribute
 */
class PageTree
{
    /** @var TemplateAssetsManager */
    private $assetsManager;
    /** @var null|EventDispatcherInterface */
    private $dispatcher = null;
    /** @var null|DataManager */
    private $dataManager = null;

    private $template = null;
    private $pageBlocks;
    private $metaTitle = "";
    private $metaDescription = "";
    private $metaKeywords = "";
    private $theme = null;
    /** @var null|TemplateManager */
    private $templateManager = null;
    private $cmsMode = true;

    /**
     * Constructor
     *
     * @param TemplateAssetsManager    $templateAssetsManager
     * @param EventDispatcherInterface $eventsDispatcher
     * @param DataManager              $dataManager
     */
    public function __construct(TemplateAssetsManager $templateAssetsManager, EventDispatcherInterface $eventsDispatcher = null, DataManager $dataManager = null)
    {
        $this->assetsManager = $templateAssetsManager;
        $this->dispatcher = $eventsDispatcher;
        $this->dataManager = $dataManager;
    }

    /**
     * Sets the TemplateAssetsManager
     *
     * @param  TemplateAssetsManager $templateAssetsManager
     * @return self
     */
    public function setTemplateAssetsManager(TemplateAssetsManager $templateAssetsManager)
    {
        $this->assetsManager = $templateAssetsManager;

        return $this;
    }

    /**
     * Sets the DataManager
     *
     * @param  DataManager $dataManager
     * @return self
     */
    public function setDataManager(DataManager $dataManager)
    {
        $this->dataManager = $dataManager;

        return $this;
    }

    /**
     * Creates magic methods
     *
     * @param  string            $name   the method name
     * @param  mixed             $params the values to pass to the called method
     * @throws \RuntimeException
     * @return mixed             Depends on method called
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

        if (preg_match('/^(get)?([Page|Language|Seo]+)$/', $name, $matches)) {
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

        throw new \RuntimeException('Call to undefined method: PageTree->' . $name . '()');
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
     * @param  boolean $value
     * @return self
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
     * @param  string $slotName
     * @return array
     */
    public function getBlockManagers($slotName)
    {
        $slotManager = $this->templateManager->getSlotManager($slotName);
        if (null === $slotManager) {
            return array();
        }

        return $slotManager->getBlockManagersCollection()->getBlockManagers();
    }

    /**
     * Sets up the PageTree object
     *
     * @param  ThemeInterface      $theme
     * @param  TemplateManager     $templateManager
     * @param  PageBlocksInterface $pageBlocks
     * @param  Template            $template
     * @return self
     */
    public function setUp(ThemeInterface $theme, TemplateManager $templateManager, PageBlocksInterface $pageBlocks, Template $template = null)
    {
        $this->templateManager = $templateManager;
        $this->pageBlocks = $pageBlocks;
        $this->theme = $theme;

        $this->dispatch(PageTreeEvent\PageTreeEvents::BEFORE_PAGE_TREE_SETUP, new PageTreeEvent\BeforePageTreeSetupEvent($this));

        $language = $this->getLanguage();
        $page = $this->getPage();
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
            return $this;
        }

        $this->assetsManager
            ->withExtraAssets($this->cmsMode)
            ->setUp($this->template, $options)
        ;

        $this->templateManager
             ->refresh($this->theme->getThemeSlots(), $this->template, $this->pageBlocks);

        $this->setUpMetaTags($this->getSeo());

        $this->dispatch(PageTreeEvent\PageTreeEvents::AFTER_PAGE_TREE_SETUP, new PageTreeEvent\AfterPageTreeSetupEvent($this));

        return $this;
    }

    /**
     * Sets up the metatags section
     */
    protected function setUpMetaTags()
    {
        $seo = $this->getSeo();

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
}
