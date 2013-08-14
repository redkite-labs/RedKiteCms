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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;

/**
 * AlBaseType
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class AlBaseType extends TestCase
{
    abstract protected function configureFields();
    
    abstract protected function getForm();
            
    public function testForm()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
                    ->disableOriginalConstructor()
                    ->getMock();
        
        $i = 0;
        $fields = $this->configureFields();        
        foreach ($fields as $field) { 
            $builder->expects($this->at($i))
                ->method('add')
                ->with($field)
            ;
            
            $i++;
        }
        
        $form = $this->getForm();
        $form->buildForm($builder, array());
    }
}