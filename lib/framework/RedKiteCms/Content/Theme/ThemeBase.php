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
use RedKiteCms\Plugin\Plugin;
use RedKiteCms\Tools\FilesystemTools;
use RedKiteCms\Tools\Utils;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class ThemeBase is the object deputed to handle Theme base methods and properties
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Theme
 */
abstract class ThemeBase
{
    /**
     * @type \RedKiteCms\Plugin\Plugin
     */
    protected $theme;
    /**
     * @type string
     */
    protected $baseThemeDir;
    /**
     * @type string
     */
    protected $themeDir;
    /**
     * @type string
     */
    protected $templatesDir;
    /**
     * @type array
     */
    //protected $templateSlots;
    /**
     * @type bool
     */
    protected $booted = false;

    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    protected $configurationHandler;
    /**
     * @type array
     */
    protected $templates;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configurationHandler = $configurationHandler;
    }

    /**
     * Boots the theme
     * @param \RedKiteCms\Plugin\Plugin $theme
     *
     * @return $this
     */
    public function boot(Plugin $theme)
    {
        if ($this->booted && $this->theme == $theme) {
            return $this;
        }

        $this->theme = $theme;
        $pluginDir = $this->theme->getPluginDir();
        $this->baseThemeDir = $this->themeDir = $pluginDir . '/Resources/theme';
        if ($this->configurationHandler->isTheme()) {
            $this->themeDir .= '_dev';
        }
        $this->templatesDir = $pluginDir . '/Resources/views';
        if (!is_dir($this->themeDir)) {
            mkdir($this->themeDir);
        }

        $this->templates = $this->findTemplates();
        $this->booted = true;

        return $this;
    }

    public function templates()
    {
        return array_keys($this->templates["template"]);
    }

    /**
     * Finds the theme's templates
     * @param null|int $depth When null parses all subfolders
     *
     * @return array
     */
    private function findTemplates()
    {
        $templates = array(
            "base" => array(),
            "template" => array(),
        );
        $finder = new Finder();
        $files = $finder->files()->in($this->templatesDir);
        foreach ($files as $file) {
            $file = (string)$file;
            $templateName = basename($file, '.html.twig');

            $key = 'template';
            if (str_replace($this->templatesDir . '/', '', $file) != $templateName . '.html.twig') {
                $key = 'base';
            }
            $templates[$key][$templateName] = $file;
        }

        return $templates;
    }

    /**
     * Find the slots parsing the theme's templates
     *
     * @return array
     */
    protected function findSlotsInTemplates()
    {
        $templates = $this->findTemplates();
        $slots = array();
        foreach ($templates["base"] as $templateName => $templateFile) {
            $templateContents = FilesystemTools::readFile($templateFile);
            $slots = array_merge_recursive($slots, $this->findSlots($templateName, $templateContents));
        }
        $baseSlots["base"] = $slots;

        $slots = array();
        foreach ($templates["template"] as $templateName => $templateFile) {
            $templateContents = FilesystemTools::readFile($templateFile);
            $slots[$templateName] = $this->findSlots($templateName, $templateContents);
        }

        return array(
            'base' => $baseSlots,
            'templates' => $slots,
        );
    }

    /**
     * Parses the given template to find slots
     * @param string $templateName
     * @param string $templateContents
     *
     * @return array
     */
    protected function findSlots($templateName, $templateContents)
    {
        // find repeated slots
        preg_match_all(
            '/\{#[\s]?repeat:[\s]?(site|language)[^\}]+[^\{]+\{\{[\s]+?slots.([^|]+)?/is',
            $templateContents,
            $matches,
            PREG_SET_ORDER
        );
        $slots = array();
        $rawSlots = array();
        foreach ($matches as $slot) {
            $repeat = $slot[1];
            $slotName = $slot[2];
            $slots[$repeat][] = $slotName;
            $rawSlots[] = $slotName;
        }

        // find all slots
        preg_match_all('/\{\{[\s]+?slots.([^|]+)?/is', $templateContents, $matches);
        $pageSlots = array_diff($matches[1], $rawSlots);
        //$this->templateSlots[$templateName] = $pageSlots;
        if (empty($pageSlots)) {
            return $slots;
        }

        $slots["page"] = $pageSlots;

        return $slots;
    }

    protected function mergeSlots($templateSlots)
    {
        $allSlots = array();
        foreach($templateSlots as $slot) {
            $allSlots = array_merge($allSlots, $slot);
        }

        return $allSlots;
    }
} 