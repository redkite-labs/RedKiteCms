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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Page;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page\AddPageBlocksListener;
use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Base\BaseListenerTest;

/**
 * AddPageBlocksListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AddPageBlocksListenerTest extends BaseListenerTest
{
    private $event;
    private $testListener;
    private $pageManager;
    private $templateManager;
    private $validator;
    private $pageRepository;
    private $languageRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->event = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->once())
            ->method('createRepository')
            ->will($this->returnValue($this->languageRepository));


        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->testListener = new AddPageBlocksListener($this->factoryRepository);
    }

    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeAddPageCommit($this->event);
    }

    public function testNothingIsAddedWhenAnyLanguageExists()
    {
        $this->pageRepository->expects($this->never())
            ->method('startTransaction');

        $this->pageRepository->expects($this->never())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollBack');

        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));

        $this->event->expects($this->never())
            ->method('abort');

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array()));

        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageRepository));

        $this->testListener->onBeforeAddPageCommit($this->event);
    }

    public function testSaveFailsWhenContentsAreNotSaved()
    {
        $page = $this->setUpPage(2);
        $language = $this->setUpLanguage(2);

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollBack');

        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));

        $this->event->expects($this->once())
            ->method('abort');

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));

        $this->validator->expects($this->once())
            ->method('hasPages')
            ->will($this->returnValue(true));

        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));

        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageRepository));

        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));

        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));

        $this->templateManager->expects($this->once())
            ->method('populate')
            ->will($this->returnValue(false));

        $this->testListener->onBeforeAddPageCommit($this->event);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSaveFailsBecauseAndUnespectedExceptionIsThrown()
    {
        $page = $this->setUpPage(2);
        $language = $this->setUpLanguage(2);

        $this->event->expects($this->once())
            ->method('abort');

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollback');

        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));

        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));

        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageRepository));

        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));

        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));

        $this->templateManager->expects($this->once())
            ->method('populate')
            ->will($this->throwException(new \RuntimeException()));

        $this->testListener->onBeforeAddPageCommit($this->event);
    }

    public function testSave()
    {
        $page = $this->setUpPage(2);
        $language = $this->setUpLanguage(2);

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));

        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));

        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageRepository));

        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));

        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));

        $this->templateManager->expects($this->once())
            ->method('populate')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeAddPageCommit($this->event);
    }

    public function testSaveFailsWhenAtLeastAtributeIsNotSaved()
    {
        $page = $this->setUpPage(2);
        $language1 = $this->setUpLanguage(2);
        $language2 = $this->setUpLanguage(3);

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollBack');

        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));

        $this->event->expects($this->once())
            ->method('abort');

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language1, $language2)));

        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));

        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageRepository));

        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));

        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));

        $this->templateManager->expects($this->exactly(2))
            ->method('populate')
            ->will($this->onConsecutiveCalls(true, false));

        $this->testListener->onBeforeAddPageCommit($this->event);
    }

    public function testSaveWhenSiteHasMoreLanguages()
    {
        $page = $this->setUpPage(2);
        $language1 = $this->setUpLanguage(2);
        $language2 = $this->setUpLanguage(3);

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

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
            ->method('getPageModel')
            ->will($this->returnValue($this->pageRepository));

        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));

        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));

        $this->templateManager->expects($this->exactly(2))
            ->method('populate')
            ->will($this->returnValue(true));

        $this->testListener->onBeforeAddPageCommit($this->event);
    }
}