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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\JsonBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\JsonBlock\RenderingItemEditorListener;

class TestRenderingItemEditorListener extends RenderingItemEditorListener
{
    protected $configureParams = null;

    public function setConfigureParams($configureParams)
    {
        $this->configureParams = $configureParams;
    }

    protected function configure()
    {
        return $this->configureParams;
    }
}

class TestForm extends \AlphaLemon\AlphaLemonCmsBundle\Core\Form\JsonBlock\JsonBlockType
{
}

/**
 * RenderingListEditorListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class RenderingItemEditorListenerTest extends BaseTestRenderingEditorListener
{
    protected function setUp()
    {
        parent::setUp();

        $this->testListener = new TestRenderingItemEditorListener();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The "configure" method for class "AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestRenderingItemEditorListener" must return an array
     */
    public function testAnExceptionIsThrownWhenTheArgumentIsNotAnArray()
    {
        $this->setUpEvents(0);
        $this->testListener->setConfigureParams('Fake');
        $this->testListener->onBlockEditorRendering($this->event, array());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The array returned by the "configure" method of the class "AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestRenderingItemEditorListener" method must contain the "blockClass" option
     */
    public function testAnExceptionIsThrownWhenTheArgumentClassDoesNotExist()
    {
        $this->setUpEvents(0);
        $this->testListener->setConfigureParams(array('Fake'));
        $this->testListener->onBlockEditorRendering($this->event);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The array returned by the "configure" method of the class "AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestRenderingItemEditorListener" method must contain the "blockClass" option
     */
    public function testAnExceptionIsThrownWhenTheBlockClassOptionDoesNotExist()
    {
        $this->setUpEvents(0);
        $this->testListener->setConfigureParams(array('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager'));
        $this->testListener->onBlockEditorRendering($this->event);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The block class "Fake" defined in "AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestRenderingItemEditorListener" does not exists
     */
    public function testAnExceptionIsThrownWhenTheBlockClassDoesNotExist()
    {
        $this->setUpEvents(0);
        $this->testListener->setConfigureParams(array('blockClass' => 'Fake'));
        $this->testListener->onBlockEditorRendering($this->event);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The array returned by the "configure" method of the class "AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestRenderingItemEditorListener" method must contain the "formClass" option
     */
    public function testAnExceptionIsThrownWhenTheFormClassOptionHasNOtBeenDefined()
    {
        $this->setUpEvents(0);
        $this->testListener->setConfigureParams(array('blockClass' => 'AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager'));
        $this->testListener->onBlockEditorRendering($this->event);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The form class "Fake" defined in "AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestRenderingItemEditorListener" does not exists
     */
    public function testAnExceptionIsThrownWhenTheFormClassOptionDoesNotExist()
    {
        $this->setUpEvents(0);
        $this->testListener->setConfigureParams(
            array(
                'blockClass' => 'AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager',
                'formClass' => 'Fake',
            )
        );
        $this->testListener->onBlockEditorRendering($this->event);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Something goes wrong retrieving the block manager
     */
    public function testAnExceptionIsThrownBackWhenSomethingGoesWrong()
    {
        $this->event->expects($this->once())
            ->method('getBlockManager')
            ->will($this->throwException(new \RuntimeException('Something goes wrong retrieving the block manager')));

        $this->testListener->setConfigureParams(
            array(
                'blockClass' => 'AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager',
                'formClass' => 'AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestForm',
            )
        );
        $this->testListener->onBlockEditorRendering($this->event);
    }

    public function testTheEditorHasBeenRendered()
    {
        $this->setUpEvents();
        $this->setUpBlockManager();
        $this->setUpContainer();
        $this->testListener->setConfigureParams(
            array(
                'blockClass' => 'AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager',
                'formClass' => 'AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestForm',
            )
        );
        $this->testListener->onBlockEditorRendering($this->event);
    }

    protected function setUpContainer()
    {
        $this->engine->expects($this->once())
            ->method('render')
            ->will($this->returnValue('<p>rendered template</p>'));

        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
                            ->disableOriginalConstructor()
                            ->getMock();
        $form->expects($this->once())
            ->method('createView')
            ->will($this->returnValue('rendered form'));

        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->container->expects($this->at(0))
            ->method('get')
            ->with('form.factory')
            ->will($this->onConsecutiveCalls($formFactory));

        $this->container->expects($this->at(1))
            ->method('get')
            ->with('templating')
            ->will($this->onConsecutiveCalls($this->engine));
    }
}
