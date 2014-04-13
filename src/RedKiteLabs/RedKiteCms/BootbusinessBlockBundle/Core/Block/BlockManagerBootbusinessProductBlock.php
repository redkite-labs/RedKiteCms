<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteLabs\RedKiteCms\BootbusinessBlockBundle\Core\Block;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Thumbnails\BlockManagerBootstrapThumbnailsBlock;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\BlockManagerJsonBlock;

/**
 * Description of BlockManagerBootbusinessProductBlock
 */
class BlockManagerBootbusinessProductBlock extends BlockManagerBootstrapThumbnailsBlock
{
    public function getDefaultValue()
    {        
        $value = '
            {
                "0" : {
                    "type": "BootbusinessProductThumbnailBlock"
                },
                "1" : {
                    "type": "BootbusinessProductThumbnailBlock"
                }
            }';
        
        return array('Content' => $value);
    }
    
    protected function renderHtml()
    {
        $items = BlockManagerJsonBlock::decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => 'BootbusinessBlockBundle:Product:product.html.twig',
            'options' => array('values' => $items, 'parent' => $this->alBlock),
        ));
    }
}
