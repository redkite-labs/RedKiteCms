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
use RedKiteCms\Exception\General\RuntimeException;
use RedKiteCms\Plugin\Plugin;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Finder\Finder;

/**
 * Class BaseTheme is the object deputed to define the base methods to handle a theme
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Theme
 */
abstract class BaseTheme
{
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    protected $configurationHandler;
    /**
     * @type \RedKiteCms\Content\SlotsManager\SlotsManagerFactoryInterface
     */
    protected $slotsManagerFactory;
    /**
     * @type \RedKiteCms\Plugin\Plugin
     */
    protected $theme;
    /**
     * @type string
     */
    protected $themeDir;
    /**
     * @type string
     */
    protected $templatesDir;
    /**
     * @type string
     */
    protected $slotsDir;
    /**
     * @type array
     */
    protected $templateSlots;
    /**
     * @type bool
     */
    protected $booted = false;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     * @param \RedKiteCms\Content\SlotsManager\SlotsManagerFactoryInterface $slotsManagerFactory
     */
    public function __construct(ConfigurationHandler $configurationHandler, SlotsManagerFactoryInterface $slotsManagerFactory)
    {
        $this->configurationHandler = $configurationHandler;
        $this->slotsManagerFactory = $slotsManagerFactory;
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
        $this->themeDir = $pluginDir . '/Resources/theme';
        $this->slotsDir = $this->themeDir . '/slots';
        $this->templatesDir = $pluginDir . '/Resources/views';
        if (!is_dir($this->themeDir)) {
            mkdir($this->themeDir);
        }

        if (!is_dir($this->slotsDir)) {
            mkdir($this->slotsDir);
        }
        $this->booted = true;

        return $this;
    }

    /**
     * Returns the theme's templates
     *
     * @return array
     */
    public function templates()
    {
        $this->isBooted();
        $templates = $this->findTemplates(0);

        return array_keys($templates);

    }

    /**
     * Checks is the theme is booted
     *
     * @throws \RedKiteCms\Exception\General\RuntimeException
     */
    protected function isBooted()
    {
        if (!$this->booted) {
            throw new RuntimeException(
                '"ThemeSlotsManager" object has not been booted: please run the "boot" method to fix this issue'
            );
        }
    }

    /**
     * writes the theme definition
     */
    protected function writeTheme()
    {
        $this->findSlotsInTemplates();
        $templates = $this->findTemplates(0);
        $templateSlots = array_intersect_key($this->templateSlots, $templates);

        $themeDefinition = array(
            "home_template" => $this->configurationHandler->homepageTemplate(),
            "templates" => $templateSlots,
        );

        FilesystemTools::writeFile($this->themeDir . '/theme.json', json_encode($themeDefinition));
    }

    /**
     * Finds the theme's templates
     * @param null|int $depth When null parses all subfolders
     *
     * @return array
     */
    protected function findTemplates($depth = null)
    {
        $templates = array();
        $finder = new Finder();
        if (null !== $depth) {
            $finder->depth($depth);
        }
        $files = $finder->files()->in($this->templatesDir);
        foreach ($files as $file) {
            $file = (string)$file;
            $templateName = basename($file, '.html.twig');
            $templates[$templateName] = $file;
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
        $slots = array();
        $templates = $this->findTemplates();
        foreach ($templates as $templateName => $templateFile) {
            $templateContents = FilesystemTools::readFile($templateFile);
            $slotsFound = $this->findSlots($templateName, $templateContents);
            $slots = array_merge_recursive($slots, $slotsFound);
        }

        return $slots;
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
            '/\{# repeat: (site|language)[^\}]+[^\{]+\{\{[\s]+?slots.([^|]+)?/is',
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
        $this->templateSlots[$templateName] = $pageSlots;
        if (empty($pageSlots)) {
            return $slots;
        }

        $slots["page"] = $pageSlots;

        return $slots;
    }
} 