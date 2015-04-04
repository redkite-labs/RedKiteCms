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
use RedKiteCms\Content\PageCollection\PagesCollectionParser;
use RedKiteCms\Content\SlotsManager\SlotsManagerFactoryInterface;
use RedKiteCms\FilesystemEntity\Page;
use RedKiteCms\Tools\FilesystemTools;
use RedKiteCms\Tools\Utils;
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
    /**
     * @type array
     */
    private $slots = array();

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
     * Aligns the site slots according with the changes made with the theme in use
     *
     * @param \RedKiteCms\Content\PageCollection\PagesCollectionParser $pagesCollectionParser
     */
    public function align(PagesCollectionParser $pagesCollectionParser)
    {
        if ($this->configurationHandler->isTheme()) {
            $this->alignThemeSite($pagesCollectionParser);
            $this->createSlots();

            return;
        }

        $this->alignSite($pagesCollectionParser);
        $this->createSlots();
    }

    /**
     * Saves the theme
     *
     * @param array $pages
     */
    public function save(Page $pagee, array $pages)
    {
        $this->writeTheme();
        $this->saveBlocks($pagee, $pages);
    }

    /**
     * Creates the slots into the website used to define the theme blocks contents
     */
    private function createSlots()
    {
        $this->isBooted();

        $templates = $this->findTemplates();
        $templates = array_merge(array_keys($templates["base"]), array_keys($templates["template"]));
        foreach($templates as $template) {
            $finder = new Finder();
            $files = $finder->files()->depth(0)->in($this->themeDir . '/' . $template);
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
        }
    }

    private function parseSlots($template, array $slots, $repeat)
    {
        foreach ($slots as $slotName) {
            $fileName = sprintf('%s/%s/%s.json', $this->themeDir, $template, $slotName);
            $value = array(
                "blocks" => array(),
            );

            if (file_exists($fileName)) {
                $slot = json_decode(FilesystemTools::readFile($fileName), true);
                if ($slot["repeat"] == $repeat) {
                    continue;
                }

                $value["blocks"] = $slot["blocks"];
            }

            $value["repeat"] = $repeat;

            FilesystemTools::writeFile($fileName, json_encode($value));
        }
    }

    /**
     * Synchronizes the base slots to define the default theme's blocks contents
     *
     * @return $this
     */
    private function synchronizeThemeSlots()
    {
        $templateSlots = $this->findSlotsInTemplates();
        $this->writeTemplatesFolder($templateSlots["base"]);
        $this->writeTemplatesFolder($templateSlots["templates"]);

        $removedSlots = array();
        $changedSlotsFile = $this->themeDir . '/theme.json';
        $themeInformation = json_decode(FilesystemTools::readFile($changedSlotsFile), true);
        $changedTemplateSlots = Utils::arrayRecursiveDiff($themeInformation["slots_dev"], $templateSlots);
        foreach($changedTemplateSlots as $type => $templateSlots) {
            foreach($templateSlots as $template => $slots) {
                foreach($slots as $slotNames) {
                    foreach($slotNames as $slotName) {
                        $removedSlots[] = sprintf('%s/%s/%s.json', $this->themeDir, $template, $slotName);
                    }
                }
            }
        }

        $fs = new Filesystem();
        $fs->remove($removedSlots);

        return $this;
    }

    private function writeTemplatesFolder(array $templates)
    {
        foreach ($templates as $template => $templateSlots) {
            if ( ! is_dir($this->themeDir . '/' . $template)) {
                mkdir($this->themeDir . '/' . $template);
            }
            foreach ($templateSlots as $repeat => $slots) {
                $this->parseSlots($template, $slots, $repeat);
            }
        }
    }

    private function saveBlocks(Page $pagee, array $pages)
    {
        $this->isBooted();
        foreach ($pages as $page) {
            $tokens = explode("_", $page["seo"][0]["language"]);
            $pageOptions = array(
                'page' => $page["name"],
                'language' => $tokens[0],
                'country' => $tokens[1],
            );
            $pagee->render($this->configurationHandler->siteDir(), $pageOptions);
            $this->saveTemplateSlots($pagee->getPageSlots(), $page["template"]);
        }

        $this->saveTemplateSlots($pagee->getCommonSlots(), 'base');
    }

    private function saveTemplateSlots(array $slots, $templateName)
    {
        foreach($slots as $slot) {
            $slotName = $slot->getSlotName();
            $templateSlotFile = sprintf('%s/%s/%s.json', $this->themeDir, $templateName, $slotName);
            if (!file_exists($templateSlotFile)) {
                continue;
            }
            $templateSlotContent = json_decode(file_get_contents($templateSlotFile), true);

            $blocks = array();
            foreach($slot->getProductionEntities() as $block) {
                $blocks[] = json_decode($block, true);
            }
            $templateSlotContent["blocks"] = $blocks;

            FilesystemTools::writeFile($templateSlotFile, json_encode($templateSlotContent));
        }
    }

    private function alignSite(PagesCollectionParser $pagesCollectionParser)
    {
        $changedSlotsFile = $this->themeDir . '/theme.json';
        $themeInformation = json_decode(FilesystemTools::readFile($changedSlotsFile), true);
        if (!array_key_exists("slots", $themeInformation)) {
            return null;
        }
        $changedSlots = $themeInformation["slots"];

        $siteInformation = $this->configurationHandler->siteInfo();
        if (!array_key_exists("slots", $siteInformation)) {
            $siteInformation["slots"] = $changedSlots;
            FilesystemTools::writeFile($this->configurationHandler->siteDir() . '/site.json', json_encode($siteInformation));

            return;
        }

        $currentSlots = $siteInformation["slots"];
        $this->doAlign($currentSlots, $changedSlots, $pagesCollectionParser);

        $siteInformation["slots"] = $changedSlots;
        FilesystemTools::writeFile($this->configurationHandler->siteDir() . '/site.json', json_encode($siteInformation));
    }

    private function alignThemeSite(PagesCollectionParser $pagesCollectionParser)
    {
        $changedSlotsFile = $this->themeDir . '/theme.json';
        $changedSlots =$this->findSlotsInTemplates();
        $themeInformation = json_decode(FilesystemTools::readFile($changedSlotsFile), true);
        if (!array_key_exists("slots_dev", $themeInformation)) {
            $themeInformation["slots_dev"] = $changedSlots;
            if (array_key_exists("slots", $themeInformation)){
                $themeInformation["slots_dev"] = $themeInformation["slots"];
            }
            FilesystemTools::writeFile($this->themeDir . '/theme.json', json_encode($themeInformation));
        }
        $currentSlots = $themeInformation["slots_dev"];

        $this->synchronizeThemeSlots();
        $this->doAlign($currentSlots, $changedSlots, $pagesCollectionParser);

        $themeInformation["slots_dev"] = $changedSlots;
        FilesystemTools::writeFile($this->themeDir . '/theme.json', json_encode($themeInformation));
    }

    private function doAlign($currentSlots, $changedSlots, PagesCollectionParser $pagesCollectionParser = null)
    {
        $differences = Utils::arrayRecursiveDiff($currentSlots, $changedSlots);
        if (empty($differences)) {
            return;
        }

        $this->alignStatus($differences, $pagesCollectionParser);
        $this->createSlots();
    }

    private function alignStatus(array $slots, PagesCollectionParser $pagesCollectionParser = null)
    {
        $fileSystem = new Filesystem();
        $baseDir = $this->configurationHandler->siteDir();
        foreach ($slots as $type => $templates) {
            foreach ($templates as $template => $templateSlots) {
                foreach ($templateSlots as $repeat => $repeatedSlots) {
                    $options = array(
                        "page" => "",
                        "language" => "",
                        "country" => "",
                        "slot" => "",
                    );
                    foreach ($repeatedSlots as $slot) {
                        $dirs = array();
                        $options["slot"] = $slot;
                        switch ($repeat) {
                            case "page":
                                $pages = $pagesCollectionParser->pages();
                                $languages = $this->configurationHandler->languages();
                                foreach ($pages as $page) {
                                    foreach ($languages as $language) {
                                        $tokens = explode("_", $language);
                                        $dir = FilesystemTools::slotDir(
                                            $baseDir,
                                            array(
                                                "page" => $page["name"],
                                                "language" => $tokens[0],
                                                "country" => $tokens[1],
                                                "slot" => $slot,
                                            )
                                        );
                                        if (!is_dir($dir)) {
                                            continue;
                                        }
                                        $dirs[] = $dir;
                                    }
                                }

                                break;
                            default:
                                $dirs = array(FilesystemTools::slotDir($baseDir, $options));

                                break;
                        }
                        $fileSystem->remove($dirs);
                    }
                }
            }
        }
    }
} 