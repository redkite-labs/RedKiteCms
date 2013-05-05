<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Form\Base;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;

/**
 * AlImageTypeTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
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