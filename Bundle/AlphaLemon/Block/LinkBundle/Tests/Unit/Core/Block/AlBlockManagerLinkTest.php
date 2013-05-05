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

namespace AlphaLemon\Block\LinkBundle\Tests\Unit\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use AlphaLemon\Block\LinkBundle\Core\Block\AlBlockManagerLink;

/**
 * AlBlockManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerLinkTest extends AlBlockManagerContainerBase
{
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->blockManager = new AlBlockManagerLink($this->container, $this->validator);
    }

    public function testDefaultValue()
    {
        $expectedValue = array('Content' =>
            '
                {
                    "0" : {
                        "href": "#",
                        "value": "Link"
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
                    "value": "Link"
                }
            }
        ';
        $block = $this->initBlock($value);
        $this->blockManager->set($block);        
        $expectedResult = array('RenderView' => array(
            'view' => 'LinkBundle:Content:link.html.twig',
            'options' => array(
                'link' => array(
                    'href' => '#',
                    'value' => 'Link',
                ),
                'block_manager' => $this->blockManager
            ),
        ));
        
        $this->assertEquals($expectedResult, $this->blockManager->getHtml());
    }
    
    public function testEditorParameters()
    {
        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->at(0))
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));
        
        $seoRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\SeoRepositoryInterface');
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
        $this->initContainer();
        
        $formType = $this->getMock('Symfony\Component\Form\FormTypeInterface');
        $this->container->expects($this->at(2))
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
        
        $this->container->expects($this->at(3))
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
        
        $this->container->expects($this->at(4))
                        ->method('get')
                        ->with('request')
                        ->will($this->returnValue($request))
        ;
        
        $blockManager = new AlBlockManagerLink($this->container, $this->validator);
        $blockManager->set($block);
        $blockManager->editorParameters();        
    }

    private function initBlock($value)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
              ->method('getContent')
              ->will($this->returnValue($value));

        return $block;
    }
}
