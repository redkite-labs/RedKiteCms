<?php
/**
 * A RedKiteCms Block
 */

namespace RedKiteCms\Block\BootbusinessBlockBundle\Core\Block;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * Description of AlBlockManagerBootbusinessBlock
 */
class AlBlockManagerBootbusinessEventBlock extends AlBlockManager
{
    public function getDefaultValue()
    {
        return array('Content' => '');
    }
    
    protected function renderHtml()
    {
        return array('RenderView' => array(
            'view' => 'BootbusinessBlockBundle:Event:event.html.twig',
        ));
    }
}
