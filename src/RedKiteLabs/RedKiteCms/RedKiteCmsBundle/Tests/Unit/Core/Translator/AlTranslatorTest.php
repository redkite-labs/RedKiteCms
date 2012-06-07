<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\PageTree;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Translator\AlTranslator;


class ObjectTranslatable extends AlTranslator
{
}

/**
 * AlPageTreeTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlTranslatorTest extends TestCase
{
    private $translator;

    protected function setUp()
    {
        parent::setUp();

        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');        
    }

    public function testTranslatorReturnsTheGivenMessageWhenTranslatorIsNotSet()
    {
        $this->translator->expects($this->never())
            ->method('trans');
        
        $translator = new ObjectTranslatable();
        $this->assertNull($translator->getTranslator());
        $this->assertEquals('My message', $translator->translate('My message'));
    }
    
    public function testTranslatorReturnsTheTRanslatedMessageWhenTranslatorIsSet()
    {
        $this->translator->expects($this->once())
            ->method('trans')
            ->will($this->returnValue('translated!'));
        
        $translator = new ObjectTranslatable($this->translator);
        $this->assertEquals($this->translator, $translator->getTranslator());
        $this->assertEquals('translated!', $translator->translate('My message'));
    }
    
    public function testTranslatorReturnsTheTRanslatedMessageWhenTranslatorIsSetUsingTheSetterMethod()
    {
        $this->translator->expects($this->once())
            ->method('trans')
            ->will($this->returnValue('translated!'));
        
        $translator = new ObjectTranslatable();
        $translator->setTranslator($this->translator);
        $this->assertEquals($this->translator, $translator->getTranslator());
        $this->assertEquals('translated!', $translator->translate('My message'));
    }
}