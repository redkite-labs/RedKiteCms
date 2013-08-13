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
 
namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Accordion;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockCollection;

/**
 * Defines the Block Manager to handle a Bootstrap Accordion
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapAccordionBlock extends AlBlockManagerJsonBlockCollection
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {        
        $value = '
            {
                "0" : {
                    "0": "item"
                },
                "1" : {
                    "0": "item"
                }
            }';
        
        return array('Content' => $value);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Accordion/accordion.html.twig',
            'options' => array('items' => $items),
        ));
    }
}
