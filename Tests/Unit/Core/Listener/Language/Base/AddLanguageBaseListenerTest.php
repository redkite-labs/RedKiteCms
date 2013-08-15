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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\Language\Base;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\Base\BaseListenerTest;

/**
 * AddLanguageBaseListenerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class AddLanguageBaseListenerTest extends BaseListenerTest
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

        $this->event = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Language\AlLanguageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager->expects($this->any())
            ->method('set')
            ->will($this->returnSelf());
    }

    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeAddLanguageCommit($this->event);
    }

    public function testEventIsAbortetWhenMainLanguageDoesNotExist()
    {
        $this->setUpLanguageManager();
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->once())
            ->method('abort');

        $this->languageRepository->expects($this->never())
            ->method('startTransaction');

        $this->languageRepository->expects($this->never())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollBack');

        $this->languageRepository->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue(null));

        $this->testListener->onBeforeAddLanguageCommit($this->event);
    }

    public function testNothingIsAddedWhenAnyLanguageExists()
    {
        $this->setUpLanguageManager();
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

        $this->languageRepository->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($this->setUpLanguage(3)));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array()));

        $this->testListener->onBeforeAddLanguageCommit($this->event);
    }

    public function testSaveFailsWhenDbRecorsAreNotSaved()
    {
        $this->setUpLanguageManager();
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->once())
            ->method('abort');

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollBack');

        $this->languageRepository->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($this->setUpLanguage(3)));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array($this->setUpObject())));

        $this->manager->expects($this->any())
            ->method('save')
            ->will($this->returnValue(false));

        $this->testListener->onBeforeAddLanguageCommit($this->event);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSaveFailsBecauseAndUnespectedExceptionIsThrown()
    {
        $this->setUpLanguageManager();
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->once())
            ->method('abort');

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollBack');

        $this->languageRepository->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($this->setUpLanguage(3)));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array($this->setUpObject())));

        $this->manager->expects($this->any())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));

        $this->testListener->onBeforeAddLanguageCommit($this->event);
    }

    public function testDbRecorsHaveBeenCopied()
    {
        $this->setUpLanguageManager();
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

        $this->languageRepository->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($this->setUpLanguage(3)));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array($this->setUpObject())));

        $this->manager->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeAddLanguageCommit($this->event);
    }

    public function testDbRecorsHaveBeenCopiedFromMainLanguage()
    {
        $this->setUpLanguageManager();
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

        $mainLanguage = $this->setUpLanguage(3);
        $this->languageRepository->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($mainLanguage));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array($this->setUpObject())));

        $this->manager->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeAddLanguageCommit($this->event);
    }

    public function testDbRecorsHaveBeenCopiedFromTheFirstAvailableLanguage()
    {
        $this->setUpLanguageManager();
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

        $mainLanguage = $this->setUpLanguage(2);
        $this->languageRepository->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($mainLanguage));

        $this->languageRepository->expects($this->once())
            ->method('firstOne')
            ->will($this->returnValue($this->setUpLanguage(3)));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array($this->setUpObject())));

        $this->manager->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeAddLanguageCommit($this->event);
    }

    protected function setUpTestToCopyFromRequestLanguage()
    {
        $this->setUpLanguageManager();
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

        $mainLanguage = $this->setUpLanguage(3);
        $this->languageRepository->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($mainLanguage));

        $this->languageRepository->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue($mainLanguage));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array($this->setUpObject())));

        $this->manager->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));
    }

    protected function setUpLanguageManager()
    {
        $this->languageManager->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->setUpLanguage(2)));

        $this->languageManager->expects($this->any())
            ->method('getLanguageRepository')
            ->will($this->returnValue($this->languageRepository));

    }
}
