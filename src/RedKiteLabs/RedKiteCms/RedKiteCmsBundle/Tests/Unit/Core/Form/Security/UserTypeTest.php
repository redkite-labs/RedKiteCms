<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Security;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Security\UserType;

/**
 * UserTypeFormTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class UserTypeFormTest extends BaseType
{
    protected function configureFields()
    {
        return array(
            'id',
            'username',
            'password',
            'email',
            'Role',
        );
    }
    
    protected function getForm()
    {
        return new UserType();
    }
    
    public function testDefaultOptions()
    {
        $this->setBaseResolver();

        $options = array(
            'data_class' => 'RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\User',
        );
        $this->resolver
            ->expects($this->at(1))
            ->method('setDefaults')
            ->with($options)
        ;

        $this->getForm()->setDefaultOptions($this->resolver);
    }
    
    public function testGetName()
    {
        $this->assertEquals('al_user', $this->getForm()->getName());
    }
}