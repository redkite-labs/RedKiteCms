<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter;

use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\BlockModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\LanguageModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\PageModelInterface;

abstract class AlSlotConverterBase implements AlSlotConverterInterface
{
    protected $pageContentsContainer;
    protected $languageModel;
    protected $pageModel;
    protected $blockModel;
    protected $slot;
    protected $arrayBlocks;

    public function __construct(AlSlot $slot, AlPageContentsContainerInterface $pageContentsContainer, LanguageModelInterface $languageModel, PageModelInterface $pageModel, BlockModelInterface $blockModel)
    {
        $this->slot = $slot;
        $this->pageContentsContainer = $pageContentsContainer;
        $this->languageModel = $languageModel;
        $this->pageModel = $pageModel;
        $this->blockModel = $blockModel;
        $slotBlocks =  $this->pageContentsContainer->getSlotBlocks($this->slot->getSlotName());
        $this->blocksToArray($slotBlocks);
    }
    
    protected function removeContents()
    {
        $blocks = $this->blockModel->retrieveContentsBySlotName($this->slot->getSlotName());
        if(count($blocks) > 0) {
            try {
                $result = null;
                
                $this->blockModel->startTransaction();
                foreach($blocks as $block) {
                    $result = $this->blockModel
                                ->setModelObject($block)
                                ->delete();

                    if(!$result) break;
                }

                if ($result) {
                    $this->blockModel->commit();
                }
                else {
                    $this->blockModel->rollBack();
                }
                
                return $result;
            }
            catch(\Exception $e)
            {
                if (isset($this->blockModel) && $this->blockModel !== null) {
                    $this->blockModel->rollBack();
                }

                throw $e;
            }
        }
    }
    
    private function blocksToArray(array $slotBlocks)
    {
        foreach($slotBlocks as $block) {
            $aBlock = $block->toArray();
            unset($aBlock["Id"]);
            
            $this->arrayBlocks[] = $aBlock;
        }
    }
    
    protected function updateBlock($block, $idLanguage, $idPage)
    {
        $block["LanguageId"] = $idLanguage;
        $block["PageId"] = $idPage; 
        
        $className = $this->blockModel->getModelObjectClassName();
        $modelObject = new $className();
        
        $result = $this->blockModel
                    ->setModelObject($modelObject)
                    ->save($block);

        return $result;
    }
    
    /*
    protected function cloneAndAddContent($content, $idLanguage, $idPage)
    {
        $alBlockManager = AlBlockManagerFactory::createBlock($this->container, $content->getClassName()); 
        $contentValue = array(
            "PageId"                => $idPage,
            "LanguageId"            => $idLanguage,
            "SlotName"              => $content->getSlotName(),
            "ClassName"             => $content->getClassName(),
            "HtmlContent"           => $content->getHtmlContent(),
            "InternalJavascript"    => $content->getInternalJavascript(),
            "InternalStylesheet"    => $content->getInternalStylesheet(),
            "ExternalJavascript"    => $content->getExternalJavascript(),
            "ExternalStylesheet"    => $content->getExternalStylesheet(),
            "ContentPosition"       => $content->getContentPosition()
        );
        $alBlockManager->save($contentValue);
        
        return $alBlockManager->get();
    }*/
}