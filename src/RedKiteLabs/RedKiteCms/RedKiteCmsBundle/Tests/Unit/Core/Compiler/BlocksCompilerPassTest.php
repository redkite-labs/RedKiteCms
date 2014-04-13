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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Compiler;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Compiler\BlocksCompilerPass;

/**
 * BlocksCompilerPassTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlocksCompilerPassTest extends TestCase
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
        $compiler = new BlocksCompilerPass();
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

        $compiler = new BlocksCompilerPass();
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

        $compiler = new BlocksCompilerPass();
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
