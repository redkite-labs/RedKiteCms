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

namespace AlphaLemon\Block\FileBundle\Tests\Unit\Core\Block;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use AlphaLemon\Block\FileBundle\Core\Block\AlBlockManagerFile;

/**
 * AlBlockManagerFileTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerFileTest extends AlBlockManagerContainerBase
{
    protected $translator;
    protected $configuration;
    
    public function testDefaultValue()
    {
        $value =
        '{
            "0" : {
                "file" : "Click to load a file",
                "description" : "",
                "opened" : false
            }
        }';

        $expectedValue = array(
            'Content' => $value,
        );

        $this->initContainer();
        $this->translate("Click to load a file");
        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }

    public function testGetHideInEditMode()
    {
        $this->initContainer();
        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $this->assertTrue($blockManager->getHideInEditMode());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\Exception\InvalidJsonFormatException
     * @expectedExceptionMessage The content format %blockTypeInfo%is wrong. You should remove that block and add it again.
     */
    public function testAnExceptionIsThrownWhenTheJsonIsMalformed()
    {
        $value =
        '{
            "0" : {
                "file" : "files/my-file",
                "description" : "",
                "opened" : true,
            }
        }';

        $block = $this->initBlock($value);
        $this->initContainer();
        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $blockManager->set($block);
        $blockManager->getHtml();
    }

    public function testGetHtmlWhenOpenedIsFalse()
    {
        $value =
        '{
            "0" : {
                "file" : "files/my-file",
                "description" : "",
                "opened" : false
            }
        }';

        $block = $this->initBlock($value);
        $this->initContainer();        
        $this->initKernel(); 
        $this->initDeployBundle();
        
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->container->expects($this->at(5))
                        ->method('get')
                        ->with('request')
                        ->will($this->returnValue($request));
        
        $this->container->expects($this->at(6))
                        ->method('getParameter')
                        ->with('alpha_lemon_cms.upload_assets_dir')
                        ->will($this->returnValue('uploads/assets'));
        
        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $blockManager->set($block);
        $this->assertEquals('<a href="/uploads/assets/files/my-file" />my-file</a>', $blockManager->getHtml());
    }

    public function testGetHtmlWhenOpenedIsTrue()
    {
        $value =
        '{
            "0" : {
                "file" : "files/my-file",
                "description" : "",
                "opened" : "1"
            }
        }';
        
        $block = $this->initBlock($value);
        $this->initContainer();        
        $this->initKernel();        
        $this->initDeployBundle();
        
        $this->container->expects($this->at(5))
                        ->method('getParameter')
                        ->with('alpha_lemon_cms.web_folder')
                        ->will($this->returnValue('web'));

        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $blockManager->set($block);
        $this->assertEquals('{% set file = kernel_root_dir ~ \'/../web/bundles/acmewebsite/files/my-file\' %} {{ file_open(file) }}', $blockManager->getHtml());
    }

    public function contentReplacedProvider()
    {
        return array(
            array(
                '{
                    "0" : {
                        "file" : "files/my-file",
                        "description" : "",
                        "opened" : "0"
                    }
                }',
                array(                    
                    "RenderView" => array (
                        "view" => "FileBundle:Content:file.html.twig",
                        "options" => array (
                                "webfolder" => "",
                                "folder" => "",
                                "filename" => "files/my-file",
                                'displayValue' => 'files/my-file',
                            )

                    ),
                ),
            ),
            array(
                '{
                    "0" : {
                        "file" : "files/my-file",
                        "description" : "",
                        "opened" : "1"
                    }
                }',
                array(                    
                    "RenderView" => array (
                        "view" => "FileBundle:Content:file.html.twig",
                        "options" => array (
                                "webfolder" => "",
                                "folder" => "",
                                "filename" => "files/my-file",
                            )

                    ),
                ),
            ),
        );
    }
    
    /**
     * @dataProvider contentReplacedProvider
     */
    public function testContentReplaced($blockContent, $expectedResult)
    {
        $block = $this->initBlock($blockContent);
        $this->initContainer();
        
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->container
            ->expects($this->at(4))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request))
        ;
        
        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $blockManager->set($block);
        $blockManagerArray = $blockManager->toArray();
                
        $this->assertEquals($expectedResult, $blockManagerArray['Content']);
    }
    
    public function testEditorParameters()
    {
        $value =
        '{
            "0" : {
                "file" : "files/my-file",
                "description" : "",
                "opened" : "0"
            }
        }';

        $block = $this->initBlock($value);
        $this->initContainer();
        
        $formType = $this->getMock('Symfony\Component\Form\FormTypeInterface');
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('file.form')
                        ->will($this->returnValue($formType))
        ;
        
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock();
        $form->expects($this->once())
            ->method('createView')
        ;
        
        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $formFactory->expects($this->once())
                    ->method('create')
                    ->will($this->returnValue($form))
        ;
        
        $this->container
            ->expects($this->at(4))
            ->method('get')
            ->with('form.factory')
            ->will($this->returnValue($formFactory))
        ;
        
        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $blockManager->set($block);
        $blockManager->editorParameters();        
    }
    
    private function initBlock($value)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->once())
              ->method('getContent')
              ->will($this->returnValue($value));

        return $block;
    }

    protected function initKernel()
    {
        $this->container
            ->expects($this->at(3))
            ->method('get')
            ->with('kernel')
            ->will($this->returnValue($this->kernel))
        ;
    }
    
    protected function initContainer()
    {
        parent::initContainer();
        
        $this->translator = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslatorInterface');
        $this->container
            ->expects($this->at(2))
            ->method('get')
            ->with('alpha_lemon_cms.translator')
            ->will($this->returnValue($this->translator))
        ;
    }
    
    private function initDeployBundle()
    {
        $this->container->expects($this->at(4))
                        ->method('getParameter')
                        ->with('red_kite_labs_theme_engine.deploy_bundle')                
                        ->will($this->returnValue('AcmeWebsiteBundle'));
    }
    
    private function translate($message)
    {
        $this->translator
            ->expects($this->once())
            ->method('translate')
            ->with($message)                
            ->will($this->returnValue($message));
    }
}
