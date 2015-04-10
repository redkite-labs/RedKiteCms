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
use RedKiteCms\FilesystemEntity\Page;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Finder\Finder;

/**
 * Class ThemeSlotsGenerator is the object deputed to update the Theme's blocks from the web site who handles the Theme itself
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Theme
 */
class ThemeSlotsGenerator extends ThemeBase
{
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
     * Synchronizes the site slots with the theme slots
     *
     * @param \RedKiteCms\FilesystemEntity\Page $page
     * @param array $pages
     */
    public function synchronize(Page $page, array $pages)
    {
        if (!$this->configurationHandler->isTheme()) {
            return;
        }

        foreach ($pages as $pageValues) {
            $tokens = explode("_", $pageValues["seo"][0]["language"]);
            $pageOptions = array(
                'page' => $pageValues["name"],
                'language' => $tokens[0],
                'country' => $tokens[1],
            );
            $page->render($this->configurationHandler->siteDir(), $pageOptions);
            $this->saveTemplateSlots($page->getPageSlots(), $pageValues["template"]);
        }

        $this->saveTemplateSlots($page->getCommonSlots(), 'base');
    }

    /**
     * Generates the slots from the web site that handles the theme into the Theme slots
     */
    public function generate()
    {
        $templates = array_merge(array_keys($this->templates["base"]), array_keys($this->templates["template"]));
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
} 