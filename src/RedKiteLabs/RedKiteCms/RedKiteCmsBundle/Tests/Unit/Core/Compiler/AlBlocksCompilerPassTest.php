<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Compiler;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Compiler\AlBlocksCompilerPass;

/**
 * AlBlocksCompilerPassTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlocksCompilerPassTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
    }

    public function testNothingIsParsedWhenRequiredDefinitionExists()
    {
        $this->setUpDefinition(false);
        $compiler = new AlBlocksCompilerPass();
        $this->assertNull($compiler->process($this->container));
    }

    public function testTagIsNotParsedWhenAnyAttributeIsSpecified()
    {
        $this->setUpDefinition();

        $tag = array(
            'service_id' => array()
        );

        $this->container
                ->expects($this->once())
                ->method('findTaggedServiceIds')
                ->will($this->returnValue($tag));

        $this->definition
                ->expects($this->never())
                ->method('addMethodCall');

        $compiler = new AlBlocksCompilerPass();
        $this->assertNull($compiler->process($this->container));
    }

    public function testTagIsParsed()
    {
        $this->setUpDefinition();

        $tag = array(
            'service_id' => array(
                array(
                    'description' => 'Business slider',
                    'type' => 'BusinessSlider',
                    'group' => 'business_theme_apps',
                )
            )
        );

        $this->container
                ->expects($this->once())
                ->method('findTaggedServiceIds')
                ->will($this->returnValue($tag));

        $this->definition
                ->expects($this->once())
                ->method('addMethodCall')
                ->with('addBlockManager');

        $compiler = new AlBlocksCompilerPass();
        $this->assertNull($compiler->process($this->container));
    }

    private function setUpDefinition($value = true)
    {
        $this->container
                ->expects($this->once())
                ->method('hasDefinition')
                ->will($this->returnValue($value));

        $expectation = (false === $value) ? $this->never() : $this->once();
        $this->container
                ->expects($expectation)
                ->method('getDefinition')
                ->will($this->returnValue($this->definition));
    }
}
