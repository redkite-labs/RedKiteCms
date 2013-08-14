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
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Translator;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslator;


/**
 * AlTranslatorTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlTranslatorTest extends TestCase
{
    private $translator;
    private $configuration;

    protected function setUp()
    {
        parent::setUp();

        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->configuration = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Configuration\AlConfigurationInterface');        
    }

    public function testTranslatorReturnsTheGivenMessageWhenTranslatorIsNotSet()
    {
        $this->translator->expects($this->never())
            ->method('trans');
            
        $this->configuration->expects($this->never())
            ->method('read');

        $translator = new AlTranslator();
        $this->assertNull($translator->getTranslator());
        $this->assertEquals('My message', $translator->translate('My message'));
    }

    public function testTranslatorReturnsTheTRanslatedMessageWhenTranslatorIsSet()
    {
        $this->initTranslator();
        $this->initConfiguration();
        
        $translator = new AlTranslator($this->translator, $this->configuration);
        $this->assertEquals($this->translator, $translator->getTranslator());
        $this->assertEquals('translated!', $translator->translate('My message'));
    }

    public function testInitTranslatorBySettings()
    {
        $this->initTranslator();
        $this->initConfiguration();
        
        $translator = new AlTranslator();
        $translator->setTranslator($this->translator);
        $translator->setConfiguration($this->configuration);
        $this->assertEquals($this->translator, $translator->getTranslator());
        $this->assertEquals('translated!', $translator->translate('My message'));
    }
    
    /**
     * @dataProvider catalogues
     */
    public function testCatalogues($value, $expectedValue)
    {
        $this->translator->expects($this->once())
            ->method('trans')
            ->with('My message', array(), 'messages')
            ->will($this->returnValue('translated!'));
        $this->initConfiguration();
        
        $translator = new AlTranslator($this->translator, $this->configuration);
        $this->assertEquals($this->translator, $translator->getTranslator());
        $this->assertEquals('translated!', $translator->translate('My message', array(), 'messages'));
    }
    
    public function testLocationArgumentIsUsed()
    {
        $this->translator->expects($this->once())
            ->method('trans')
            ->with('My message', array(), 'messages', 'it')
            ->will($this->returnValue('translated!'));
        $this->configuration->expects($this->never())
            ->method('read');
        
        $translator = new AlTranslator($this->translator, $this->configuration);
        $this->assertEquals($this->translator, $translator->getTranslator());
        $this->assertEquals('translated!', $translator->translate('My message', array(), 'messages', 'it'));
    }
    
    public function catalogues()
    {
        return array(
            array(
                'messages',
                'messages',
            ),
            array(
                'catalogue',
                'en_catalogue',
            ),
        );
    }
    
    private function initTranslator()
    {
        $this->translator->expects($this->once())
            ->method('trans')
            ->will($this->returnValue('translated!'));
    }
    
    private function initConfiguration()
    {   
        $this->configuration->expects($this->once())
            ->method('read')
            ->with('language')
            ->will($this->returnValue('en'));
    }
}
