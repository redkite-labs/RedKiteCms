<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\ThemeChanger;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface;

/**
 * AlThemeChanger is deputated to change the website template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlThemeChanger
{
    protected $templateManager;
    protected $factoryRepository;
    protected $blocksFactory;
    protected $languagesRepository;
    protected $pagesRepository;
    protected $blockRepository;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager              $templateManager
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface    $blocksFactory
     */
    public function __construct(AlTemplateManager $templateManager, AlFactoryRepositoryInterface $factoryRepository, AlBlockManagerFactoryInterface $blocksFactory)
    {
        $this->templateManager = $templateManager;
        $this->factoryRepository = $factoryRepository;
        $this->blocksFactory = $blocksFactory;
        $this->languagesRepository = $this->factoryRepository->createRepository('Language');
        $this->pagesRepository = $this->factoryRepository->createRepository('Page');
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
    }

    /**
     * Changes the current theme
     *
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface $previousTheme
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface $theme
     * @param string                                                    $path
     * @param array                                                     $templatesMap
     */
    public function change(AlThemeInterface $previousTheme, AlThemeInterface $theme, $path, array $templatesMap)
    {
        $this->saveThemeStructure($previousTheme, $path);
        $this->backupBlocks();
        $this->changeTemplate($theme, $templatesMap);
    }

    /**
     * Changes the source slot with the target slot
     *
     * @param  string $sourceSlotName
     * @param  string $targetSlotName
     * @return string
     */
    public function changeSlot($sourceSlotName, $targetSlotName)
    {
        try {
            $sourceBlocks = $this->blockRepository->retrieveContents(null, null, $sourceSlotName, array(2, 3));
            $targetBlocks = $this->blockRepository->retrieveContents(null, null, $targetSlotName);

            $this->blockRepository->startTransaction();
            $result = $this->saveBlocks($sourceBlocks, array(
                'SlotName' => $targetSlotName,
                'ToDelete' => 0,
            ));
            if (! $result) {
                $this->blockRepository->rollback();

                return "The slot has not been changed due to an error occoured when saving to database";
            }

            if ( ! $this->saveIncludedBlocks($sourceBlocks, array(2, 3))) {
                return "The slot has not been changed due to an error occoured when saving to database";
            }

            $result = $this->saveBlocks($targetBlocks, array(
                'SlotName' => $sourceSlotName,
                'ToDelete' => 3,
            ));
            if (! $result) {
                $this->blockRepository->rollback();

                return "The slot has not been changed due to an error occoured when saving to database";
            }

            if ( ! $this->saveIncludedBlocks($targetBlocks)) {
                return "The slot has not been changed due to an error occoured when saving to database";
            }

            $this->blockRepository->commit();

            return "The slot has been changed";
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Finalizes the theme change. Finalization can be partial or full: when partial
     * only the swapped slots' contents are removed, full removes all previous slots'
     * contents
     *
     * @param  string  $action
     * @return boolean
     */
    public function finalize($action)
    {
        $value = ($action == 'full') ? array(2, 3) : 3;
        $blocks = $this->blockRepository->retrieveContents(null, null, null, $value);

        $this->blockRepository->startTransaction();
        $result = $this->saveBlocks($blocks, $values = array(
                'ToDelete' => 1,
        ));
        if (! $result) {
            $this->blockRepository->rollback();

            return false;
        }
        $this->blockRepository->commit();

        return true;
    }

    /**
     * Copies the current theme blocks and sets ToDelete field to 2
     *
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\ThemeChanger\Exception
     */
    protected function backupBlocks()
    {
        try {
            $blocks = $this->blockRepository->retrieveContents(null, null);
            $this->saveBlocks($blocks, array('ToDelete' => 2));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Changes the website templates with the new ones provided into the $templatesMap
     * array
     *
     * @param  \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface $theme
     * @param  array                                                     $templatesMap
     * @throws \Exception
     */
    protected function changeTemplate(AlThemeInterface $theme, array $templatesMap)
    {
        try {
            $ignoreRepeatedSlots = false;
            foreach ($this->languagesRepository->activeLanguages() as $language) {
                foreach ($this->pagesRepository->activePages() as $page) {
                    $templateName = $page->getTemplateName();
                    if ( ! array_key_exists($templateName, $templatesMap)) {
                        continue;
                    }

                    $page->setTemplateName($templatesMap[$templateName]);
                    $page->save();

                    $template = $theme->getTemplate($page->getTemplateName());
                    $this->templateManager
                        ->setTemplate($template)
                        ->refresh();

                    $this->templateManager->populate($language->getId(), $page->getId(), $ignoreRepeatedSlots);
                    $ignoreRepeatedSlots = true;
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Saves the current theme structure into a file
     *
     * @param  \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface $theme
     * @param  type                                                      $themeStructureFile
     * @throws \Exception
     */
    protected function saveThemeStructure(AlThemeInterface $theme, $themeStructureFile)
    {
        try {
            $templates = array();
            foreach ($this->languagesRepository->activeLanguages() as $language) {
                foreach ($this->pagesRepository->activePages() as $page) {
                    $key = $language->getId() . '-' . $page->getId();
                    $templates[$key] = $page->getTemplateName();
                }
            }

            $themeName = $theme->getThemeName();
            $currentTheme = array(
                "Theme" => $themeName,
                "Templates" => $templates,
            );

            file_put_contents($themeStructureFile, json_encode($currentTheme));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function saveBlocks($blocks, $values)
    {
        $result = true;

        $this->blockRepository->startTransaction();
        foreach ($blocks as $block) {
            $blockManager = $this->blocksFactory->createBlockManager($block);
            if (null === $blockManager) {
               continue;
            }

            $result = $blockManager
                ->set($block)
                ->save($values)
            ;

            if (! $result) {
                break;
            }
        }

        if ($result) {
            $this->blockRepository->commit();
        } else {
            $this->blockRepository->rollback();
        }

        return $result;
    }

    private function saveIncludedBlocks($blocks, $toDelete = 0)
    {
        $result = true;

        foreach ($blocks as $block) {
            $includedBlocks = $this->blockRepository->retrieveContentsBySlotName('%' . $block->getId() . '%', $toDelete);
            if (empty($includedBlocks)) {
                continue;
            }

            $result = $this->saveBlocks($includedBlocks, array(
                'ToDelete' => ($toDelete == 0) ? 3 : 0,
            ));

            if (! $result) {
                $this->blockRepository->rollback();

                break;
            }
        }

        return $result;
    }
}
