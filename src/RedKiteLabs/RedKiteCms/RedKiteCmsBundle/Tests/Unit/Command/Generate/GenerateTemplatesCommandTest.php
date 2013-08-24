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

use Symfony\Component\DependencyInjection\Container;
use Sensio\Bundle\GeneratorBundle\Tests\Command\GenerateCommandTest;
use Symfony\Component\Console\Tester\CommandTester;
use org\bovigo\vfs\vfsStream;

/**
 * GenerateAppThemeBundleCommandTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
                "generate_slot" => false, // this option does not belong the values array in the real world and it is used only for test pourpose
            ),
        );
        $this->generationTest($values);
    }

    /**
     * @dataProvider templatesProvider 
     */
    public function testTemplatesGeneration($values, $slotPattern)
    {
        $fakeCode = '{' . PHP_EOL;
        $fakeCode .= '        $loader->load(\'services.xml\');' . PHP_EOL;
        $fakeCode .= '}';
        file_put_contents(vfsStream::url('root/DependencyInjection/Extension.php'), $fakeCode);

        $this->generationTest($values);
        $extensionContents = file_get_contents(vfsStream::url('root/DependencyInjection/Extension.php'));
        
        $templatePattern = "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config\/templates',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'home.xml',\n[\s]+\),/";
        $this->assertRegExp($templatePattern, $extensionContents);
        $this->assertRegExp($slotPattern, $extensionContents);
    }

    public function testWhenTheExtensionFileHasAlreadyWrittenCodeIsRegenerated()
    {
        file_put_contents(vfsStream::url('root/DependencyInjection/Extension.php'), 'Extension content');

        $this->generationTest();
        $extensionContents = file_get_contents(vfsStream::url('root/DependencyInjection/Extension.php'));
        $pattern = "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config\/templates',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'home.xml',\n[\s]+\),/";
        $this->assertRegExp($pattern, $extensionContents);
    }
    
    public function templatesProvider()
    {
        return array(
            array(
                array(
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
                ),
                "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config\/templates\/slots',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'home.xml',\n[\s]+\),/",
            ),
            array(
                array(
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
                    "base.html.twig" => array(
                        "slots" => array
                        (
                            "logo" => array
                            (
                                "htmlContent" => "<p>Some content",
                            ),
                        ),
                        "generate_template" => false,
                    ),
                ),
                "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config\/templates\/slots',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'home.xml',\n[\s]+'base.xml',\n[\s]+\),/",
            ),
            array(
                array(
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
                    "base.html.twig" => array(
                        "slots" => array
                        (
                            "logo" => array
                            (
                                "htmlContent" => "<p>Some content",
                            ),
                        ),
                        "generate_template" => false,
                    ),
                    "template.html.twig" => array(
                        "slots" => array
                        (
                            "logo" => array
                            (
                                "htmlContent" => "<p>Some content",
                            ),
                        ),
                        "generate_template" => false,
                        "generate_slot" => false, // this option does not belong the values array in the real world and it is used only for test pourpose
                    ),
                ),
                "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config\/templates\/slots',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'home.xml',\n[\s]+'base.xml',\n[\s]+\),/"
            ),
        );
    }
    
    protected function generationTest($values = null)
    {
        if (null === $values) {
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
        }   

        $slotsGenerator = $this->getSlotsGenerator();
        $themeName = 'FakeThemeBundle';
        
        $c = 0;
        foreach($values as $template => $value) {
            $templateName = basename($template, '.html.twig');
            
            if ($value["generate_template"]) {
                $templateParser = $this->getTemplateParser($values);
                $templateGenerator = $this->getTemplateGenerator();
                $templateGenerator
                    ->expects($this->once())
                    ->method('generateTemplate')
                    ->with(vfsStream::url('root/Resources/config/templates'), null, $templateName, $values[$template]['assets'])
                ;
            }
            
            if (!array_key_exists("generate_slot", $value) || $value["generate_slot"] == true ) {
                $slotsGenerator
                    ->expects($this->at($c))
                    ->method('generateSlots')
                    ->with(vfsStream::url('root/Resources/config/templates/slots'), null, $templateName, $values[$template]['slots'])
                ;
                $c++;
            }
        }

        $tester = new CommandTester($this->getCommand($templateParser, $templateGenerator, $slotsGenerator, ''));
        $tester->execute(array(array('theme' => $themeName)), array('interactive' => false));
    }

    protected function getCommand($templateParser, $templateGenerator, $slotsGenerator, $input)
    {
        $command = $this
            ->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Command\Generate\GenerateTemplatesCommand')
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
        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        
        $kernel
            ->expects($this->once())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root/')))
        ;
        
        $kernel
            ->expects($this->once())
            ->method('getBundle')
            ->will($this->returnValue($bundle))
        ;
        
        $bundle
            ->expects($this->once())
            ->method('getNamespace')
            ->will($this->returnValue('\bundle\namespace'))
        ;

        $container = new Container();
        $container->set('kernel', $kernel);

        return $container;
    }

    protected function getTemplateParser($values)
    {
        $templateParser = $this
            ->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Generator\TemplateParser\AlTemplateParser')
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
            ->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlTemplateGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generateTemplate'))
            ->getMock()
        ;
    }

    protected function getSlotsGenerator()
    {
        return $this
            ->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlSlotsGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generateSlots'))
            ->getMock()
        ;
    }
}
