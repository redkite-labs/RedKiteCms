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

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerContainer;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;

/**
 * AlBlockManagerMenu
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerMenu extends AlBlockManagerContainer
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
    
    public function getHtml()
    {
        $items = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => $this->blocksTemplate,
            'options' => array( 'items' => $items, 'parent' => $this->alBlock),
        ));
    }
    
    protected function edit(array $values)
    {
        $data = json_decode($values['Content'], true); 
        $savedValues = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock);
        
        if ($data["operation"] == "add") {
            $savedValues[] = $data["value"];
            $values = array("Content" => json_encode($savedValues));
        }
        
        if ($data["operation"] == "remove") {
            unset($savedValues[$data["item"]]);
            
            $blocksRepository = $this->container->get('alpha_lemon_cms.factory_repository');
            $repository = $blocksRepository->createRepository('Block');
            $repository->deleteIncludedBlocks($data["slotName"]);
            
            $values = array("Content" => json_encode($savedValues));
        }
        
        return parent::edit($values);
    }
}