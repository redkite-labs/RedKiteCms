<?php
/**
 * This file is part of the BusinessDropCapBundle and it is distributed
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

namespace AlphaLemon\Block\FileBundle\Tests\Unit\Core\Form;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\Block\FileBundle\Core\Form\AlFileType;

/**
 * AlFileTypeTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlFileTypeTest extends TestCase
{
    public function testConfigure()
    {
        $listener = new AlFileType();
        
        $formBuilder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
                            ->disableOriginalConstructor()
                            ->getMock();  
        
        $formBuilder->expects($this->at(0))
                    ->method('add')
                    ->with('file');
        
        $formBuilder->expects($this->at(1))
                    ->method('add')
                    ->with('description');
        
        $formBuilder->expects($this->at(2))
                    ->method('add')
                    ->with('opened');
        
        $listener->buildForm($formBuilder, array());
    }
}