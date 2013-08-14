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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\JsonBlock;

use RedKiteLabs\RedKiteCmsBundle\Core\Listener\JsonBlock\RenderingListEditorListener;

class TestRenderingListEditorListener extends RenderingListEditorListener
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

/**
 * RenderingListEditorListenerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class RenderingListEditorListenerTest extends BaseTestRenderingEditorListener
{
    protected function setUp()
    {
        parent::setUp();

        $this->testListener = new TestRenderingListEditorListener();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The "configure" method for class "RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestRenderingListEditorListener" must return an array
     */
    public function testAnExceptionIsThrownWhenTheArgumentIsNotAnArray()
    {
        $this->setUpEvents(0);
        $this->testListener->setConfigureParams('Fake');
        $this->testListener->onBlockEditorRendering($this->event, array());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The array returned by the "configure" method of the class "RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestRenderingListEditorListener" method must contain the "blockClass" option
     */
    public function testAnExceptionIsThrownWhenTheArgumentClassDoesNotExist()
    {
        $this->setUpEvents(0);
        $this->testListener->setConfigureParams(array('Fake'));
        $this->testListener->onBlockEditorRendering($this->event);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The array returned by the "configure" method of the class "RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestRenderingListEditorListener" method must contain the "blockClass" option
     */
    public function testAnExceptionIsThrownWhenTheBlockClassOptionDoesNotExist()
    {
        $this->setUpEvents(0);
        $this->testListener->setConfigureParams(array('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager'));
        $this->testListener->onBlockEditorRendering($this->event);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The block class "Fake" defined in "RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestRenderingListEditorListener" does not exists
     */
    public function testAnExceptionIsThrownWhenTheBlockClassDoesNotExist()
    {
        $this->setUpEvents(0);
        $this->testListener->setConfigureParams(array('blockClass' => 'Fake'));
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
                'blockClass' => 'RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager',
                'formClass' => 'RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\JsonBlock\TestForm',
            )
        );
        $this->testListener->onBlockEditorRendering($this->event);
    }

    public function testTheEditorHasBeenRendered()
    {
        $this->setUpEvents();
        $this->setUpBlockManager();
        $this->setUpContainer();

        $this->block->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));

        $this->testListener->setConfigureParams(array('blockClass' => 'RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager'));
        $this->testListener->onBlockEditorRendering($this->event);
    }

    protected function setUpContainer()
    {
        $this->engine->expects($this->once())
            ->method('render')
            ->will($this->returnValue('<p>rendered template</p>'));

        $this->container->expects($this->once())
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($this->engine));
    }
}
