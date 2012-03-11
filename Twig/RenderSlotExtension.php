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
use AlphaLemon\ThemeEngineBundle\Twig\RenderSlotExtension as BaseRenderSlotExtension;

/**
 * Adds the renderSlot function to Twig engine
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class RenderSlotExtension extends BaseRenderSlotExtension
{
    public function __construct(ContainerInterface $container, AlPageTree $pageTree)
    {
        parent::__construct($container, $pageTree);
    }

    public function render($slotName = null)
    {
        if(null === $slotName)
        {
            throw new InvalidArgumentException("renderSlot function requires a valid slot name to render the contents");
        }

        try
        {
            $result = '';  
            $alBlocks = $this->pageTree->getContents($slotName);
            if(count($alBlocks) > 0)
            {
                foreach($alBlocks as $alBlock)
                {
                    if(\array_key_exists('Id', $alBlock))
                    {
                        $hideInEditMode = ($alBlock['HideInEditMode']) ? 'al_hide_edit_mode' : '';
                        $result .= sprintf('<div class="al_editable %s cmVoice {id: \'%s\', slotName: \'%s\', type: \'%s\', cMenu:\'context_menu_1\'}">%s</div>', $hideInEditMode, $alBlock['Id'], $slotName, strtolower($alBlock['Type']), $alBlock['HtmlContentCMSMode']);
                    }
                    else
                    {
                        if(\array_key_exists('RenderView', $alBlock))
                        {
                            $result .= $this->container->get('templating')->render($alBlock['RenderView']['view'], $alBlock['RenderView']['params']);
                        }
                        else if(\array_key_exists('HtmlContent', $alBlock))
                        {
                            $result .= $alBlock['HtmlContent'];
                        }
                    }
                }
            }
            else
            {
                if($this->pageTree->isCmsMode())
                {
                    $result .= sprintf('<div class="al_editable cmVoice {id: \'0\', slotName: \'%s\', cMenu:\'context_menu_1\'}">%s</div>', $slotName, 'This slot has any content inside. Use the contextual menu to add a new one');
                }
            }
            
            return sprintf('<div class="al_%s">%s</div>', $slotName, $result);
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
            'renderSlot' => new \Twig_Function_Method($this, 'render', array(
                'is_safe' => array('html'),
            )),
        );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'renderSlot';
    }
}
