<?php
/*
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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Command;

use Symfony\Component\DependencyInjection\Container;
use Sensio\Bundle\GeneratorBundle\Tests\Command\GenerateCommandTest;
use Symfony\Component\Console\Tester\CommandTester;
use org\bovigo\vfs\vfsStream;

/**
 * GenerateAppThemeBundleCommandTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class GenerateTemplatesCommandTest extends GenerateCommandTest
{
    private $root;
    protected function setUp()
    {
        $this->root = vfsStream::setup('root', null, array('DependencyInjection' => array('Extension.php' => '')));
    }

    public function testATemplateNotPlacedOnThemeFolderDoesNotGenerateATemplateConfig()
    {
        $values = array(
            "base.html.twig" => array(
                "slots" => array
                (
                    "page_content" => array
                    (
                        "htmlContent" => "<p>Some content",
                    ),
                ),
                "generate_template" => false,
            ),
        );

        $themeName = 'FakeThemeBundle';
        $template = array_keys($values);
        $template = $template[0];
        $templateName = basename($template, '.html.twig');

        $templateParser = $this->getTemplateParser($values);
        $templateGenerator = $this->getTemplateGenerator();
        $templateGenerator
            ->expects($this->never())
            ->method('generateTemplate')
        ;

        $slotsGenerator = $this->getSlotsGenerator();
        $slotsGenerator
            ->expects($this->once())
            ->method('generateSlots')
            ->with(vfsStream::url('root/Resources/config/templates/slots'), null, $templateName, $values[$template]['slots'])
        ;

        $tester = new CommandTester($this->getCommand($templateParser, $templateGenerator, $slotsGenerator, ''));
        $tester->execute(array(array('theme' => $themeName)), array('interactive' => false));
    }

    public function testAnySlotFileIsGeneratedWhenSlotsAreNotDefined()
    {
        $values = array(
            "home.html.twig" => array(
                "assets" => array(
                    "external_stylesheets" => array(
                        "@BusinessWebsiteThemeBundle/Resources/public/css/reset.css",
                    ),
                ),
                "slots" => array(),
                "generate_template" => true,
            ),
        );

        $themeName = 'FakeThemeBundle';
        $template = array_keys($values);
        $template = $template[0];
        $templateName = basename($template, '.html.twig');

        $templateParser = $this->getTemplateParser($values);
        $templateGenerator = $this->getTemplateGenerator();
        $templateGenerator
            ->expects($this->once())
            ->method('generateTemplate')
            ->with(vfsStream::url('root/Resources/config/templates'), null, $templateName, $values[$template]['assets'])
        ;

        $slotsGenerator = $this->getSlotsGenerator();
        $slotsGenerator
            ->expects($this->never())
            ->method('generateSlots')
        ;

        $tester = new CommandTester($this->getCommand($templateParser, $templateGenerator, $slotsGenerator, ''));
        $tester->execute(array(array('theme' => $themeName)), array('interactive' => false));
    }

    public function testTemplateHasBeenGeneratedAndExtensionFileHasBeenUpdated()
    {
        $fakeCode = '{' . PHP_EOL;
        $fakeCode .= '        $loader->load(\'services.xml\');' . PHP_EOL;
        $fakeCode .= '}';
        file_put_contents(vfsStream::url('root/DependencyInjection/Extension.php'), $fakeCode);

        $this->generationTest();
        $this->assertEquals($this->getExpectedExtensionContent(), file_get_contents(vfsStream::url('root/DependencyInjection/Extension.php')));
    }

    public function testWhenTheExtensionFileHasAlreadyWritteCodeIsRegenerated()
    {
        file_put_contents(vfsStream::url('root/DependencyInjection/Extension.php'), $this->getExpectedExtensionContent());

        $this->generationTest();
        $this->assertEquals($this->getExpectedExtensionContent(), file_get_contents(vfsStream::url('root/DependencyInjection/Extension.php')));
    }

    private function getExpectedExtensionContent()
    {
        $content = '{' . PHP_EOL;
        $content .= '        $loader->load(\'services.xml\');' . PHP_EOL;
        $content .= PHP_EOL;
        $content .= '        // This code is autogenerated - DON\'T CHANGE IT MANUALLY' . PHP_EOL;
        $content .= PHP_EOL;
        $content .= '        $loader->load(\'.xml\');' . PHP_EOL;
        $content .= PHP_EOL;
        $content .= '        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . \'/../Resources/config/templates\'));' . PHP_EOL;
        $content .= '        $loader->load(\'home.xml\');' . PHP_EOL;
        $content .= PHP_EOL;
        $content .= '        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . \'/../Resources/config/templates/slots\'));' . PHP_EOL;
        $content .= '        $loader->load(\'home.xml\');' . PHP_EOL;
        $content .= PHP_EOL;
        $content .= '        // This code is autogenerated - DON\'T CHANGE IT MANUALLY' . PHP_EOL;
        $content .= '}';

        return $content;
    }

    protected function generationTest()
    {
        $values = array(
            "home.html.twig" => array(
                "assets" => array(
                    "external_stylesheets" => array(
                        "@BusinessWebsiteThemeBundle/Resources/public/css/reset.css",
                    ),
                ),
                "slots" => array
                (
                    "page_content" => array
                    (
                        "htmlContent" => "<p>Some content",
                    ),
                ),
                "generate_template" => true,
            ),
        );

        $themeName = 'FakeThemeBundle';
        $template = array_keys($values);
        $template = $template[0];
        $templateName = basename($template, '.html.twig');

        $templateParser = $this->getTemplateParser($values);
        $templateGenerator = $this->getTemplateGenerator();
        $templateGenerator
            ->expects($this->once())
            ->method('generateTemplate')
            ->with(vfsStream::url('root/Resources/config/templates'), null, $templateName, $values[$template]['assets'])
        ;

        $slotsGenerator = $this->getSlotsGenerator();
        $slotsGenerator
            ->expects($this->once())
            ->method('generateSlots')
            ->with(vfsStream::url('root/Resources/config/templates/slots'), null, $templateName, $values[$template]['slots'])
        ;

        $tester = new CommandTester($this->getCommand($templateParser, $templateGenerator, $slotsGenerator, ''));
        $tester->execute(array(array('theme' => $themeName)), array('interactive' => false));
    }

    protected function getCommand($templateParser, $templateGenerator, $slotsGenerator, $input)
    {
        $command = $this
            ->getMockBuilder('AlphaLemon\ThemeEngineBundle\Command\Generate\GenerateTemplatesCommand')
            ->setMethods(array('checkAutoloader', 'updateKernel'))
            ->getMock()
        ;

        $command->setContainer($this->getContainer());
        $command->setHelperSet($this->getHelperSet($input));
        $command->setTemplateParser($templateParser);
        $command->setTemplateGenerator($templateGenerator);
        $command->setSlotsGenerator($slotsGenerator);

        return $command;
    }

    protected function getContainer()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $kernel
            ->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root/')))
        ;

        $container = new Container();
        $container->set('kernel', $kernel);

        return $container;
    }

    protected function getTemplateParser($values)
    {
        $templateParser = $this
            ->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Generator\TemplateParser\AlTemplateParser')
            ->disableOriginalConstructor()
            ->setMethods(array('parse'))
            ->getMock()
        ;
        $templateParser
            ->expects($this->once())
            ->method('parse')
            ->will($this->returnValue($values))
        ;

        return $templateParser;
    }

    protected function getTemplateGenerator()
    {
        return $this
            ->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Generator\AlTemplateGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generateTemplate'))
            ->getMock()
        ;
    }

    protected function getSlotsGenerator()
    {
        return $this
            ->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Generator\AlSlotsGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generateSlots'))
            ->getMock()
        ;
    }
}
