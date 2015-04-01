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

            return;
        }

        $this->alignSite($pagesCollectionParser);
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
     *
     * @return $this
     */
    public function createSlots()
    {
        return;
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

    private function parseSlots($template, array $slots, $repeat)
    {
        $changedSlots = array();
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

                $slotName = basename($fileName, '.json');
                $changedSlots[$slotName] = array(
                    "old" => $slot["repeat"],
                    "new" => $repeat,
                );
                $value["blocks"] = $slot["blocks"];
            }

            $value["repeat"] = $repeat;

            FilesystemTools::writeFile($fileName, json_encode($value));
        }

        return $changedSlots;
    }

    /**
     * Synchronizes the base slots to define the default theme's blocks contents
     *
     * @return $this
     */
    private function synchronizeThemeSlots()
    {
        //$foundSlots = array();
        //$changedSlots = array();
        $templateSlots = $this->findSlotsInTemplates();

        $this->writeTemplatesFolder($templateSlots["base"]);
        $this->writeTemplatesFolder($templateSlots["templates"]);

        /* FIXME
        $foundSlots = $this->writeTemplatesFolder($templateSlots["base"]);
        $foundSlots = array_merge($foundSlots, $this->writeTemplatesFolder($templateSlots["templates"]));


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
        $fs->remove($removedSlots);*/

        return $this;
    }

    private function writeTemplatesFolder(array $templates)
    {
        //$foundSlots = array();
        foreach ($templates as $template => $templateSlots) {
            if ( ! is_dir($this->themeDir . '/' . $template)) {
                mkdir($this->themeDir . '/' . $template);
            }
            foreach ($templateSlots as $repeat => $slots) {
                $this->parseSlots($template, $slots, $repeat);
                //$foundSlots = array_merge($foundSlots, $slots);
            }
        }

        //return $foundSlots;
    }

    private function saveBlocks(Page $pagee, array $pages)
    {
        $this->isBooted();

        //$themeTemplates = $this->findTemplates();
        foreach ($pages as $page) {
//if ($page["name"] != 'homepage') continue;
            $tokens = explode("_", $page["seo"][0]["language"]);
            $pageOptions = array(
                'page' => $page["name"],
                'language' => $tokens[0],
                'country' => $tokens[1],
            );
            $pagee->render($this->configurationHandler->siteDir(), $pageOptions);
            foreach($pagee->getPageSlots() as $slot) {
                $slotName = $slot->getSlotName();
                $templateSlotFile = sprintf('%s/%s/%s.json', $this->themeDir, $page["template"], $slotName);
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
            //exit;

                /*
            $pagePath = sprintf('%s/%s/%s', $this->configurationHandler->pagesDir(), $page["name"], $page["seo"][0]["language"]);
            $template = $page["template"];
            $finder = new Finder();
            $slots = $finder->directories()->depth(0)->in($pagePath);
            foreach ($slots as $slot) {
                $slotName = basename($slot);

            }
            exit;
            $templates[$template] = array(
                'page' => $page["name"],/*
                'slots' => (array_key_exists(
                    $template,
                    $slotsForTemplates["templates"]
                )) ? $slotsForTemplates["templates"][$template] : array(),*
            );*/
        }
        exit;

        /*
        $themeFile = $this->themeDir . '/theme.json';
        if (!file_exists($themeFile)) {
            $templates = array();
            $themeTemplates = $this->findTemplates();
            foreach ($themeTemplates["templates"] as $templateName => $templateFile) {
                $templateContents = FilesystemTools::readFile($templateFile);
                $slotsFound = $this->findSlots($templateName, $templateContents);
print_R($slotsFound);Exit;
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
            print_r($slotsForTemplates);exit;
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
        }//exit;

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
        }*/
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
        $themeInformation = json_decode(FilesystemTools::readFile($changedSlotsFile), true);//print_R($themeInformation);
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
        // FIXME
        return;

        $differences = $this->findDifferences($currentSlots, $changedSlots);
        if (null === $differences) {
            return;
        }

        $this->alignOldStatus($differences["old"], $pagesCollectionParser);
        $this->createSlots();
    }

    private function findDifferences($currentSlots, $changedSlots)
    {
        $oldStatus = array_diff_key($currentSlots, $changedSlots);
        $newStatus = array();
        foreach ($changedSlots as $repeat => $s) {
            if (!array_key_exists($repeat, $currentSlots)) {
                continue;
            }
            $savedSlots = $currentSlots[$repeat];
            $oldDiff = array_diff($savedSlots, $s);
            if (!empty($oldDiff)) {
                $oldStatus[$repeat] = $oldDiff;
            }

            $newDiff = array_diff($s, $savedSlots);
            if (!empty($newDiff)) {
                $newStatus[$repeat] = $newDiff;
            }
        }

        return array(
            "old" => $oldStatus,
            "new" => $newStatus,
        );
    }

    private function alignOldStatus(array $slots, PagesCollectionParser $pagesCollectionParser = null)
    {
        $fileSystem = new Filesystem();
        $baseDir = $this->configurationHandler->siteDir();
        foreach($slots as $repeat => $repeatedSlots) {
            $options = array(
                "page" => "",
                "language" => "",
                "country" => "",
                "slot" => "",
            );
            foreach($repeatedSlots as $slot) {
                $dirs = array();
                $options["slot"] = $slot;
                switch ($repeat) {
                    case "page":
                        $pages = $pagesCollectionParser->pages();
                        $languages = $this->configurationHandler->languages();
                        foreach($pages as $page) {
                            foreach($languages as $language) {
                                $tokens = explode("_", $language);
                                $dirs[] = FilesystemTools::slotDir(
                                    $baseDir,
                                    array(
                                        "page" => $page["name"],
                                        "language" => $tokens[0],
                                        "country" => $tokens[1],
                                        "slot" => $slot,
                                    )
                                );
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