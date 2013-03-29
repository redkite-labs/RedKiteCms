<?php
/**
 * An AlphaLemonCms Block
 */

namespace AlphaLemon\Block\BootbusinessProductBlockBundle\Core\Block;

use AlphaLemon\Block\BootstrapThumbnailBlockBundle\Core\Block\AlBlockManagerBootstrapThumbnailsBlock;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;

/**
 * Description of AlBlockManagerBootbusinessProductBlock
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
    
    public function getHtml()
    {
        $items = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => 'BootbusinessProductBlockBundle:Product:product.html.twig',
            'options' => array('values' => $items, 'parent' => $this->alBlock),
        ));
    }
}
