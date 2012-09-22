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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Page;

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;

/**
 * AlPageManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlPageManagerTest extends AlContentManagerBase
{
    private $pageManager;
    private $templateManager;

    protected function setUp()
    {
        parent::setUp();

        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageRepository->expects($this->any())
            ->method('getRepositoryObjectClassName')
            ->will($this->returnValue('\AlphaLemon\AlphaLemonCmsBundle\Model\AlPage'));

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->pageRepository));

        $this->pageManager = new AlPageManager($this->eventsHandler, $this->templateManager, $this->factoryRepository, $this->validator);
    }
    
    public function testPageRepositoryInjectedBySetters()
    {
        $pageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->assertEquals($this->pageManager, $this->pageManager->setPageRepository($pageRepository));
        $this->assertEquals($pageRepository, $this->pageManager->getPageRepository());
        $this->assertNotSame($this->pageRepository, $this->pageManager->getPageRepository());
    }

    public function testTemplateManagerInjectedBySetters()
    {
        $templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->assertEquals($this->pageManager, $this->pageManager->setTemplateManager($templateManager));
        $this->assertEquals($templateManager, $this->pageManager->getTemplateManager());
        $this->assertNotSame($this->templateManager, $this->pageManager->getTemplateManager());
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     */
    public function testSetFailsWhenANotValidPropelObjectIsGiven()
    {
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $this->pageManager->set($page);
    }

    public function testSetANullAlPageObject()
    {
        $this->pageManager->set(null);
        $this->assertNull($this->pageManager->get());
    }

    public function testSetAlPageObject()
    {
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageManager->set($page);
        $this->assertEquals($page, $this->pageManager->get());
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyParametersException
     */
    public function testAddFailsWhenAnyParamIsGiven()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));

        $values = array();
        $this->pageManager->save($values);
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testAddFailsWhenAnyExpectedParamIsGiven()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkRequiredParamsExists')
            ->will($this->throwException(new General\ParameterExpectedException()));

        $values = array('fake' => 'value');

        $this->pageManager->save($values);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testAddFailsWhenExpectedPageNameParamIsMissing()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $this->setUpEventsHandler($event);

        $params = array('TemplateName'      => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $this->pageManager->save($params);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testAddFailsWhenExpectedTemplateParamIsMissing()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $this->setUpEventsHandler($event);

        $params = array('PageName'      => 'fake page',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $this->pageManager->save($params);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Page\PageExistsException
     */
    public function testAddFailsWhenTryingToAddPageThatAlreadyExists()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('pageExists')
            ->will($this->returnValue(true));

        $params = array('PageName'      => 'fake page',
                        'TemplateName'      => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $this->pageManager->save($params);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Page\AnyLanguageExistsException
     */
    public function testAddFailsWhenAnyLanguageHasBeenAddedAndTryingToAddPage()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('hasLanguages')
            ->will($this->returnValue(false));

        $params = array('PageName'      => 'fake page',
                        'TemplateName'      => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $this->pageManager->save($params);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddBlockThrownAnUnespectedException()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollback');

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->validator->expects($this->once())
            ->method('hasLanguages')
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
                ->method('save')
                ->will($this->throwException(new \RuntimeException()));

        $params = array('PageName'      => 'fake page',
                        'TemplateName'  => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');
        $this->pageManager->save($params);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResetHomeThrownAnUnespectedExceptionWhenAdding()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('hasPages')
            ->will($this->returnValue(true));

        $this->validator->expects($this->once())
            ->method('hasLanguages')
            ->will($this->returnValue(true));

        $homepage = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository->expects($this->once())
            ->method('homePage')
            ->will($this->returnValue($homepage));

        $this->pageRepository->expects($this->once(1))
            ->method('setRepositoryObject')
            ->will($this->returnSelf());

        $this->pageRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollBack');

        $params = array('PageName'      => 'fake page',
                        'IsHome'        => '1',
                        'TemplateName'  => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $this->pageManager->save($params);
    }

    public function testAddNewPageFailsBecauseSaveFailsAtLast()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollBack');

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->validator->expects($this->once())
            ->method('hasLanguages')
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));

        $params = array('PageName'      => 'fake page',
                        'TemplateName'  => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $res = $this->pageManager->save($params);
        $this->assertFalse($res);
    }

    public function testAddNewPageFailsBecauseResetHomeFails()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('hasPages')
            ->will($this->returnValue(true));

        $this->validator->expects($this->once())
            ->method('hasLanguages')
            ->will($this->returnValue(true));

        $homepage = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository->expects($this->once())
            ->method('homePage')
            ->will($this->returnValue($homepage));

        $this->pageRepository->expects($this->once(1))
            ->method('setRepositoryObject')
            ->will($this->returnSelf());

        $this->pageRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollBack');

        $params = array('PageName'      => 'fake page',
                        'IsHome'        => '1',
                        'TemplateName'  => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $res = $this->pageManager->save($params);
        $this->assertFalse($res);
    }

    public function testAddNewHomePage()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->validator->expects($this->once())
            ->method('hasPages')
            ->will($this->returnValue(true));

        $this->validator->expects($this->once())
            ->method('hasLanguages')
            ->will($this->returnValue(true));

        $params = array('PageName'      => 'fake page',
                        'TemplateName'  => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => 'some,keywords',
                        'IsHome'        => '1');
        $this->pageRepository->expects($this->exactly(2))
            ->method('save')
            ->will($this->returnValue(true));

        $homepage = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository->expects($this->once())
            ->method('homePage')
            ->will($this->returnValue($homepage));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $this->pageRepository->expects($this->exactly(2))
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $res = $this->pageManager->save($params);
        $this->assertTrue($res);
    }

    public function testAddNewPage()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->validator->expects($this->once())
            ->method('hasLanguages')
            ->will($this->returnValue(true));

        $params = array('PageName'      => 'fake page',
                        'TemplateName'  => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => 'some,keywords',
                        'IsHome'        => '1');
        $expectedParams = $params;
        $expectedParams['PageName'] = 'fake-page';
        $this->pageRepository->expects($this->once())
            ->method('save')
            ->with($expectedParams)
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $res = $this->pageManager->save($params);
        $this->assertTrue($res);
    }
    
    public function testResetHomeIsSkippedBecauseAnyHomePageHasBeenDefined()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->validator->expects($this->once())
            ->method('hasLanguages')
            ->will($this->returnValue(true));
        
        $this->validator->expects($this->once())
            ->method('hasPages')
            ->will($this->returnValue(true));
        
        $this->pageRepository->expects($this->once())
            ->method('homePage')
            ->will($this->returnValue(null));

        $params = array('PageName'      => 'fake page',
                        'TemplateName'  => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => 'some,keywords',
                        'IsHome'        => '1');
        $expectedParams = $params;
        $expectedParams['PageName'] = 'fake-page';
        $this->pageRepository->expects($this->once())
            ->method('save')
            ->with($expectedParams)
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $res = $this->pageManager->save($params);
        $this->assertTrue($res);
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage The page adding action has been aborted
     */
    public function testAddActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->never())
            ->method('hasLanguages');

        $this->pageRepository->expects($this->never())
            ->method('save');

        $this->pageRepository->expects($this->never())
            ->method('startTransaction');

        $this->pageRepository->expects($this->never())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $this->pageRepository->expects($this->never())
                ->method('setRepositoryObject');

        $params = array('PageName'      => 'fake page',
                        'TemplateName'  => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $res = $this->pageManager->save($params);
        $this->assertTrue($res);
    }

    public function testAddParametersHaveBeenChangedByAnEvent()
    {
        $changedParams = array(
            'PageName'      => 'fake page',
            'TemplateName'  => 'home',
            'Permalink'     => 'permalink changed by event',
            'Title'         => 'page title',
            'Description'   => 'page description',
            'Keywords'      => ''
        );

        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent');
        $event1->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue($changedParams));
        $this->setUpEventsHandler(null, 3);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->validator->expects($this->once())
            ->method('hasLanguages')
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $params = array('PageName'      => 'fake page',
                        'TemplateName'  => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $res = $this->pageManager->save($params);
        $this->assertTrue($res);
    }

    public function testAListenerHasAbortedTheAddAction()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent');
        $event2->expects($this->once())
                ->method('isAborted')
                ->will($this->returnValue(true));
        $this->setUpEventsHandler(null, 2);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->validator->expects($this->once())
            ->method('hasLanguages')
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->never())
            ->method('commit');

        $this->pageRepository->expects($this->once())
            ->method('rollback');

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $params = array('PageName'      => 'fake page',
                        'TemplateName'  => 'home',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $res = $this->pageManager->save($params);
        $this->assertFalse($res);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyParametersException
     */
    public function testEditFailsWhenAnyParamIsGiven()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageEditingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));

        $this->pageRepository->expects($this->never())
            ->method('save');

        $params = array();
        $this->pageManager->save($params);
    }

    public function testEditFailsBecauseSaveFailsAtLast()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageEditingEvent');
        $this->setUpEventsHandler($event);

        $page = $this->setUpPage();
        $page->expects($this->any())
            ->method('getPageName')
            ->will($this->returnValue('fake-page'));

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->pageRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollBack');

        $params = array('PageName' => 'fake page');
        $this->pageManager->set($page);
        $res = $this->pageManager->save($params);
        $this->assertFalse($res);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEditBlockThrownAnUnespectedException()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageEditingEvent');
        $this->setUpEventsHandler($event);

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollback');

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->pageRepository->expects($this->once())
                ->method('save')
                ->will($this->throwException(new \RuntimeException()));

        $page = $this->setUpPage();
        $params = array('PageName' => 'fake page');
        $this->pageManager->set($page);
        $this->pageManager->save($params);
    }

    public function testEditPageName()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageEditingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeEditPageCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $expectedParams = array(
            'PageName'      => 'fake-page',
        );
        $this->pageRepository->expects($this->once())
            ->method('save')
            ->with($expectedParams)
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $page = $this->setUpPage();
        $params = array('PageName' => 'fake page');
        $this->pageManager->set($page);
        $res = $this->pageManager->save($params);
        $this->assertTrue($res);
    }
    
    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage The page editing action has been aborted
     */
    public function testEditActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $this->pageRepository->expects($this->never())
                ->method('setRepositoryObject');

        $this->pageRepository->expects($this->never())
            ->method('save');

        $this->pageRepository->expects($this->never())
            ->method('startTransaction');

        $this->pageRepository->expects($this->never())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $page = $this->setUpPage();
        $params = array('PageName' => 'fake page');
        $this->pageManager->set($page);
        $this->pageManager->save($params);
    }

    public function testEditParametersHaveBeenChangedByAnEvent()
    {
        $changedParams = array(
            'PageName'      => 'page name changed by event',
        );

        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent');
        $event1->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue($changedParams));
        $this->setUpEventsHandler(null, 3);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $expectedParams = array(
            'PageName'      => 'page-name-changed-by-event',
        );
        $this->pageRepository->expects($this->once())
            ->method('save')
            ->with($expectedParams)
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');
        
        $page = $this->setUpPage();
        $params = array('PageName' => 'fake page');
        $this->pageManager->set($page);
        $res = $this->pageManager->save($params);
        $this->assertTrue($res);
    }

    public function testAListenerHasAbortedTheEditAction()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent');
        $event2->expects($this->once())
                ->method('isAborted')
                ->will($this->returnValue(true));
        $this->setUpEventsHandler(null, 2);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->pageRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->never())
            ->method('commit');

        $this->pageRepository->expects($this->once())
            ->method('rollback');

        $page = $this->setUpPage();
        $params = array('PageName' => 'fake page');
        $this->pageManager->set($page);
        $res = $this->pageManager->save($params);
        $this->assertFalse($res);
    }

    public function testEditHomePageBecauseResetHomeFails()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageEditingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('hasPages')
            ->with(1)
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));

        $homepage = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository->expects($this->once())
            ->method('homePage')
            ->will($this->returnValue($homepage));

        $this->pageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollBack');

        $page = $this->setUpPage();
        $params = array('IsHome' => 1);
        $this->pageManager->set($page);
        $res = $this->pageManager->save($params);
        $this->assertFalse($res);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResetHomeThrownAnUnespectedExceptionWhenEditing()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageEditingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('hasPages')
            ->will($this->returnValue(true));

        $homepage = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository->expects($this->once())
            ->method('homePage')
            ->will($this->returnValue($homepage));

        $this->pageRepository->expects($this->once(1))
            ->method('setRepositoryObject')
            ->will($this->returnSelf());

        $this->pageRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollBack');

        $page = $this->setUpPage();
        $params = array('IsHome' => 1);
        $this->pageManager->set($page);
        $this->pageManager->save($params);
    }

    public function testEditHomePage()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageEditingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeEditPageCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->validator->expects($this->once())
            ->method('hasPages')
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->exactly(2))
            ->method('save')
            ->will($this->returnValue(true));

        $homepage = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository->expects($this->once())
            ->method('homePage')
            ->will($this->returnValue($homepage));

        $this->pageRepository->expects($this->exactly(2))
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $page = $this->setUpPage();
        $params = array('IsHome' => 1);
        $this->pageManager->set($page);
        $res = $this->pageManager->save($params);
        $this->assertTrue($res);
    }

    public function testEditTemplate()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageEditingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeEditPageCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $params = array(
            'TemplateName' => 'new',
            'oldTemplateName' => ''
        );
        $this->pageRepository->expects($this->once())
            ->method('save')
            ->with($params)
            ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once(2))
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $page = $this->setUpPage();
        $page->expects($this->once())
            ->method('getTemplateName');

        $this->pageManager->set($page);
        $res = $this->pageManager->save($params);
        $this->assertTrue($res);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testDeleteFailsWhenTheManagedPageIsNull()
    {
        $this->eventsHandler->expects($this->never())
                            ->method('createEvent');

        $this->pageManager->set(null);
        $this->pageManager->delete();
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Page\RemoveHomePageException
     */
    public function testDeleteFailsWhenTryingToRemoveTheHomePage()
    {
        $this->eventsHandler->expects($this->never())
                            ->method('createEvent');

        $page = $this->setUpPage(null, 1);
        $this->pageManager->set($page);
        $this->pageManager->delete();
    }

    public function testDeleteFailsBecauseSaveFailsAtLast()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageDeletingEvent');
        $this->setUpEventsHandler($event);

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(false));

        $this->pageRepository->expects($this->once())
            ->method('rollBack');

        $page = $this->setUpPage(null, 0);
        $this->pageManager->set($page);
        $res = $this->pageManager->delete();
        $this->assertFalse($res);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDeleteBlockThrownAnUnespectedException()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageDeletingEvent');
        $this->setUpEventsHandler($event);

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
            ->method('rollback');

        $this->pageRepository->expects($this->once())
                ->method('delete')
                ->will($this->throwException(new \RuntimeException()));

        $page = $this->setUpPage(null, 0);
        $this->pageManager->set($page);
        $this->pageManager->delete();
    }

    public function testDelete()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageDeletingEvent');
        $event2 = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeDeletePageCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $this->pageRepository->expects($this->once())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $page = $this->setUpPage(null, 0);
        $this->pageManager->set($page);
        $res = $this->pageManager->delete();
        $this->assertTrue($res);
    }
    
    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage The page deleting action has been aborted
     */
    public function testDeleteActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeLanguageDeletingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $this->pageRepository->expects($this->never())
            ->method('startTransaction');

        $this->pageRepository->expects($this->never())
                ->method('delete');

        $this->pageRepository->expects($this->never())
            ->method('commit');

        $this->pageRepository->expects($this->never())
            ->method('rollback');

        $page = $this->setUpPage(null, 0);
        $this->pageManager->set($page);
        $this->pageManager->delete();
    }

    public function testAListenerHasAbortedTheDeleteAction()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeLanguageDeletingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeDeleteLanguageCommitEvent');
        $event2->expects($this->once())
                ->method('isAborted')
                ->will($this->returnValue(true));
        $this->setUpEventsHandler(null, 2);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->pageRepository->expects($this->once())
            ->method('startTransaction');

        $this->pageRepository->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $this->pageRepository->expects($this->never())
            ->method('commit');

        $this->pageRepository->expects($this->once())
            ->method('rollback');

        $page = $this->setUpPage(null, 0);
        $this->pageManager->set($page);
        $res = $this->pageManager->delete();
        $this->assertFalse($res);
    }
    
    /**
     * This test is a porting of the one proposed by Symfony1 Joobet tutorial 
     */
    public function testSlugify()
    {
        $this->assertEquals('alphalemon', AlPageManager::slugify('AlphaLemon'));
        $this->assertEquals('alpha-lemon', AlPageManager::slugify('alpha lemon'));        
        $this->assertEquals('alpha-lemon', AlPageManager::slugify('alpha    lemon'));                
        $this->assertEquals('alphalemon', AlPageManager::slugify('    alphalemon'));                        
        $this->assertEquals('alphalemon', AlPageManager::slugify('alphalemon    '));                               
        $this->assertEquals('alpha-lemon', AlPageManager::slugify('alpha,lemon'));                             
        $this->assertEquals('n-a', AlPageManager::slugify(''));                                    
        $this->assertEquals('n-a', AlPageManager::slugify(' - '));
        $this->assertEquals('developpeur-web', AlPageManager::slugify('Développeur Web'));
    }
    
    private function setUpPage($id = 2, $isHome = null)
    {
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        if (null !== $id) {
            $page->expects($this->once())
                ->method('getId')
                ->will($this->returnValue(2));
        }
        
        if (null !== $isHome) {
            $page->expects($this->once())
                    ->method('getIsHome')
                    ->will($this->returnValue($isHome));
        }
        
        return $page;
    }
}
