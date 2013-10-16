<?php
/*
 * This file is part of the TwitterBootstrapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 * 
 * @license    MIT LICENSE
 * 
 */

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Navbar;

use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Menu\AlBlockManagerMenu;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;

/**
 * Defines the Block Manager to handle the Bootstrap Navbar
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapNavbarBlock extends AlBlockManagerMenu
{    
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        $value = '
            {
                "0": {
                    "blockType" : "Link"
                },
                "1": {
                    "blockType" : "Link"
                }
            }';
            
        return array('Content' => $value);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function renderHtml()
    {
        $items = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock->getContent());
        
        $bootstrapVersion = $this->container->get('red_kite_cms.active_theme')->getThemeBootstrapVersion(); 
        $template = sprintf('TwitterBootstrapBundle:Content:Navbar/%s/navbar.html.twig', $bootstrapVersion);
        
        return array('RenderView' => array(
            'view' => $template,
            'options' => array(
                'items' => $items, 
            ),
        ));
    }
}
