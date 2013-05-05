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

namespace AlphaLemon\Block\LinkBundle\Tests\Unit\Core\Form;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\Block\LinkBundle\Core\Form\AlLinkType;

/**
 * AlLinkTypeTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlLinkTypeTest extends TestCase
{
    public function testForm()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
                    ->disableOriginalConstructor()
                    ->getMock();
        
        $fields = array(
            'href',
            'value',
        );
        $builder->expects($this->any())
            ->method('add')
            ->will(new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls($fields))
        ;
        
        $form = new AlLinkType();
        $form->buildForm($builder, array());
    }
}