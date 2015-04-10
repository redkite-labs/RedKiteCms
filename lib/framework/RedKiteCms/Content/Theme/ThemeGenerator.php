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

use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class ThemeGenerator is the object deputed to generate the theme definition and slots folders for the given Theme
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Theme
 */
class ThemeGenerator extends ThemeBase
{
    /**
     * Generates the theme definition and slots
     */
    public function generate()
    {
        if (!$this->configurationHandler->isTheme() || $this->theme->getName() != $this->configurationHandler->handledTheme()) {
            return;
        }

        $templates = array_keys($this->templates["template"]);
        $homepage = json_decode(file_get_contents($this->configurationHandler->pagesDir() . '/' .$this->configurationHandler->homepage() . '/page.json'), true);
        $homepageTemplate = $homepage["template"];
        if (!in_array($homepageTemplate, $templates)) {
            $homepageTemplate = $templates[0];
        }

        $themeDefinition = array(
            "home_template" => $homepageTemplate,
            "templates" => $templates,
        );

        $this->synchronizeThemeSlots();
        FilesystemTools::writeFile($this->themeDir . '/theme.json', json_encode($themeDefinition));
    }

    private function writeSlots($template, array $slots, $repeat)
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

    private function synchronizeThemeSlots()
    {
        $templateSlots = $this->findSlotsInTemplates();

        $files = $this->parseForRemovedTemplates($templateSlots["templates"]);
        $files = array_merge($files, $this->parseForRemovedSlots($templateSlots["base"]));
        $files = array_merge($files, $this->parseForRemovedSlots($templateSlots["templates"]));

        if (!empty($files)) {
            $fs = new Filesystem();
            $fs->remove($files);
        }

        $this->writeTemplatesFolder($templateSlots["base"]);
        $this->writeTemplatesFolder($templateSlots["templates"]);

        return $this;
    }

    private function parseForRemovedTemplates($templates)
    {
        $templates = array_keys($templates);
        $removedTemplates = array();
        $finder = new Finder();
        $existingTemplates = $finder->files()->directories()->depth(0)->in($this->themeDir);
        foreach($existingTemplates as $template) {
            $templateName = basename($template);
            if ($templateName == 'base') {
                continue;
            }

            if (!in_array($templateName, $templates)) {
                $removedTemplates[] = (string)$template;
            }
        }

        return $removedTemplates;
    }

    private function parseForRemovedSlots($templates)
    {
        $removedSlots = array();
        $finder = new Finder();
        $templateNames = array_keys($templates);
        foreach($templateNames as $template) {
            $templateDir = $this->themeDir . '/' . $template;
            if (!is_dir($templateDir)) {
                continue;
            }

            $allSlots = $this->mergeSlots($templates[$template]);
            $slots = $finder->files()->name('*.json')->in($templateDir);
            foreach($slots as $slot) {
                $slotName = basename($slot, '.json');
                if (!in_array($slotName, $allSlots)) {
                    $removedSlots[] = (string)$slot;
                }
            }
        }

        return $removedSlots;
    }

    private function writeTemplatesFolder(array $templates)
    {
        foreach ($templates as $template => $templateSlots) {
            if ( ! is_dir($this->themeDir . '/' . $template)) {
                mkdir($this->themeDir . '/' . $template);
            }
            foreach ($templateSlots as $repeat => $slots) {
                $this->writeSlots($template, $slots, $repeat);
            }
        }
    }
} 