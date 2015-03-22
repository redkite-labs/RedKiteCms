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

use RedKiteCms\Plugin\Plugin;
use RedKiteCms\Tools\FilesystemTools;

/**
 * Class Theme is the object deputed to handle a theme
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Theme
 */
class Theme extends BaseTheme
{
    /**
     * @type array
     */
    private $templates;
    /**
     * @type string
     */
    private $themeDefinition;
    /**
     * @type string
     */
    private $homepageTemplate;

    /**
     * Boots the theme from the given theme plugin
     *
     * @param \RedKiteCms\Plugin\Plugin $theme
     *
     * @return $this
     */
    public function boot(Plugin $theme)
    {
        parent::boot($theme);

        $themeFile = $this->themeDir . '/theme.json';
        if (!is_file($themeFile)) {
            $this->writeTheme();
        }

        $this->findTemplatesWithBlocks();
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
        if (!array_key_exists($templateName, $this->templates)) {
            return null;
        }

        $blocks = $this->templates[$templateName];
        $this->addSlots($blocks, $username);
    }

    private function findTemplatesWithBlocks()
    {
        $this->themeDefinition = json_decode(FilesystemTools::readFile($this->themeDir . '/theme.json'), true);
        $this->homepageTemplate = $this->themeDefinition["home_template"];
        $templateSlots = $this->themeDefinition["templates"];
        if (null === $templateSlots) {
            return;
        }

        $this->templates = array();
        foreach ($templateSlots as $templateName => $slots) {
            $blocks = array();
            foreach ($slots as $slotName) {
                $slot = json_decode(FilesystemTools::readFile($this->slotsDir . '/' . $slotName . '.json'), true);
                $blocks[$slotName] = $slot["blocks"];
            }

            $this->templates[$templateName] = $blocks;
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