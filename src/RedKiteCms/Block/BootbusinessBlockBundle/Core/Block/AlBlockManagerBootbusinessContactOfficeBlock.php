<?php
/**
 * A RedKiteCms Block
 */

namespace RedKiteCms\Block\BootbusinessBlockBundle\Core\Block;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * Description of AlBlockManagerBootbusinessBlock
 */
class AlBlockManagerBootbusinessContactOfficeBlock extends AlBlockManager
{
    public function getDefaultValue()
    {
        return array('Content' => '');
    }
    
    protected function renderHtml()
    {
        return array('RenderView' => array(
            'view' => 'BootbusinessBlockBundle:ContactOffice:contact_office.html.twig',
        ));
    }
}
