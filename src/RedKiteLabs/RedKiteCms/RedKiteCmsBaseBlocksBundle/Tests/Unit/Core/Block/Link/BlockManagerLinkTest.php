<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBaseBlocksBundle\Tests\Unit\Core\Block\Link;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\BlockManagerContainerBase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBaseBlocksBundle\Core\Block\Link\BlockManagerLink;

/**
 * BlockManagerLinkTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerLinkTest extends BlockManagerContainerBase
{
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->initContainer();
        $this->blockManager = new BlockManagerLink($this->container, $this->validator);
    }

    public function testDefaultValue()
    {
        $expectedValue = array('Content' =>
            '
                {
                    "0" : {
                        "href": "#",
                        "value": "This is a link"
                    }
                }
            '
        );
        
        
        $this->assertEquals($expectedValue, $this->blockManager->getDefaultValue());
    }

    public function testHtmlViewOutput()
    {
        $value =
        '
            {
                "0" : {
                    "href": "#",
                    "value": "This is a link"
                }
            }
        ';
        $block = $this->initBlock($value);
        $this->blockManager->set($block);        
        $expectedResult = array('RenderView' => array(
            'view' => 'RedKiteCmsBaseBlocksBundle:Content:Link/link.html.twig',
            'options' => array(
                'link' => array(
                    'href' => '#',
                    'value' => 'This is a link',
                ),
                'block_manager' => $this->blockManager
            ),
        ));
        
        $this->assertEquals($expectedResult, $this->blockManager->getHtml());
    }
    
    public function testEditorParameters()
    {
        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $this->factoryRepository->expects($this->at(0))
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));
        
        $seoRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface');
        $seoRepository->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue(array()))
        ;
        
        
        $this->factoryRepository->expects($this->at(1))
            ->method('createRepository')
            ->with('Seo')
            ->will($this->returnValue($seoRepository))
        ;
        
        $value =
        '
            {
                "0" : {
                    "src": "",
                    "data_src": "holder.js/260x180",
                    "title" : "Sample title",
                    "alt" : "Sample alt"
                }
            }
        ';

        $block = $this->initBlock($value);
        
        $formType = $this->getMock('Symfony\Component\Form\FormTypeInterface');
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('bootstrap_link.form')
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
        
        $this->container->expects($this->at(4))
                        ->method('get')
                        ->with('form.factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->once())
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue('en'))
        ;
        
        $this->container->expects($this->at(5))
                        ->method('get')
                        ->with('request')
                        ->will($this->returnValue($request))
        ;
        
        $this->initContainer();
        $blockManager = new BlockManagerLink($this->container, $this->validator);
        $blockManager->set($block);
        $blockManager->editorParameters();        
    }

    protected function initContainer()
    {
        parent::initContainer();
        
        $this->translator = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Translator\TranslatorInterface');
        $this->container
            ->expects($this->at(2))
            ->method('get')
            ->with('red_kite_cms.translator')
            ->will($this->returnValue($this->translator))
        ;
    }
    
    private function initBlock($value)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->once())
              ->method('getContent')
              ->will($this->returnValue($value));

        return $block;
    }
}
