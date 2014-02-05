<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
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
          
namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\BootstrapBreadcrumb;

use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Tests\Unit\Core\Block\Menu\BaseBlockManagerMenu;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Breadcrumb\AlBlockManagerBootstrapBreadcrumbBlock;


/**
 * AlBlockManagerMenuVerticalTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapBreadcrumbBlockTest extends BaseBlockManagerMenu
{
    protected function setUp()
    {
        parent::setUp();
        
        $this->blocksTemplate = 'TwitterBootstrapBundle:Content:Breadcrumb/breadcrumb.html.twig';
    }
    
    protected function getBlockManager()
    {
        return new AlBlockManagerBootstrapBreadcrumbBlock($this->container, $this->validator);
    }
    
    public function testHtmlViewOutput()
    {
        $blockContent = 
            '{
                "0": {
                    "blockType" : "Link"
                },
                "1": {
                    "blockType" : "Link"
                }
            }';

        $this->initContainer();
        
        $seo = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlSeo');
        $seo->expects($this->once())
              ->method('getPermalink')
              ->will($this->returnValue('homepage'))
        ;
        
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree')
                        ->setMethods(array('getAlSeo'))
                        ->disableOriginalConstructor()
                        ->getMock();
        $pageTree->expects($this->at(0))
              ->method('getAlSeo')
              ->will($this->returnValue($seo))
        ;
        
        $this->container->expects($this->at(3))
                      ->method('get')
                      ->with('red_kite_cms.page_tree')
                      ->will($this->returnValue($pageTree))
        ;
        $block = $this->initBlock($blockContent);
        $blockManager = $this->getBlockManager();
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => $this->blocksTemplate,
            'options' => array(
                'items' => array (
                    array(
                        "blockType" => "Link"
                    ),
                    array(
                        "blockType" => "Link"
                    ),
                ),
                'blockOptions' => array(
                    'active_page' => 'homepage',
                    'wrapper_tag' => 'li',
                    'no_link_when_active' => true,
                ),
                'block_manager' => $blockManager
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
    }
}
