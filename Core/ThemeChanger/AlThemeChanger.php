<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\ThemeChanger;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use AlphaLemon\ThemeEngineBundle\Core\Theme\AlThemeInterface;

/**
 * Description of AlThemeChanger
 *
 * @author alphalemon
 */
class AlThemeChanger
{
    protected $templateManager; 
    protected $factoryRepository;
    protected $blocksFactory;
    protected $languagesRepository;
    protected $pagesRepository;
    
    public function __construct(AlTemplateManager $templateManager, AlFactoryRepositoryInterface $factoryRepository, AlBlockManagerFactoryInterface $blocksFactory)
    {
        $this->templateManager = $templateManager;
        $this->factoryRepository = $factoryRepository;
        $this->blocksFactory = $blocksFactory;
        $this->languagesRepository = $this->factoryRepository->createRepository('Language');
        $this->pagesRepository = $this->factoryRepository->createRepository('Page');
    }
    
    public function change($previousTheme, $theme, $path, array $templatesMap)
    {
        $this->saveCurrentTheme($previousTheme, $path);
        $this->backupBlocks();
        $this->changeTemplate($theme, $templatesMap);
    }
    
    public function changeSlot($sourceSlotName, $targetSlotName)
    {
        try {
            $blocksRepository = $this->factoryRepository->createRepository('Block');

            $sourceBlocks = $blocksRepository->retrieveContents(null, null, $sourceSlotName, array(2, 3));
            $targetBlocks = $blocksRepository->retrieveContents(null, null, $targetSlotName);

            $blocksRepository->startTransaction();
            $result = $this->saveBlocks($sourceBlocks, $values = array(
                'SlotName' => $targetSlotName,
                'ToDelete' => 0,
            ));
            if ( ! $result) {
                $blocksRepository->rollback();

                return "The slot has not been changed due to an error occoured when saving to database";
            }

            $result = $this->saveBlocks($targetBlocks, $values = array(
                'SlotName' => $sourceSlotName,
                'ToDelete' => 3,
            ));
            if ( ! $result) {
                $blocksRepository->rollback();

                return "The slot has not been changed due to an error occoured when saving to database";
            }

            $blocksRepository->commit();
            
            return "The slot has been changed";
        } catch(\Exception $ex) {
            return $ex->getMessage();
        }
    }

    protected function backupBlocks()
    {
        try {
            $blockRepository = $this->factoryRepository->createRepository('Block');
            $blocks = $blockRepository->retrieveContents(null, null);
            $this->saveBlocks($blocks, array('ToDelete' => 2));            
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    protected function changeTemplate(AlThemeInterface $theme, $templatesMap)
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
    
    protected function saveCurrentTheme($theme, $themeStructureFile)
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
            
            @file_put_contents($themeStructureFile, json_encode($currentTheme));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    private function saveBlocks($blocks, $values)
    {
        $result = true;
        
        foreach ($blocks as $block) {               
            $blockManager = $this->blocksFactory->createBlockManager($block);
            if (null === $blockManager) { 
               continue;
            }
            
            $result = $blockManager
                ->set($block)
                ->save($values)
            ;         
            
            if ( ! $result) {
                break;
            }   
        }
        
        return $result;
    }
}
