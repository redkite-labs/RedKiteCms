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

namespace AlphaLemon\AlphaLemonCmsBundle\Twig;

use AlphaLemon\ThemeEngineBundle\Twig\SlotRendererExtension as BaseSlotRendererExtension;
use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\TwigTemplateWriter\AlTwigTemplateWriter;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\General\RuntimeException;

/**
 * Adds the renderSlot function to Twig engine
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class SlotRendererExtension extends BaseSlotRendererExtension
{
    /**
     * Overrides the base renderSlot method
     */
    public function renderSlot($slotName = null, $extraAttributes = "")
    {
        $this->checkSlotName($slotName);
        
        $content = "";
        try {
            $slotContents = array();
            $pageTree = $this->container->get('alpha_lemon_cms.page_tree');
            $blockManagers = $pageTree->getBlockManagers($slotName);

            foreach ($blockManagers as $blockManager) {
                if (null === $blockManager) {
                    continue;
                }

                $slotContents[] = $this->renderBlock($blockManager, null, false, $extraAttributes);
            }

            if (count($slotContents) == 0 && $pageTree->isCmsMode()) {
                $slotContents[] = sprintf('<div data-editor="enabled" data-block-id="0" data-slot-name="%s" class="al-empty-slot-placeholer">%s</div>', $slotName, 'This slot has any content inside. Use the contextual menu to add a new one');
            }

            $content = implode(PHP_EOL, $slotContents);
            $content = AlTwigTemplateWriter::MarkSlotContents($slotName, $content);

        } catch (\Exception $ex) {
            $content = sprintf("Something was wrong rendering the %s slot. This is the returned error: %s", $slotName, $ex->getMessage());
        }

        return sprintf('<div class="al_%s">%s</div>', $slotName, $content);
    }

    /**
     * Renders a block
     *
     * @param array $block
     * @param boolean $add Returns the slot as new editable block
     * @return string
     * @throws \InvalidArgumentException
     */
    public function renderBlock(AlBlockManager $blockManager, $template = null, $included = false, $extraAttributes = '')
    {
        try {
            $block = $blockManager->toArray();
            if (empty($block)) {
                return "";
            }
            
            $templating = $this->container->get('templating');
            $slotName = $block["Block"]["SlotName"];
            $content = $this->blockContentToHtml($block['Content']);
            
            if (strpos($content, '<script') !== false) {
                $content = "A script content is not rendered in editor mode";
            }
            
            if (null === $template) {
                $template = '_block.html.twig';
            }
            
            if ( ! $blockManager->getEditorDisabled() && preg_match('/data\-editor="true"/s', $content)) { 
                $hideInEditMode = (array_key_exists('HideInEditMode', $block) && $block['HideInEditMode']) ? 'true' : 'false';
                $editorParameters = $blockManager->editorParameters();
                
                $cmsAttributes = $templating->render('AlphaLemonCmsBundle:Slot:editable_block_attributes.html.twig', array(
                    'block_id' => $block['Block']["Id"],
                    'hide_in_edit_mode' => $hideInEditMode,
                    'slot_name' => $slotName,
                    'type' => $block['Block']['Type'],
                    'content' => $content,
                    'edit_inline' => $block['EditInline'],
                    'editor' => $editorParameters,
                    'extra_attributes' => $extraAttributes,
                    'included' => $included,
                ));
                      
                if (preg_match('/data\-encoded\-content=\'(.*?)\'/s', $cmsAttributes, $matches))
                {
                    $cmsAttributes = preg_replace('/data\-encoded\-content=\'(.*?)\'/s', 'data-encoded-content=\'' . rawurlencode($matches[1]) . '\'', $cmsAttributes);                    
                }
                
                $content = preg_replace('/data\-editor="true"/', $cmsAttributes . ' data-editor="enabled"', $content);
            }
            
            return $templating->render('AlphaLemonCmsBundle:Slot:' . $template, array(
                'block_id' => $block['Block']["Id"],
                'slot_name' => $slotName,
                'type' => $block['Block']['Type'],
                'content' => $content,
                'edit_inline' => $block['EditInline'],
            ));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Converts a block's content to html
     * 
     * @param array|string $block
     * @return string
     */
    public function blockContentToHtml($content)
    {
        $result = $content;    
        if (is_array($content)) {
            $result = "";
            if (\array_key_exists('RenderView', $content)) {
                $viewsRenderer = $this->container->get('alpha_lemon_cms.view_renderer');
                $result = $viewsRenderer->render($content['RenderView']); 
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'renderSlot' => new \Twig_Function_Method($this, 'renderSlot', array(
                'is_safe' => array('html'),
            )),
            'renderBlock' => new \Twig_Function_Method($this, 'renderBlock', array(
                'is_safe' => array('html'),
            )),
            'blockContentToHtml' => new \Twig_Function_Method($this, 'blockContentToHtml', array(
                'is_safe' => array('html'),
            )),
            'renderIncludedBlock' => new \Twig_Function_Method($this, 'renderIncludedBlock', array(
                'is_safe' => array('html'),
            )),
        );
    }
    
    public function renderIncludedBlock($key, AlBlockManager $parent = null, $type = "Text", $addWhenEmpty = false, $defaultContent = "", $extraAttributes = "")
    {
        $blocksRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $repository = $blocksRepository->createRepository('Block');
        $blocks = $repository->retrieveContents(null,  null, $key, array(0, 2, 3)); 
        
        if (count($blocks) > 0) { 
            $alBlock = $blocks[0];
            $blockManagerFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
            $blockManager = $blockManagerFactory->createBlockManager($alBlock->getType());
            $blockManager->set($alBlock);
            if (null !== $parent) {
                $blockManager->setEditorDisabled($parent->getEditorDisabled());
            }
            
            $content = $this->renderBlock($blockManager, '_included_block.html.twig', true, $extraAttributes);
        } else {
            if (true === $addWhenEmpty) {
                if (null === $parent) {
                    throw new RuntimeException("You must provide a valid AlBlockManager instance to automatically add a new Block");
                }
                
                $blockManagerFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
                $blockManager = $blockManagerFactory->createBlockManager($type);
                $blockManager->setEditorDisabled($parent->getEditorDisabled());
                
                $values = array(
                  "PageId"          => $parent->get()->getPageId(),
                  "LanguageId"      => $parent->get()->getLanguageId(),
                  "SlotName"        => $key,
                  "Type"            => $type,
                  "ContentPosition" => 1,
                );            
                
                if ( ! empty($defaultContent)) {
                    $values["Content"] = $defaultContent;
                }
                
                $blockManager->save($values);
                $content = $this->renderBlock($blockManager, '_included_block.html.twig', true, $extraAttributes);
            }
            else {
                $content = sprintf('<div data-editor="enabled" data-block-id="0" data-slot-name="%s" data-included="1">%s</div>', $key, 'This slot has any content inside. Use the contextual menu to add a new one');
            }
        }
        
        return $content;
    }
}
