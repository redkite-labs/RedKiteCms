<?php
/**
 * This file is part of the BusinessDropCapBundle and it is distributed
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

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Tests\Unit\Core\Form;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\File\AlFileType;

/**
 * AlFileTypeTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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