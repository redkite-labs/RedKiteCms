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

use AlphaLemon\PageTreeBundle\Core\PageTree\AlPageTree;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use AlphaLemon\ThemeEngineBundle\Twig\SlotRendererExtension as BaseSlotRendererExtension;

/**
 * Adds the renderSlot function to Twig engine
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class SlotRendererExtension extends BaseSlotRendererExtension
{
    public function __construct(ContainerInterface $container, AlPageTree $pageTree)
    {
        parent::__construct($container, $pageTree);
    }

    public function renderSlot($slotName = null)
    {
        if(null === $slotName)
        {
            throw new InvalidArgumentException("renderSlot function requires a valid slot name to render the contents");
        }

        try
        {
            $result = array();
            $blocks = $this->pageTree->getContents($slotName);
            if(count($blocks) > 0)
            {
                foreach($blocks as $block)
                {
                    $result[] = $this->doRender($block, true);
                }
            }
            else
            {
                if($this->pageTree->isCmsMode())
                {
                    $result[] = sprintf('<div class="al_editable {id: \'0\', slotName: \'%s\'}">%s</div>', $slotName, 'This slot has any content inside. Use the contextual menu to add a new one');
                }
            }

            return sprintf('<div class="al_%s">%s</div>', $slotName, implode("\n", $result));
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function renderBlock(array $block = null, $add = false)
    {
        if(null === $block)
        {
            throw new InvalidArgumentException("renderBlock function requires an array to render its contents. A null block parameter has given");
        }

        return $this->doRender($block, $add);
    }

    protected function doRender(array $block = null, $add = false)
    {
        try
        {
            $result = "";
            $slotName = $block["Block"]["SlotName"];
            if(\array_key_exists('Id', $block))
            {
                if($block['InternalJavascript'] != "" && array_key_exists('added', $block)) $content .= sprintf('<script>%s</script>', $block['InternalJavascript']);
                $result = $block['HtmlContentCMSMode'];
            }
            else
            {
                if(\array_key_exists('RenderView', $block))
                {
                    $result = $this->container->get('templating')->render($block['RenderView']['view'], $block['RenderView']['params']);
                }
                else if(\array_key_exists('HtmlContent', $block))
                {
                    $result = $block['HtmlContent'];
                }
            }

            $hideInEditMode = ($block['HideInEditMode']) ? 'al_hide_edit_mode' : '';
            $content = sprintf('<div>%s</div>', $result);
            if($add) $content = sprintf ('<div id="block_%s" class="%s al_editable {id: \'%s\', slotName: \'%s\', type: \'%s\'}">%s</div>', $block['Block']["Id"], $hideInEditMode, $block['Block']['Id'], $slotName, strtolower($block['Block']['ClassName']), $content);

            return $content;
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    /**
     * @return array
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
     * @return string
     */
    public function getName() {
        return 'slotRenderer';
    }
}
