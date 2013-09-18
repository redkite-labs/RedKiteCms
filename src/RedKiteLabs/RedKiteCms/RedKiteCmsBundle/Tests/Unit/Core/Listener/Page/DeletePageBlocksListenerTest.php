<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\Page;

use RedKiteLabs\RedKiteCmsBundle\Core\Listener\Page\DeletePageBlocksListener;
use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\Base\BaseListenerTest;

/**
 * DeletePageBlocksListenerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class DeletePageBlocksListenerTest extends BaseListenerTest
{
    protected $event;
    protected $testListener;
    protected $pageManager;
    protected $templateManager;
    protected $pageContentsContainer;
    protected $pageRepository;
    protected $languageRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->event = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Page\BeforeDeletePageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Page\AlPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();


        $this->pageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->blocksRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->at(0))
            ->method('createRepository')
            ->with('Language')
            ->will($this->returnValue($this->languageRepository));
        
         $this->factoryRepository->expects($this->at(1))
            ->method('createRepository')
            ->with('Block')
            ->will($this->returnValue($this->blocksRepository));

        $this->testListener = new DeletePageBlocksListener($this->factoryRepository);
    }

    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeDeletePageCommit($this->event);
    }

    public function testDeleteFailsWhenAnyLanguageExists()
    {
        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array()));

        $this->pageRepository->expects($this->never())
            ->method('startTransaction');

        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));

        $this->event->expects($this->never())
            ->method('abort');

        $this->pageManager->expects($this->once())
            ->method('getPageRepository')
            ->will($this->returnValue($this->pageRepository));

        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    /**
     * @expectedException \PropelException
     */
    public function testDeleteWithOneLanguageFailsDueToUnespectedException()
    {
        $page = $this->setUpPage(2);
        $language = $this->setUpLanguage(2);

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));
        
        $this->blocksRepository->expects($this->once())
            ->method('deleteBlocks')
            ->will($this->throwException(new \PropelException()))
        ;

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->never())
            ->method('commit');

        $this->pageRepository->expects($this->once())
            ->method('rollback');

        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));

        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));

        $this->pageManager->expects($this->once())
            ->method('getPageRepository')
            ->will($this->returnValue($this->pageRepository));

        $this->testListener->onBeforeDeletePageCommit($this->event);
    }

    public function testDeleteWithOneLanguage()
    {
        $page = $this->setUpPage(2);
        $language = $this->setUpLanguage(2);

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));
        
        $this->blocksRepository->expects($this->once())
            ->method('deleteBlocks')
            ->with(2, 2);

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));

        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));

        $this->pageManager->expects($this->once())
            ->method('getPageRepository')
            ->will($this->returnValue($this->pageRepository));

        $this->testListener->onBeforeDeletePageCommit($this->event);
    }

    public function testDeleteWithMoreLanguages()
    {
        $page = $this->setUpPage(2);
        $language1 = $this->setUpLanguage(2);
        $language2  = $this->setUpLanguage(3);

        // Orm
        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        // Event
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language1, $language2)));

        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));

        $this->pageManager->expects($this->once())
            ->method('getPageRepository')
            ->will($this->returnValue($this->pageRepository));

        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
}
