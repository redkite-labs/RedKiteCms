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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\JsonBlock;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\JsonBlock\JsonBlockType;

class jsonFormTester extends JsonBlockType
{
}


/**
 * JsonBlockTypeTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class JsonBlockTypeTest extends BaseType
{
    protected function configureFields()
    {
        return array(
        );
    }
    
    protected function getForm()
    {
        return new jsonFormTester();
    }
    
    public function testDefaultOptions()
    {
        $this->setBaseResolver();

        $this->getForm()->setDefaultOptions($this->resolver);
    }
    
    public function testGetName()
    {
        $this->assertEquals('al_json_block', $this->getForm()->getName());
    }
}