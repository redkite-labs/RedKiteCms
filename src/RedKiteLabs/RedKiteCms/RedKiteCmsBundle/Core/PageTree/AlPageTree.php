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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\PageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocksInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\DataManager\DataManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface;

/**
 * Defines an object which stores all the web page information as a tree
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @method \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme getTheme() Returns the handled AlTheme object
 * @method AlTemplate getTemplate() Returns the handled AlTemplate object
 * @method AlTemplateManager getTemplateManager() Returns the handled AlTemplateManager object
 * @method \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks getPageBlocks() Returns the handled AlPageBlocks object
 * @method \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlPage getAlPage() Returns the handled AlPage object
 * @method \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage getAlLanguage() Returns the handled AlLanguage object
 * @method \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlSeo getAlSeo() Returns the handled AlSeo object
 * @method AlPageTree getExternalStylesheets() Returns the handled external stylesheets
 * @method AlPageTree getInternalStylesheets() Returns the handled internal stylesheets
 * @method AlPageTree getExternalJavascripts() Returns the handled external javascripts
 * @method AlPageTree getInternalJavascripts() Returns the handled internal javascripts
 * @method string getMetaTitle() Returns the metatag Title attribute
 * @method string getMetaDescription() Returns the metatag Description attribute
 * @method string getMetaKeywords() Returns the metatag Keywords attribute
 */
class AlPageTree
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
    /** @var null|AlTemplateManager */
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
     * @param  AlThemeInterface      $theme
     * @param  AlTemplateManager     $templateManager
     * @param  AlPageBlocksInterface $pageBlocks
     * @param  AlTemplate            $template
     * @return self
     */
    public function setUp(AlThemeInterface $theme, AlTemplateManager $templateManager, AlPageBlocksInterface $pageBlocks, AlTemplate $template = null)
    {
        $this->templateManager = $templateManager;
        $this->pageBlocks = $pageBlocks;
        $this->theme = $theme;

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
            return $this;
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
}
