<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Security;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Security\AlUserType;

/**
 * AlUserTypeFormTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlUserTypeFormTest extends AlBaseType
{
    protected function configureFields()
    {
        return array(
            'id',
            'username',
            'password',
            'email',
            'AlRole',
        );
    }
    
    protected function getForm()
    {
        return new AlUserType();
    }
    
    public function testDefaultOptions()
    {
        $expectedResult = array(
            'data_class' => 'RedKiteLabs\RedKiteCmsBundle\Model\AlUser',
            'csrf_protection' => false,
        );
        
        $this->assertEquals($expectedResult, $this->getForm()->getDefaultOptions(array()));
    }
    
    public function testGetName()
    {
        $this->assertEquals('al_user', $this->getForm()->getName());
    }
}