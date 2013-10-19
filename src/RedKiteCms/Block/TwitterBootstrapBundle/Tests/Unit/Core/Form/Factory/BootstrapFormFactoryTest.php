<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Form\Factory;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Factory\BootstrapFormFactory;

/**
 * BootstrapFormFactoryTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BootstrapFormFactoryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->activeTheme = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\ActiveTheme\AlActiveThemeInterface');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->factory = new BootstrapFormFactory($this->activeTheme, $this->formFactory);
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\RuntimeException
     * @expectedExceptionMessage Something went wrong: I cannot find any valid form for 1.x Twitter Bootstrap version
     */
    public function testAnExceptionIsThrownWhenAnyValidBootstrapVersionExists()
    {
        $this->activeTheme
            ->expects($this->once())
            ->method('getThemeBootstrapVersion')
            ->will($this->returnValue('1.x'));

        $this->formFactory
            ->expects($this->never())
            ->method('create');

        $this->factory->createForm('Button', 'AlButtonType');
    }

    /**
     * @dataProvider bootstrapVersionsProvider
     */
    public function testCreateForm($bootstrapVersion, $bootstrapToken)
    {
        $this->activeTheme
            ->expects($this->once())
            ->method('getThemeBootstrapVersion')
            ->will($this->returnValue($bootstrapVersion));

        $className = '\RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Button\\' . $bootstrapToken . '\AlButtonType';
        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->with(new $className());

        $this->factory->createForm('Button', 'AlButtonType');
    }

    public function bootstrapVersionsProvider()
    {
        return array(
            array(
                "2.x",
                'Two'
            ),
            array(
                "3.x",
                "Three",
            ),
        );
    }
}
