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

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\TinyMceBlock\AlBlockManagerTinyMce;

/**
 * AlBlockManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerTinyMceTest extends AlContentManagerBase
{
    private $dispatcher;
    private $blockManager;

    protected function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->urlManager = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface');

        $this->blockManager = new AlBlockManagerTinyMceTester($this->eventsHandler, $this->urlManager, $this->factoryRepository, $this->validator);
    }

    public function testUrlManagerIsNotCalledWhenContentKeyDeosNotExist()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $block->expects($this->any())
                ->method('getExternalJavascript')
                ->will($this->returnValue('changed external javascript content'));

        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent');
        $this->setUpEventsHandler($event, 2);

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));

         $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block);

         $this->urlManager->expects($this->never())
            ->method('fromUrl');

         $this->urlManager->expects($this->never())
            ->method('getInternalUrl');

        $params = array('ExternalJavascript' => 'changed external javascript content');
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(true, $result);
    }

    public function testTheLinkHasNotBeenConvertedAndLeftAsFound()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $block->expects($this->once())
                ->method('getContent')
                ->will($this->returnValue('saved html content <a href="http://example.com">page</a>'));

        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent');
        $this->setUpEventsHandler($event, 2);

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block);

        $this->urlManager->expects($this->any())
            ->method('fromUrl')
            ->with($this->equalTo('http://example.com'))
            ->will($this->returnSelf());

         $this->urlManager->expects($this->once())
            ->method('getInternalUrl')
            ->will($this->returnValue(null));

        $params = array('Content' => 'saved html content <a href="http://example.com">page</a>');
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(true, $result);
        $this->assertEquals('saved html content <a href="http://example.com">page</a>', $this->blockManager->get()->getContent());
    }

    public function testTheLinkHasBeenConverted()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $block->expects($this->once())
                ->method('getContent')
                ->will($this->returnValue('saved html content <a href="/alcms.php/backend/my-awesome-permalink">page</a>'));

        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent');
        $this->setUpEventsHandler($event, 2);

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block);

        $this->urlManager->expects($this->any())
            ->method('fromUrl')
            ->with($this->equalTo('my-awesome-permalink'))
            ->will($this->returnSelf());

         $this->urlManager->expects($this->once())
            ->method('getInternalUrl')
            ->will($this->returnValue('/alcms.php/backend/my-awesome-permalink'));

        $params = array('Content' => 'saved html content <a href="my-awesome-permalink">page</a>');
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(true, $result);
        $this->assertEquals('saved html content <a href="/alcms.php/backend/my-awesome-permalink">page</a>', $this->blockManager->get()->getContent());
    }
}

class AlBlockManagerTinyMceTester extends AlBlockManagerTinyMce
{
    public function getDefaultValue()
    {
        return array("Content" => "Test value");
    }
}
