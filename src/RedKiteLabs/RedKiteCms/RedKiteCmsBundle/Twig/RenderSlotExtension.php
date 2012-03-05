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
            $alContents = $this->pageTree->getContents($slotName);
            if(count($alContents) > 0)
            {
                foreach($alContents as $alContent)
                {
                    if(\array_key_exists('Id', $alContent))
                    {
                        $hideInEditMode = ($alContent['HideInEditMode']) ? 'al_hide_edit_mode' : '';
                        $result .= sprintf('<div class="al_editable %s cmVoice {id: \'%s\', slotName: \'%s\', type: \'%s\', cMenu:\'context_menu_1\'}">%s</div>', $hideInEditMode, $alContent['Id'], $slotName, strtolower($alContent['Type']), $alContent['HtmlContentCMSMode']);
                    }
                    else
                    {
                        if(\array_key_exists('RenderView', $alContent))
                        {
                            $result .= $this->container->get('templating')->render($alContent['RenderView']['view'], $alContent['RenderView']['params']);
                        }
                        else if(\array_key_exists('HtmlContent', $alContent))
                        {
                            $result .= $alContent['HtmlContent'];
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
