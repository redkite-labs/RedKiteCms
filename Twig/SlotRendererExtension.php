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
                $slotContents[] = sprintf('<div class="al_editable {id: \'0\', slotName: \'%s\'}">%s</div>', $slotName, 'This slot has any content inside. Use the contextual menu to add a new one');
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
     * @param Boolean $add Returns the slot as new editable block
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
        );
    }

    /**
     * Renders the slot
     *
     * @param array $block
     * @param Boolean $add
     * @return string
     * @throws Exception
     */
    protected function doRender(array $block = null, $add = false)
    {
        try {
            $content = "";
            if(null === $block || empty($block)) return $content;

            $slotName = $block["Block"]["SlotName"];
            $content = $block['Content'];
            if (\array_key_exists('RenderView', $block)) {
                $content = $this->container->get('templating')->render($block['RenderView']['view'], $block['RenderView']['params']);
            }

            $hideInEditMode = (array_key_exists('HideInEditMode', $block) && $block['HideInEditMode']) ? 'al_hide_edit_mode' : '';
            if (null !== $block['Block']["Id"]) {
                $content = sprintf('<div>%s</div>', $content);
                if ($add) {
                    $content = sprintf ('<div id="block_%s" class="%s al_editable {id: \'%s\', slotName: \'%s\', type: \'%s\', editorWidth: \'%s\'}">%s</div>', $block['Block']["Id"], $hideInEditMode, $block['Block']['Id'], $slotName, strtolower($block['Block']['Type']), $block['EditorWidth'], $content);
                }
            }
            else {
                $content = sprintf ('<div id="al_map_%s" class="al_template_slot" >%s</div>', $slotName, $content);
            }

            return $content;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}