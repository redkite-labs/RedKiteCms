<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Content\Theme;

use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Content\SlotsManager\SlotsManagerFactoryInterface;
use RedKiteCms\Plugin\Plugin;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Finder\Finder;

/**
 * Class Theme is the object deputed to handle a theme
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Theme
 */
class Theme extends ThemeBase
{
    /**
     * @type array
     */
    private $templateBlocks;
    /**
     * @type string
     */
    private $themeDefinition;
    /**
     * @type string
     */
    private $homepageTemplate;
    /**
     * @var \RedKiteCms\Plugin\Plugin
     */
    private $plugin;
    /**
     * @type \RedKiteCms\Content\SlotsManager\SlotsManagerFactoryInterface
     */
    private $slotsManagerFactory;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     * @param \RedKiteCms\Content\SlotsManager\SlotsManagerFactoryInterface $slotsManagerFactory
     */
    public function __construct(ConfigurationHandler $configurationHandler, SlotsManagerFactoryInterface $slotsManagerFactory)
    {
        parent::__construct($configurationHandler);

        $this->slotsManagerFactory = $slotsManagerFactory;
    }

    /**
     * @return \RedKiteCms\Plugin\Plugin
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * Boots the theme from the given theme plugin
     *
     * @param \RedKiteCms\Plugin\Plugin $theme
     *
     * @return $this
     */
    public function boot(Plugin $theme)
    {
        $this->plugin = $theme;

        parent::boot($theme);

        $this->initTemplates();
        $this->initHomepageTemplate();

        return $this;
    }

    /**
     * Returns the template used by the theme homepage
     *
     * @return string
     */
    public function homepageTemplate()
    {
        return $this->homepageTemplate;
    }

    /**
     * Adds the default theme slots to the page which uses the given template
     *
     * @param string $templateName
     * @param string $username
     */
    public function addTemplateSlots($templateName, $username)
    {
        if (!array_key_exists($templateName, $this->templateBlocks)) {
            return null;
        }

        $blocks = $this->templateBlocks[$templateName];
        $this->addSlots($blocks, $username);
    }

    private function initTemplates()
    {
        $templateNames = array_keys($this->templates["template"]);
        foreach ($templateNames as $templateName) {
            $blocks = array();
            $templateFileName = $this->themeDir . '/' . $templateName;
            if (!is_dir($templateFileName)) {
                $this->templateBlocks[$templateName] = $blocks;

                continue;
            }
            $finder = new Finder();
            $slotFiles = $finder->files()->in($this->themeDir . '/' . $templateName);
            foreach ($slotFiles as $slotFile) {
                $slotName = basename($slotFile, '.json');
                $slot = json_decode(FilesystemTools::readFile((string)$slotFile), true);
                $blocks[$slotName] = $slot["blocks"];
            }

            $this->templateBlocks[$templateName] = $blocks;
        }
    }

    private function initHomepageTemplate()
    {
        $this->homepageTemplate = $this->configurationHandler->homepageTemplate();
        if (null === $this->homepageTemplate) {
            $this->homepageTemplate = $this->themeDefinition["home_template"];
        }
    }

    private function addSlots(array $blocks, $username)
    {
        foreach ($blocks as $slotName => $slotBlocks) {
            $slotManager = $this->slotsManagerFactory->createSlotManager('page');
            $slotManager->addSlot($slotName, $slotBlocks, $username);
        }
    }
}