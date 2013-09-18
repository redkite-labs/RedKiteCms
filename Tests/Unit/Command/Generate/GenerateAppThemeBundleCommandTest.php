<?php
/**
 * This file is part of the RedKiteLabsRedKiteCmsBundle and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Command;

use Sensio\Bundle\GeneratorBundle\Tests\Command\GenerateCommandTest;
use Symfony\Component\Console\Tester\CommandTester;
use org\bovigo\vfs\vfsStream;

/**
 * GenerateAppThemeBundleCommandTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class GenerateAppThemeBundleCommandTest extends GenerateCommandTest
{
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage A strict RedKiteCms App-Theme namespace must start with RedKiteCms\Theme and the bundle must be suffixed as ThemeBundle
     */
    public function testStrictGenerationRequiresRedKiteNamespace()
    {
        $options = array('--dir' => vfsStream::url('root'), '--no-strict' => false, '--namespace' => 'Foo/BarBundle', '--format' => 'yml', '--bundle-name' => 'BarBundle', '--structure' => true);
        $generator = $this->getGenerator();
        $generator
            ->expects($this->never())
            ->method('generateExt')
        ;

        $tester = new CommandTester($this->getCommand($generator, ''));
        $tester->execute($options, array('interactive' => false));
    }

    /**
     * @dataProvider getNonInteractiveCommandData
     */
    public function testNonInteractiveCommand($options, $expected)
    {
        list($namespace, $bundle, $dir, $format, $structure, $noStrict) = $expected;

        $commandOptions = array(
            'no-strict' => $noStrict,
        );

        $generator = $this->getGenerator();
        $generator
            ->expects($this->once())
            ->method('generateExt')
            ->with($namespace, $bundle, $dir, $format, $structure, $commandOptions)
        ;

        $tester = new CommandTester($this->getCommand($generator, ''));
        $tester->execute($options, array('interactive' => false));
    }

    public function getNonInteractiveCommandData()
    {
        $root = vfsStream::setup('root');

        return array(
            array(array('--dir' => vfsStream::url('root'), '--no-strict' => true, '--namespace' => 'Foo/BarBundle'), array('Foo\BarBundle', 'FooBarBundle', vfsStream::url('root/'), 'annotation', false, true)),
            array(array('--dir' => vfsStream::url('root'), '--no-strict' => true, '--namespace' => 'Foo/BarBundle', '--format' => 'yml', '--bundle-name' => 'BarBundle', '--structure' => true), array('Foo\BarBundle', 'BarBundle', vfsStream::url('root/'), 'yml', true, true)),
            array(array('--dir' => vfsStream::url('root'), '--namespace' => 'RedKiteCms/Theme/BarThemeBundle', '--format' => 'yml', '--bundle-name' => 'BarThemeBundle', '--structure' => true), array('RedKiteCms\Theme\BarThemeBundle', 'BarThemeBundle', vfsStream::url('root/'), 'yml', true, false)),
        );
    }

    protected function getCommand($generator, $input)
    {
        $command = $this
            ->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Command\Generate\GenerateAppThemeBundleCommand')
            ->setMethods(array('checkAutoloader', 'updateKernel'))
            ->getMock()
        ;

        $command->setContainer($this->getContainer());
        $command->setHelperSet($this->getHelperSet($input));
        $command->setGenerator($generator);

        return $command;
    }

    protected function getGenerator()
    {
        // get a noop generator
        return $this
            ->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlAppThemeGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generateExt'))
            ->getMock()
        ;
    }
}