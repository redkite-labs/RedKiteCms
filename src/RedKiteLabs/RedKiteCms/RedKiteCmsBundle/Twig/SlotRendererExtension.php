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
    public function renderSlot($slotName = null)
    {
        $this->checkSlotName($slotName);

        $content = "";
        try {
            $slotContents = array();
            $pageTree = $this->container->get('alpha_lemon_cms.page_tree');
            $blockManagers = $pageTree->getBlockManagers($slotName);

            foreach ($blockManagers as $blockManager) {
                if (null === $blockManager) continue;

                $slotContents[] = $this->doRender($blockManager->toArray(), true);
            }

            if (count($slotContents) == 0 && $pageTree->isCmsMode()) {
                $slotContents[] = sprintf('<div class="al_editable {id: \'0\', slotName: \'%s\'}" data-toggle="context" data-target="#al_context_menu">%s</div>', $slotName, 'This slot has any content inside. Use the contextual menu to add a new one');
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
    public function renderBlock(array $block = null, $add = false)
    {
        if (null === $block) {
            throw new \InvalidArgumentException("renderBlock function requires an array to render its contents. A null block argument has given");
        }

        return $this->doRender($block, $add);
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
        );
    }

    /**
     * Renders the slot
     *
     * @param array $block
     * @param boolean $add
     * @return string
     * @throws Exception
     */
    protected function doRender(array $block = array(), $add = false)
    {
        try {
            if (empty($block)) {
                return "";
            }
            
            $templating = $this->container->get('templating');
            $slotName = $block["Block"]["SlotName"];
            $content = $this->blockContentToHtml($block['Content']);
            
            if (strpos($content, '<script') !== false) {
                $content = "A script content is not rendered in editor mode";
            }

            if (null === $block['Block']["Id"]) {
                return $templating->render('AlphaLemonCmsBundle:Slot:map_slot.html.twig', array(
                    'slot_name' => $slotName,
                    'content' => $content,
                ));
            }
            
            $hideInEditMode = (array_key_exists('HideInEditMode', $block) && $block['HideInEditMode']) ? 'al_hide_edit_mode' : '';
            $scriptToHideContents = ($hideInEditMode != '') ? sprintf("$('#block_%s').data('block', '%s');", $block['Block']["Id"], rawurlencode($content)) : '';
            $internalJavascript = (string)$block["InternalJavascript"];
            $internalJavascript = ($internalJavascript != "" && (bool)$block["ExecuteInternalJavascript"]) ? $internalJavascript : '';
            $template = ($add) ? 'editable_block.html.twig' : '_block.html.twig';
            
            return $templating->render('AlphaLemonCmsBundle:Slot:' . $template, array(
                'block_id' => $block['Block']["Id"],
                'hide_in_edit_mode' => $hideInEditMode,
                'slot_name' => $slotName,
                'type' => $block['Block']['Type'],
                'editor_width' => $block['EditorWidth'],
                'content' => $content,
                'contents_hidden_script' => $scriptToHideContents,
                'internal_javascript' => $internalJavascript,
            ));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    
}