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
use RedKiteCms\Content\PageCollection\PagesCollectionParser;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class ThemeAligner is the object deputed to align the site that handles a theme or a site that uses a theme when the theme had changed.
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Theme
 */
class ThemeAligner extends ThemeBase
{
    /**
     * Aligns the site slots according with the changes made with the theme in use
     *
     * @param \RedKiteCms\Content\PageCollection\PagesCollectionParser $pagesCollectionParser
     */
    public function align(PagesCollectionParser $pagesCollectionParser)
    {
        $themeSlots = $this->findSlotsInTemplates();
        $slots = $this->mergeSlotsByStatus($themeSlots);
        $pageSlots = $slots["page"];
        unset($slots["page"]);

        $files = $this->removeCommonSlots($slots);
        $files = array_merge($files, $this->removePageSlots($pagesCollectionParser, $pageSlots));

        if (!empty($files)) {
            $fs = new Filesystem();
            $fs->remove($files);
        }
    }

    private function mergeSlotsByStatus($templates)
    {
        $slots = array();
        foreach ($templates as $template) {
            foreach ($template as $templateSlots) {
                $slots = array_merge_recursive($slots, $templateSlots);
            }
        }

        return $slots;
    }

    private function removeCommonSlots($slots)
    {
        $filesToRemove = array();
        $slotsDir = $this->configurationHandler->siteDir() . '/slots';
        $finder = new Finder();
        $slotFolders = $finder->directories()->depth(0)->in($slotsDir);
        foreach ($slotFolders as $slotFolder) {
            $slotFolder = (string)$slotFolder;
            $repeat = "language";
            if (is_dir($slotFolder . '/active')) {
                $repeat = "site";
            }

            $slotFolderName = basename($slotFolder);
            if (!in_array($slotFolderName, $slots[$repeat])) {
                $filesToRemove[] = $slotFolder;
            }
        }

        return $filesToRemove;
    }

    private function removePageSlots(PagesCollectionParser $pagesCollectionParser, $slots)
    {
        $filesToRemove = array();
        $pagesDir = $this->configurationHandler->pagesDir();
        $pages = $pagesCollectionParser->pages();
        $languages = $this->configurationHandler->languages();
        foreach ($pages as $page) {
            foreach ($languages as $language) {
                $pageDir = sprintf('%s/%s/%s', $pagesDir, $page["name"], $language);

                $finder = new Finder();
                $slotFolders = $finder->directories()->depth(0)->in($pageDir);
                foreach ($slotFolders as $slotFolder) {
                    $slotFolderName = basename($slotFolder);
                    if (!in_array($slotFolderName, $slots)) {
                        $filesToRemove[] = (string)$slotFolder;
                    }
                }
            }
        }

        return $filesToRemove;
    }
}