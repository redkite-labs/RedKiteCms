<?php
/**
 * A RedKiteCms Block
 */

namespace RedKiteCms\Block\BootbusinessBlockBundle\Core\Block;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Thumbnails\AlBlockManagerBootstrapThumbnailsBlock;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;

/**
 * Description of AlBlockManagerBootbusinessBlock
 */
class AlBlockManagerBootbusinessProductBlock extends AlBlockManagerBootstrapThumbnailsBlock
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
        $items = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => 'BootbusinessBlockBundle:Product:product.html.twig',
            'options' => array('values' => $items, 'parent' => $this->alBlock),
        ));
    }
}
