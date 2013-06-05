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

namespace AlphaLemon\Block\MenuBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockCollection;

/**
 * AlBlockManagerMenu
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerMenu extends AlBlockManagerJsonBlockCollection
{
    protected $blocksTemplate = 'MenuBundle:Content:menu.html.twig';
    
    /**
     * @see AlBlockManager::getDefaultValue()
     *
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
        
        return array("Content" => $value);
    }
    
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => $this->blocksTemplate,
            'options' => array(
                'items' => $items, 
            ),
        ));
    }
}
