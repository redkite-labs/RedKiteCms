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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBaseBlocksBundle\Tests\Unit\Core\Block\Menu;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\BlockManagerContainerBase;

/**
 * BaseBlockManagerMenu
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class BaseBlockManagerMenu extends BlockManagerContainerBase
{
    protected $blocksTemplate;
    
    abstract protected function getBlockManager();
            
    public function testDefaultValue()
    {
        $expectedValue = array(
            'Content' => '
            {
                "0": {
                    "blockType" : "Link"
                },
                "1": {
                    "blockType" : "Link"
                }
            }'
        );

        $this->initContainer();
        $blockManager = $this->getBlockManager(); 
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    /**
     * @dataProvider linksProvider
     */
    public function testGetHtml($links, $productionRoute, $html)
    {
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
        
        $blocksRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface');
        $blocksRepository->expects($this->once())
              ->method('retrieveContentsBySlotName')
              ->will($this->returnValue($links))
        ;
        
        $factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $factoryRepository->expects($this->once())
              ->method('createRepository')
              ->with('Block')
              ->will($this->returnValue($blocksRepository))
        ;
        
        $this->container->expects($this->at(4))
                      ->method('get')
                      ->with('red_kite_cms.factory_repository')
                      ->will($this->returnValue($factoryRepository))
        ;
        
        $urlManager = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager\UrlManagerInterface');
        $urlManager->expects($this->once())
              ->method('getProductionRoute')
              ->will($this->returnValue($productionRoute))
        ;
        $this->container->expects($this->at(5))
                      ->method('get')
                      ->with('red_kite_cms.url_manager')
                      ->will($this->returnValue($urlManager))
        ;
        
        $blockContent = 
            '{
                "0": {
                    "blockType" : "Link"
                },
                "1": {
                    "blockType" : "Link"
                }
            }';
        $block = $this->initBlock($blockContent);
        $blockManager = $this->getBlockManager();
        $blockManager->set($block);
        
        $this->assertEquals($html, $blockManager->getHtml());
    }
    
    public function linksProvider()
    {
        return array(
            array(
                array(
                    $this->initLinkBlock()
                ),
                null,
                '<ol class="nav nav-pills"><li ><a href="#">This is a link</a></li></ol>'
            ),
            array(
                array(
                    $this->initLinkBlock()
                ),
                'welcome-to-redkite-cms',
                '<ol class="nav nav-pills"><li {% if path(\'welcome-to-redkite-cms\') == app.request.getBaseUrl ~ app.request.getPathInfo %}class="active"{% endif %}><a href="#">This is a link</a></li></ol>'
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
                ),
                'block_manager' => $blockManager
            ),
        )); 
        $blockManagerArray = $blockManager->toArray();
        
        $this->assertEquals($expectedResult, $blockManagerArray["Content"]);
    }
    
    protected function initContainer()
    {
        $repository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $repository->expects($this->at(0))
              ->method('createRepository')
              ->with('Block')
        ;
        
        $this->container->expects($this->at(0))
                        ->method('get')
                        ->with('red_kite_cms.events_handler')
                        ->will($this->returnValue($this->eventsHandler))
        ;
        
        $this->container->expects($this->at(1))
                      ->method('get')
                      ->will($this->returnValue($repository))
        ;
    }
    
    protected function initBlockManager($block)
    {
        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\BlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block)
                ->will($this->returnSelf());
        
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));
        
        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));
        
        $this->initContainer();
        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockEditingEvent');
        $this->setUpEventsHandler($event, 2);
    }
    
    protected function initBlock($value)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
              ->method('getContent')
              ->will($this->returnValue($value));
        
        $block->expects($this->any())
              ->method('getId')
              ->will($this->returnValue(2));

        return $block;
    }
    
    protected function initLinkBlock()
    {
        $linkBlockContent =
            '{
                "0" : {
                    "href": "#",
                    "value": "This is a link"
                }
            }';        
        
        return $this->initBlock($linkBlockContent);
    }
}
