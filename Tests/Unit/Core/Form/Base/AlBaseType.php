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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;

/**
 * AlBaseType
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class AlBaseType extends TestCase
{
    protected $resolver;
    protected $translatorDomain = 'RedKiteCmsBundle';

    abstract protected function configureFields();
    
    abstract protected function getForm();

    public function __construct()
    {
        $this->resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');
    }

    public function testForm()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
                    ->disableOriginalConstructor()
                    ->getMock();
        
        $i = 0;
        $fields = $this->configureFields();        
        foreach ($fields as $field) { 
            if (is_array($field)) {
                $type = array_key_exists("type", $field) ? $field["type"] : "Text";
                $options = array_key_exists("options", $field) ? $field["options"] : array();

                $builder->expects($this->at($i))
                    ->method('add')
                    ->with($field["name"], $type, $options)
                ;
            } else {
                $builder->expects($this->at($i))
                    ->method('add')
                    ->with($field)
                ;
            }
            
            $i++;
        }
        
        $form = $this->getForm();
        $form->buildForm($builder, array());
    }

    protected function setBaseResolver()
    {
        $options = array(
            'translation_domain' => $this->translatorDomain,
            'csrf_protection' => false,
        );
        $this->resolver
            ->expects($this->at(0))
            ->method('setDefaults')
            ->with($options)
        ;
    }
}