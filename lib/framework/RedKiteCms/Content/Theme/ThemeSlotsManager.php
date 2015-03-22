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
use RedKiteCms\Content\BlockManager\BlockManager;
use RedKiteCms\Content\SlotsManager\SlotsManagerFactoryInterface;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class ThemeSlotsManager is the object deputed to manage the theme slots
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Theme
 */
class ThemeSlotsManager extends BaseTheme
{
    private $blockManager;
    private $slots = array();

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     * @param \RedKiteCms\Content\SlotsManager\SlotsManagerFactoryInterface $slotsManagerFactory
     * @param \RedKiteCms\Content\BlockManager\BlockManager $blockManager
     */
    public function __construct(ConfigurationHandler $configurationHandler, SlotsManagerFactoryInterface $slotsManagerFactory, BlockManager $blockManager )
    {
        parent::__construct($configurationHandler, $slotsManagerFactory);

        $this->blockManager = $blockManager;
    }

    /**
     * Returns the found slots
     *
     * @return array
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * Creates the slots into the website used to define the theme blocks contents
     *
     * @return $this
     */
    public function createSlots()
    {
        $this->isBooted();

        $finder = new Finder();
        $files = $finder->files()->depth(0)->in($this->slotsDir);
        foreach ($files as $file) {
            $file = (string)$file;
            $slotName = basename($file, '.json');
            $json = FilesystemTools::readFile($file);
            $slot = json_decode($json, true);

            $blocks = array();
            if (array_key_exists("blocks", $slot)) {
                $blocks = $slot["blocks"];
            }

            $slotManager = $this->slotsManagerFactory->createSlotManager($slot["repeat"]);
            $slotManager->addSlot($slotName, $blocks);
        }

        return $this;
    }

    /**
     * Synchronizes the base slots to define the default theme's blocks contents
     *
     * @return $this
     */
    public function synchronizeThemeSlots()
    {
        $foundSlots = array();
        $changedSlots = array();
        $templateSlots = $this->findSlotsInTemplates();
        foreach ($templateSlots as $repeat => $slots) {
            $changedSlots = array_merge($changedSlots, $this->parseSlots($slots, $repeat));
            $foundSlots = array_merge($foundSlots, $slots);
        }

        /* TODO: Save the changed_slots.json, add revision and update the site according with that revision
        $savedChangedSlots = array();
        $changedSlotsFile = $this->themeDir . '/changed_slots.json';
        if (file_exists($changedSlotsFile)) {
            $savedChangedSlots = json_decode(FilesystemTools::readFile($changedSlotsFile), true);
        }

        FilesystemTools::writeFile($this->themeDir . '/changed_slots.json', json_encode($changedSlots));

        */
        $removedSlots = array();
        $finder = new Finder();
        $files = $finder->files()->in($this->slotsDir);
        foreach ($files as $file) {
            $file = (string)$file;
            $fileName = basename($file, '.json');
            if (!in_array($fileName, $foundSlots)) {
                $removedSlots[] = $file;
            }
        }
        $fs = new Filesystem();
        $fs->remove($removedSlots);

        return $this;
    }

    /**
     * Saves the theme
     *
     * @param array $pages
     */
    public function save(array $pages)
    {
        $this->writeTheme();
        $this->saveBlocks($pages);
    }

    private function parseSlots(array $slots, $repeat)
    {
        $changedSlots = array();
        foreach ($slots as $slotName) {
            $fileName = sprintf('%s/%s.json', $this->slotsDir, $slotName);
            if (file_exists($fileName)) {
                $slot = json_decode(FilesystemTools::readFile($fileName), true);
                if ($slot["repeat"] == $repeat) {
                    continue;
                }

                $slotName = basename($fileName, '.json');
                $changedSlots[$slotName] = array(
                    "old" => $slot["repeat"],
                    "new" => $repeat,
                );
            }

            $value = array(
                "repeat" => $repeat,
                "blocks" => array(),
            );
            FilesystemTools::writeFile($fileName, json_encode($value));
        }

        return $changedSlots;
    }

    private function saveBlocks(array $pages)
    {
        $this->isBooted();

        $themeFile = $this->themeDir . '/theme.json';
        if (!file_exists($themeFile)) {
            $templates = array();
            $themeTemplates = $this->findTemplates(0);
            foreach ($themeTemplates as $templateName => $templateFile) {
                $templateContents = FilesystemTools::readFile($templateFile);
                $slotsFound = $this->findSlots($templateName, $templateContents);

                $pageSlots = array();
                if (array_key_exists("page", $slotsFound)) {
                    $pageSlots = $slotsFound["page"];
                }

                $templates[$templateName] = array(
                    'page' => "homepage",
                    'slots' => $pageSlots,
                );
            }
        } else {
            $slotsForTemplates = json_decode(FilesystemTools::readFile($this->themeDir . '/theme.json'), true);
            $templates = array();
            foreach ($pages as $page) {
                $template = $page["template"];
                if (!array_key_exists($template, $templates)) {
                    $templates[$template] = array(
                        'page' => $page["name"],
                        'slots' => (array_key_exists(
                            $template,
                            $slotsForTemplates["templates"]
                        )) ? $slotsForTemplates["templates"][$template] : array(),
                    );
                }
            }
        }

        $slots = array();
        $templateSlots = $this->findSlotsInTemplates();
        if (array_key_exists("language", $templateSlots)) {
            $slots = array_merge($slots, $templateSlots["language"]);
        }

        if (array_key_exists("site", $templateSlots)) {
            $slots = array_merge($slots, $templateSlots["site"]);
        }

        $templates["common"] = array(
            "page" => "homepage",
            "slots" => $slots,
        );

        foreach ($templates as $templateDefinition) {
            $page = $templateDefinition["page"];
            $this->writeBlocks($page, $templateDefinition["slots"]);
        }
    }

    private function writeBlocks($page, $slots)
    {
        $siteDir = $this->configurationHandler->siteDir();
        $options = array(
            "page" => $page,
            "language" => $this->configurationHandler->language(),
            "country" => $this->configurationHandler->country(),
        );
        foreach ($slots as $slot) {
            $options["slot"] = $slot;
            $slotDir = FilesystemTools::slotDir($siteDir, $options);
            if (null === $slotDir) {
                continue;
            }

            $blocksDir = $slotDir . '/active/blocks';

            $blocks = array();
            $finder = new Finder();
            $files = $finder->files()->in($blocksDir);
            foreach ($files as $file) {
                $file = (string)$file;
                $blocks[] = json_decode(FilesystemTools::readFile($file), true);
            }

            $slotFile = $this->slotsDir . '/' . $slot . '.json';
            $slotFileContents = FilesystemTools::readFile($slotFile);
            $slotContents = json_decode($slotFileContents, true);
            $slotContents["blocks"] = $blocks;
            FilesystemTools::writeFile($slotFile, json_encode($slotContents));
        }
    }
} 