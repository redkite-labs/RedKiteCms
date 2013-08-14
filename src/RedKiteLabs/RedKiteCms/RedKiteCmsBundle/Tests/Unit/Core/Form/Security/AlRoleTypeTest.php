<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\ImageBundle\Tests\Unit\Core\Form;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Security\AlRoleType;

/**
 * AlRoleTypeFormTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlRoleTypeFormTest extends AlBaseType
{
    protected function configureFields()
    {
        return array(
            'id',
            'role',
        );
    }
    
    protected function getForm()
    {
        return new AlRoleType();
    }
    
    public function testDefaultOptions()
    {
        $expectedResult = array(
            'data_class' => 'RedKiteLabs\RedKiteCmsBundle\Model\AlRole',
            'csrf_protection' => false,
        );
        
        $this->assertEquals($expectedResult, $this->getForm()->getDefaultOptions(array()));
    }
    
    public function testGetName()
    {
        $this->assertEquals('al_role', $this->getForm()->getName());
    }
}