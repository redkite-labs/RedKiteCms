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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Translator;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Translator\AlTranslator;

class ObjectTranslatable extends AlTranslator
{
}

/**
 * AlTranslatorTest
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
