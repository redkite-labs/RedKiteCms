<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Translator;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Translator\Translator;


/**
 * TranslatorTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class TranslatorTest extends TestCase
{
    private $translator;
    private $configuration;

    protected function setUp()
    {
        parent::setUp();

        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->configuration = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Configuration\ConfigurationInterface');
    }
    
    public function testPageRepositoryInjectedBySetters()
    {
        $configuration = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Configuration\ConfigurationInterface');
        $translator = new Translator($this->translator, $this->configuration);
        $this->assertEquals($translator, $translator->setConfiguration($configuration));
        $this->assertEquals($configuration, $translator->getConfiguration());
        $this->assertNotSame($this->configuration, $translator->getConfiguration());
    }

    public function testTranslatorReturnsTheGivenMessageWhenTranslatorIsNotSet()
    {
        $this->translator->expects($this->never())
            ->method('trans');
            
        $this->configuration->expects($this->never())
            ->method('read');

        $translator = new Translator();
        $this->assertNull($translator->getTranslator());
        $this->assertEquals('My message', $translator->translate('My message'));
    }

    public function testTranslatorReturnsTheTRanslatedMessageWhenTranslatorIsSet()
    {
        $this->initTranslator();
        $this->initConfiguration();
        
        $translator = new Translator($this->translator, $this->configuration);
        $this->assertEquals($this->translator, $translator->getTranslator());
        $this->assertEquals('translated!', $translator->translate('My message'));
    }

    public function testInitTranslatorBySettings()
    {
        $this->initTranslator();
        $this->initConfiguration();
        
        $translator = new Translator();
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
            ->with('My message', array(), $expectedValue)
            ->will($this->returnValue('translated!'));
        $this->initConfiguration();
        
        $translator = new Translator($this->translator, $this->configuration);
        $this->assertEquals($this->translator, $translator->getTranslator());
        $this->assertEquals('translated!', $translator->translate('My message', array(), $value));
    }
    
    public function testLocationArgumentIsUsed()
    {
        $this->translator->expects($this->once())
            ->method('trans')
            ->with('My message', array(), 'messages', 'it')
            ->will($this->returnValue('translated!'));
        $this->configuration->expects($this->never())
            ->method('read');
        
        $translator = new Translator($this->translator, $this->configuration);
        $this->assertEquals($this->translator, $translator->getTranslator());
        $this->assertEquals('translated!', $translator->translate('My message', array(), 'messages', 'it'));
    }
    
    public function catalogues()
    {
        return array(            
            array(
                'RedKiteCmsBundle',
                'RedKiteCmsBundle',
            ),
            array(
                'messages',
                'messages',
            ),
            array(
                'RedKiteCmsBaseBlocksBundle',
                'RedKiteCmsBaseBlocksBundle',
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
