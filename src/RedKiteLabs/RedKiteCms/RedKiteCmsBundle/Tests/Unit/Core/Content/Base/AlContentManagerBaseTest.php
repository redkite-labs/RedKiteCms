<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\Base;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;


class AlContentManagerTester extends AlContentManagerBase
{

}

/**
 * AlContentManagerBaseTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlContentManagerBaseTest extends TestCase
{
    protected $dispatcher;
    protected $validator;
    protected $contentManager;

    protected function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidator')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->contentManager = new AlContentManagerTester($this->dispatcher, $this->validator);
    }

    public function testDispatcherInjectedByContructor()
    {
        $this->assertEquals($this->dispatcher, $this->contentManager->getDispatcher());
    }

    public function testDispatcherInjectedBySetters()
    {
        $dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->assertEquals($this->contentManager, $this->contentManager->setDispatcher($dispatcher));
        $this->assertEquals($dispatcher, $this->contentManager->getDispatcher());
        $this->assertNotEquals($this->dispatcher, $this->contentManager->getDispatcher());
    }

    public function testValidatorInjectedByContructor()
    {
        $this->assertEquals($this->validator, $this->contentManager->getValidator());
    }

    public function testValidatorInjectedBySetters()
    {
        $validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->assertEquals($this->contentManager, $this->contentManager->setValidator($validator));
        $this->assertEquals($validator, $this->contentManager->getValidator());
        $this->assertNotEquals($this->dispatcher, $this->contentManager->getValidator());
    }

    public function testTranslator()
    {
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->assertEquals($this->contentManager, $this->contentManager->setTranslator($translator));
        $this->assertEquals($translator, $this->contentManager->getTranslator());
    }
}
