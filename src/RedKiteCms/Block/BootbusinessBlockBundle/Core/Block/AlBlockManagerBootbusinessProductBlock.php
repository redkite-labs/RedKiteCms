<?php
/**
 * An AlphaLemonCms Block
 */

namespace AlphaLemon\Block\BootbusinessProductBlockBundle\Core\Block;

use AlphaLemon\Block\BootstrapThumbnailBlockBundle\Core\Block\AlBlockManagerBootstrapThumbnailBlock;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;

/**
 * Description of AlBlockManagerBootbusinessProductBlock
 */
class AlBlockManagerBootbusinessProductBlock extends AlBlockManagerBootstrapThumbnailBlock
{
    public function getHtml()
    {
        $items = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => 'BootbusinessProductBlockBundle:Product:product.html.twig',
            'options' => array('values' => $items, 'parent' => $this->alBlock),
        ));
    }
}