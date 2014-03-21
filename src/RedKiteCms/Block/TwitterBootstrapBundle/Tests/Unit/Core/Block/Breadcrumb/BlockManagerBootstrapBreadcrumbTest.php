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
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Breadcrumb\BlockManagerBootstrapBreadcrumbBlock;


/**
 * BlockManagerMenuVerticalTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapBreadcrumbBlockTest extends BaseBlockManagerMenu
{
    protected function setUp()
    {
        parent::setUp();
        
        $this->blocksTemplate = 'TwitterBootstrapBundle:Content:Breadcrumb/breadcrumb.html.twig';
    }
    
    protected function getBlockManager()
    {
        return new BlockManagerBootstrapBreadcrumbBlock($this->container, $this->validator);
    }
    
    public function linksProvider()
    {
        return array(
            array(
                array(
                    $this->initLinkBlock()
                ),
                null,
                '<ol class="breadcrumb"><li><a href="#">This is a link</a></li></ol>'
            ),
            array(
                array(
                    $this->initLinkBlock()
                ),
                'welcome-to-redkite-cms',
                '<ol class="breadcrumb"><li {% if path(\'welcome-to-redkite-cms\') == app.request.getBaseUrl ~ app.request.getPathInfo %}<li><span>This is a link</span></li>{% else %}<li><a href="#">This is a link</a></li>{% endif %}</ol>'
            ),
        );
    }
    
    public function testContentReplaced()
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
        
        $seo = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Seo');
        $seo->expects($this->once())
              ->method('getPermalink')
              ->will($this->returnValue('homepage'))
        ;
        
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree')
                        ->setMethods(array('getSeo'))
                        ->disableOriginalConstructor()
                        ->getMock();
        $pageTree->expects($this->at(0))
              ->method('getSeo')
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
        $blockManagerArray = $blockManager->toArray();
        
        $this->assertEquals($expectedResult, $blockManagerArray["Content"]);
    }
}