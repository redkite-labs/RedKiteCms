<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Listener\Language\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Listener\Base\BaseListenerTest;

/**
 * DeleteLanguageBaseListenerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class DeleteLanguageBaseListenerTest extends BaseListenerTest
{
    protected $event;
    protected $testListener;
    protected $languageManager;
    protected $languageRepository;
    protected $manager;
    protected $objectModel;

    abstract protected function setUpObject();

    protected function setUp()
    {
        parent::setUp();

        $this->event = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Language\BeforeDeleteLanguageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Language\LanguageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\LanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageManager->expects($this->any())
            ->method('getLanguageRepository')
            ->will($this->returnValue($this->languageRepository));



        $this->manager->expects($this->any())
            ->method('set')
            ->will($this->returnSelf());
    }

    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeDeleteLanguageCommit($this->event);
    }

    public function testEventDoesNothingWhenAnyLanguageIsManaged()
    {
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->never())
            ->method('abort');

        $this->languageRepository->expects($this->never())
            ->method('startTransaction');

        $this->languageRepository->expects($this->never())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollBack');

        $this->languageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue(null));

        $this->testListener->onBeforeDeleteLanguageCommit($this->event);
    }

    public function testNothingIsAddedWhenAnyLanguageExists()
    {
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->never())
            ->method('abort');

        $this->languageRepository->expects($this->never())
            ->method('startTransaction');

        $this->languageRepository->expects($this->never())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollBack');

        $this->languageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->setUpLanguage(3)));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array()));

        $this->testListener->onBeforeDeleteLanguageCommit($this->event);
    }

    public function testSaveFailsWhenContentsAreNotSaved()
    {
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->once())
            ->method('abort');

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollBack');

        $this->languageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->setUpLanguage(3)));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array($this->setUpObject())));

        $this->manager->expects($this->any())
            ->method('delete')
            ->will($this->returnValue(false));

        $this->testListener->onBeforeDeleteLanguageCommit($this->event);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSaveFailsBecauseAndUnespectedExceptionIsThrown()
    {
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->once())
            ->method('abort');

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollBack');

        $this->languageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->setUpLanguage(3)));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array($this->setUpObject())));

        $this->manager->expects($this->any())
            ->method('delete')
            ->will($this->throwException(new \RuntimeException()));

        $this->testListener->onBeforeDeleteLanguageCommit($this->event);
    }

    public function testContentsHaveBeenDeleted()
    {
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->never())
            ->method('abort');

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollBack');

        $this->languageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->setUpLanguage(3)));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array($this->setUpObject())));

        $this->manager->expects($this->any())
            ->method('delete')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeDeleteLanguageCommit($this->event);
    }
}
