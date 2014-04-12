<?php
/*
 * This file is part of the TwitterBootstrapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 * 
 * @license    MIT LICENSE
 * 
 */

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Thumbnail;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Base\BaseTestBlock;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Thumbnail\BlockManagerBootstrapThumbnailBlock;

/**
 * BlockManagerBootstrapThumbnailBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapThumbnailBlockTest extends BaseTestBlock
{  
    /**
     * @dataProvider bootstrapVersionsProvider
     */
    public function testDefaultValue($bootstrapVersion, $columnValue)
    {
        $expectedValue = array(
            "Content" =>    '
            {
                "0" : {
                    "width": "' . $columnValue . '"
                }
            }'
        );
            
        $this->initContainer(); 
        $this->initBootstrapversion($bootstrapVersion);
        $blockManager = new BlockManagerBootstrapThumbnailBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    public function bootstrapVersionsProvider()
    {
        return array(
            array(
                "2.x",
                "span3",
            ),
            array(
                "3.x",
                "col-md-5",
            ),
        );
    }
    
    /**
     * @dataProvider bootstrapVersionsProvider
     */
    public function testEditorParameters($bootstrapVersion, $columnValue)
    {
        $value = '
            {
                "0" : {
                    "width": "' . $columnValue . '"
                }
            }';
        
        $block = $this->initBlock($value);
        $this->initContainer();
        $this->initBootstrapversion($bootstrapVersion);
        
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock();
        $form->expects($this->once())
            ->method('createView')
            ->will($this->returnValue('the-form'))
        ;

        $formFactory = $this->getMockBuilder('RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Factory\BootstrapFormFactory')
                    ->disableOriginalConstructor()
                    ->getMock();
        $formFactory->expects($this->once())
                    ->method('createForm')
                    ->with('Thumbnail', 'ThumbnailType')
                    ->will($this->returnValue($form))
        ;
        $this->container->expects($this->at(4))
                        ->method('get')
                        ->with('twitter_bootstrap.bootstrap_form_factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $blockManager = new BlockManagerBootstrapThumbnailBlock($this->container, $this->validator);
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals('TwitterBootstrapBundle:Editor:Thumbnail/editor.html.twig', $result["template"]);
    }

    /**
     * @dataProvider bootstrapVersionsProvider
     */
    public function testGetHtml($bootstrapVersion, $columnValue)
    {
        $value = '
        {
            "0" : {
                "width": "' . $columnValue . '"
            }
        }';
            
        $block = $this->initBlock($value);
        $this->initContainer();
        $this->initBootstrapversion($bootstrapVersion);
        
        $blockManager = new BlockManagerBootstrapThumbnailBlock($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Thumbnail/' . $bootstrapVersion .'/thumbnail.html.twig',
            'options' => array(
                'thumbnail' => array(
                    "width" => $columnValue,
                ),
                'block_manager' => $blockManager,
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
    }
    
    /**
     * @dataProvider bootstrapVersionsProvider
     */
    public function testIsInternalBlock($bootstrapVersion)
    {
        
        $this->initBootstrapversion($bootstrapVersion);
        $blockManager = new BlockManagerBootstrapThumbnailBlock($this->container, $this->validator);
        $this->assertTrue($blockManager->getIsInternalBlock());
    }
}