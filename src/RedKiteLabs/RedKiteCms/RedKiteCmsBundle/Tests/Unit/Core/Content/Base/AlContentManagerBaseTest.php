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
    protected $eventsHandler;
    protected $validator;
    protected $contentManager;

    protected function setUp()
    {
        parent::setUp();

        $this->eventsHandler = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface');

        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidator')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->contentManager = new AlContentManagerTester($this->eventsHandler, $this->validator);
    }

    public function testEventsHandlerByContructor()
    {
        $this->assertEquals($this->eventsHandler, $this->contentManager->getEventsHandler());
    }

    public function testEventsHandlerBySetters()
    {
        $eventsHandler = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface');
        $this->assertSame($this->contentManager, $this->contentManager->setEventsHandler($eventsHandler));
        $this->assertSame($eventsHandler, $this->contentManager->getEventsHandler());
        $this->assertNotSame($this->eventsHandler, $this->contentManager->getEventsHandler());
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
        $this->assertSame($this->contentManager, $this->contentManager->setValidator($validator));
        $this->assertSame($validator, $this->contentManager->getValidator());
        $this->assertNotSame($this->validator, $this->contentManager->getValidator());
    }

    public function testTranslator()
    {
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->assertEquals($this->contentManager, $this->contentManager->setTranslator($translator));
        $this->assertEquals($translator, $this->contentManager->getTranslator());
    }
}
