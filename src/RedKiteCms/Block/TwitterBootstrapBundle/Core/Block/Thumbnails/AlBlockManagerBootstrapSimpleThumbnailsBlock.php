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
class AlBlockManagerBootstrapSimpleThumbnailsBlock extends AlBlockManagerJsonBlockCollection
{
    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {        
        $value = '
            {
                "0" : {
                    "type": "BootstrapSimpleThumbnailBlock"
                },
                "1" : {
                    "type": "BootstrapSimpleThumbnailBlock"
                }
            }';
        
        return array('Content' => $value);
    }

    /**
     * Renders the App-Block's content view
     *
     * @return string|array
     */
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $bootstrapVersion = $this->container->get('red_kite_cms.active_theme')->getThemeBootstrapVersion();   
        $template = 'TwitterBootstrapBundle:Content:Thumbnails/' . $bootstrapVersion . '/thumbnails.html.twig';
        
        return array('RenderView' => array(
            'view' => $template,
            'options' => array(
                'values' => $items,
            ),
        ));
    }
}
