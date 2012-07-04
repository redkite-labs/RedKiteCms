<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Language\Base;

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Base\BaseListenerTest;

/**
 * AddLanguageBaseListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
abstract class AddLanguageBaseListenerTest extends BaseListenerTest
{
    protected $event;
    protected $testListener;
    protected $languageManager;
    protected $languageModel;
    protected $manager;
    protected $objectModel;

    abstract protected function setUpObject();

    protected function setUp()
    {
        parent::setUp();

        $this->event = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageManager->expects($this->any())
            ->method('getLanguageModel')
            ->will($this->returnValue($this->languageModel));

        $this->languageManager->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->setUpLanguage(2)));

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
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->once())
            ->method('abort');

        $this->languageModel->expects($this->never())
            ->method('startTransaction');

        $this->languageModel->expects($this->never())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollBack');

        $this->languageModel->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue(null));

        $this->testListener->onBeforeAddLanguageCommit($this->event);
    }

    public function testNothingIsAddedWhenAnyLanguageExists()
    {
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->never())
            ->method('abort');

        $this->languageModel->expects($this->never())
            ->method('startTransaction');

        $this->languageModel->expects($this->never())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollBack');

        $this->languageModel->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($this->setUpLanguage(3)));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array()));

        $this->testListener->onBeforeAddLanguageCommit($this->event);
    }

    public function testSaveFailsWhenDbRecorsAreNotSaved()
    {
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->once())
            ->method('abort');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('rollBack');

        $this->languageModel->expects($this->once())
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
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->once())
            ->method('abort');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('rollBack');

        $this->languageModel->expects($this->once())
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
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->never())
            ->method('abort');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollBack');

        $this->languageModel->expects($this->once())
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
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->never())
            ->method('abort');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollBack');

        $mainLanguage = $this->setUpLanguage(3);
        $this->languageModel->expects($this->once())
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
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->never())
            ->method('abort');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollBack');

        $mainLanguage = $this->setUpLanguage(2);
        $this->languageModel->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($mainLanguage));

        $this->languageModel->expects($this->once())
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
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->languageManager));

        $this->event->expects($this->never())
            ->method('abort');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollBack');

        $mainLanguage = $this->setUpLanguage(3);
        $this->languageModel->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($mainLanguage));

        $this->languageModel->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue($mainLanguage));

        $this->objectModel->expects($this->once())
            ->method('fromLanguageId')
            ->will($this->returnValue(array($this->setUpObject())));

        $this->manager->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));
    }
}