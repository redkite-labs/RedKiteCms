<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\Seo;

use RedKiteLabs\RedKiteCmsBundle\Core\Listener\Seo\UpdatePermalinkOnBlocksListener;
use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\Base\BaseListenerTest;

/**
 * UpdatePermalinkOnBlocksListenerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class UpdatePermalinkOnBlocksListenerTest extends BaseListenerTest
{
    private $event;
    private $testListener;
    private $blockRepository;
    private $blockManagerFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->event = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Seo\BeforeEditSeoCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->blockManager = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage')
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->blockManagerFactory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');

        $this->testListener = new UpdatePermalinkOnBlocksListener($this->factoryRepository ,$this->blockManagerFactory);
    }

    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->never())
            ->method('abort');

        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeEditSeoCommit($this->event);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValuesParamIsNotAnArray()
    {
        $this->event->expects($this->never())
            ->method('abort');

        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue('fake'));

        $this->testListener->onBeforeEditSeoCommit($this->event);
    }

    public function testAnythingIsMadeWhenMandatoryValueMisses()
    {
        $this->event->expects($this->never())
            ->method('abort');

        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array('Permalink' => 'a new permalink')));

        $this->blockRepository->expects($this->never())
            ->method('startTransaction');

        $this->blockRepository->expects($this->never())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollBack');

        $this->testListener->onBeforeEditSeoCommit($this->event);
    }

    public function testAnythingIsMadeWhenTheOldPermalinkHasNotBeenFound()
    {
        $this->event->expects($this->never())
            ->method('abort');

        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array('oldPermalink' => 'the-old-permalink', 'Permalink' => 'a new permalink')));

        $this->blockRepository->expects($this->once())
            ->method('fromContent')
            ->will($this->returnValue(array()));

        $this->blockRepository->expects($this->never())
            ->method('startTransaction');

        $this->blockRepository->expects($this->never())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollBack');

        $this->testListener->onBeforeEditSeoCommit($this->event);
    }

    public function testPermalinksHasNotBeenUpdatedWhenSaveOperationFails()
    {
        $this->event->expects($this->once())
            ->method('abort');

        $this->blockRepository->expects($this->once())
            ->method('fromContent')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockManager->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));

        $this->blockManagerFactory->expects($this->once())
            ->method('createBlockManager')
            ->will($this->returnValue($this->blockManager));

        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array('oldPermalink' => 'the-old-permalink', 'Permalink' => 'a new permalink')));

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->never())
            ->method('commit');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $this->testListener->onBeforeEditSeoCommit($this->event);
    }

    public function testPermalinksHasNotBeenUpdatedWhenOnceSaveOperationFails()
    {
        $this->event->expects($this->once())
            ->method('abort');

        $this->blockRepository->expects($this->once())
            ->method('fromContent')
            ->will($this->returnValue(array($this->setUpBlock(), $this->setUpBlock(), $this->setUpBlock())));

        $this->blockManager->expects($this->exactly(2))
            ->method('save')
            ->will($this->onConsecutiveCalls(true, false, true));

        $this->blockManagerFactory->expects($this->exactly(2))
            ->method('createBlockManager')
            ->will($this->returnValue($this->blockManager));

        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array('oldPermalink' => 'the-old-permalink', 'Permalink' => 'a new permalink')));

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->never())
            ->method('commit');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $this->testListener->onBeforeEditSeoCommit($this->event);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testPermalinksHasNotBeenUpdatedWhenAnUnespectedExceptionIsThrown1()
    {
        $this->event->expects($this->once())
            ->method('abort');

        $this->blockRepository->expects($this->once())
            ->method('fromContent')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockManager->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException));

        $this->blockManagerFactory->expects($this->once())
            ->method('createBlockManager')
            ->will($this->returnValue($this->blockManager));

        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array('oldPermalink' => 'the-old-permalink', 'Permalink' => 'a new permalink')));

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->never())
            ->method('commit');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $this->testListener->onBeforeEditSeoCommit($this->event);
    }

    public function testPermalinkIsUpdated()
    {
        $this->event->expects($this->never())
            ->method('abort');

        $this->blockRepository->expects($this->once())
            ->method('fromContent')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollBack');

        $this->blockManager->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $this->blockManagerFactory->expects($this->once())
            ->method('createBlockManager')
            ->will($this->returnValue($this->blockManager));

        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array('oldPermalink' => 'the-old-permalink', 'Permalink' => 'a new permalink')));

        $this->testListener->onBeforeEditSeoCommit($this->event);
    }

    public function testUpdateMoreBlocks()
    {
        $this->event->expects($this->never())
            ->method('abort');

        $this->blockRepository->expects($this->once())
            ->method('fromContent')
            ->will($this->returnValue(array($this->setUpBlock(), $this->setUpBlock(), $this->setUpBlock())));

        $this->blockManager->expects($this->exactly(3))
            ->method('save')
            ->will($this->returnValue(true));

        $this->blockManagerFactory->expects($this->exactly(3))
            ->method('createBlockManager')
            ->will($this->returnValue($this->blockManager));

        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array('oldPermalink' => 'the-old-permalink', 'Permalink' => 'a new permalink')));

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollBack');

        $this->testListener->onBeforeEditSeoCommit($this->event);
    }

    private function setUpBlock()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue(array("Id" => 2, "Type" => "Text")));

        return $block;
    }
}
