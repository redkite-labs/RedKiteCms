<?php
/**
 * An AlphaLemonCms Block
 */

namespace AlphaLemon\Block\BootbusinessProductBlockBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * Description of AlBlockManagerBootbusinessProductBlock
 */
class AlBlockManagerBootbusinessPartnerBlock extends AlBlockManager
{
    public function getDefaultValue()
    {
        return array('Content' => '');
    }
    
    protected function renderHtml()
    {
        return array('RenderView' => array(
            'view' => 'BootbusinessProductBlockBundle:Partner:partner.html.twig',
        ));
    }
}
