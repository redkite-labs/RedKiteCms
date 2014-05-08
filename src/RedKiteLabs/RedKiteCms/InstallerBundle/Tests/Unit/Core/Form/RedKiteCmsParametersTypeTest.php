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

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Tests\Unit\Core\Form;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Form\RedKiteCmsParametersType;

/**
 * RedKiteCmsParametersTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class RedKiteCmsParametersTypeTest extends BaseType
{
    protected function configureFields()
    {
        return array(
            'bundle',
            'driver',
            'host',
            'database',
            'port',
            'user',
            'password',
            'website-url',
        );
    }
    
    protected function getForm()
    {
        return new RedKiteCmsParametersType();
    }
        
    public function testGetName()
    {
        $this->assertEquals('installer_parameters', $this->getForm()->getName());
    }
}