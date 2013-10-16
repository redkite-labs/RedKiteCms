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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Thumbnails;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockCollection;

/**
 * Defines the Block Manager to handle a collection of Bootstrap Thumbnails
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapThumbnailsBlock extends AlBlockManagerBootstrapSimpleThumbnailsBlock
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {        
        $value = '
            {
                "0" : {
                    "type": "BootstrapThumbnailBlock"
                },
                "1" : {
                    "type": "BootstrapThumbnailBlock"
                }
            }';
        
        return array('Content' => $value);
    }

    /**
     * {@inheritdoc}
     
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Thumbnails/3.x/thumbnails.html.twig',
            'options' => array(
                'values' => $items,
            ),
        ));
    }*/
}
