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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block;

use Sensio\Bundle\GeneratorBundle\Tests\Command\GenerateCommandTest;
use AlphaLemon\AlphaLemonCmsBundle\Core\CommandsProcessor\AlCommandsProcessor;
use Symfony\Component\Console\Tester\CommandTester;
use org\bovigo\vfs\vfsStream;

/**
 * GenerateAppBlockBundleCommandTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class GenerateAppBlockBundleCommandTest extends GenerateCommandTest
{
    /**
     * @dataProvider getInteractiveCommandData
     */
    public function testInteractiveCommand($options, $input, $expected)
    {
        list($namespace, $bundle, $dir, $format, $structure, $description, $group, $strict) = $expected;

        $commandOptions = array(
            'description' => $description,
            'group' => $group,
            'strict' => $strict,
        );
        
        $generator = $this->getGenerator();        
        $generator
            ->expects($this->once())
            ->method('generateExt')
            ->with($namespace, $bundle, $dir, $format, $structure, $commandOptions)
        ;

        $tester = new CommandTester($this->getCommand($generator, $input));
        $tester->execute($options);
    }

    public function getInteractiveCommandData()
    {
        $root = vfsStream::setup('root');
        
        $options = array('--dir' => vfsStream::url('root'), '--description' => 'Fake block', '--group' => 'fake-group', '--strict' => false);
        return array(
            array($options, "Foo/BarBundle\n", array('Foo\BarBundle', 'FooBarBundle', vfsStream::url('root/'), 'annotation', false, 'Fake block', 'fake-group', false)),
            array($options, "Foo/BarBundle\nBarBundle\nfoo\nyml\nn", array('Foo\BarBundle', 'BarBundle', 'foo/', 'yml', false, 'Fake block', 'fake-group', false)),
            array(array('--dir' => vfsStream::url('root'), '--description' => 'Fake block', '--group' => 'fake-group', '--format' => 'yml', '--bundle-name' => 'BarBundle', '--structure' => true), "Foo/BarBundle\n", array('Foo\BarBundle', 'BarBundle', vfsStream::url('root').'/', 'yml', true, 'Fake block', 'fake-group', false)),
            array(array('--dir' => vfsStream::url('root'), '--description' => 'Fake block', '--group' => 'fake-group', '--strict' => true, '--format' => 'yml', '--bundle-name' => 'BarBundle', '--structure' => true), "AlphaLemon/Block/BarBundle\n", array('AlphaLemon\Block\BarBundle', 'BarBundle', vfsStream::url('root').'/', 'yml', true, 'Fake block', 'fake-group', true)),
        );
    }
    
    /**
     * @dataProvider getNonInteractiveCommandData
     */
    public function testNonInteractiveCommand($options, $expected)
    {
        list($namespace, $bundle, $dir, $format, $structure, $description, $group, $strict) = $expected;

        $commandOptions = array(
            'description' => $description,
            'group' => $group,
            'strict' => $strict,
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
            array(array('--dir' => vfsStream::url('root'), '--description' => 'Fake block', '--group' => 'fake-group', '--namespace' => 'Foo/BarBundle'), array('Foo\BarBundle', 'FooBarBundle', vfsStream::url('root/'), 'annotation', false, 'Fake block', 'fake-group', false)),
            array(array('--dir' => vfsStream::url('root'), '--description' => 'Fake block', '--group' => 'fake-group', '--namespace' => 'Foo/BarBundle', '--format' => 'yml', '--bundle-name' => 'BarBundle', '--structure' => true), array('Foo\BarBundle', 'BarBundle', vfsStream::url('root/'), 'yml', true, 'Fake block', 'fake-group', false)),
            array(array('--dir' => vfsStream::url('root'), '--description' => 'Fake block', '--group' => 'fake-group', '--strict' => true, '--namespace' => 'AlphaLemon/Block/BarBundle', '--format' => 'yml', '--bundle-name' => 'BarBundle', '--structure' => true), array('AlphaLemon\Block\BarBundle', 'BarBundle', vfsStream::url('root/'), 'yml', true, 'Fake block', 'fake-group', true)),
        );
    }
    
    protected function getCommand($generator, $input)
    {
        $command = $this
            ->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Command\Generate\GenerateAppBlockBundleCommand')
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
            ->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Generator\AlAppBlockGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generateExt'))
            ->getMock()
        ;
    }
}
