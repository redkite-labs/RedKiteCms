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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Seo;

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;

/**
 * AlSeoManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlSeoManagerTest extends AlContentManagerBase
{
    private $seoManager;

    protected function setUp()
    {
        parent::setUp();

        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->seoRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->seoRepository->expects($this->any())
            ->method('getRepositoryObjectClassName')
            ->will($this->returnValue('\AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo'));

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $this->seoManager = new AlSeoManager($this->eventsHandler, $this->factoryRepository, $this->validator);
    }

    public function testSeoRepositoryInjectedBySetters()
    {
        $seoRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\SeoRepositoryInterface')
                              ->disableOriginalConstructor()
                              ->getMock();
        $this->assertEquals($this->seoManager, $this->seoManager->setSeoRepository($seoRepository));
        $this->assertEquals($seoRepository, $this->seoManager->getSeoRepository());
        $this->assertNotSame($this->seoManager, $this->seoManager->getSeoRepository());
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     */
    public function testSetFailsWhenANotValidPropelObjectIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $this->seoManager->set($block);
    }

    public function testSetANullAlPageObject()
    {
        $this->seoManager->set(null);
        $this->assertNull($this->seoManager->get());
    }

    public function testSetAlPageObject()
    {
        $seo = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');
        $this->seoManager->set($seo);
        $this->assertEquals($seo, $this->seoManager->get());
    }

    public function testAddIsSkippedWhenAnyParameterIsGiven()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));

        $values = array();
        $this->assertNull($this->seoManager->save($values));
    }

    public function testAddIsSkippedWhenAnyExpectedParamIsGiven()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkRequiredParamsExists')
            ->will($this->throwException(new General\ParameterExpectedException()));

        $values = array('fake' => 'value');

        $this->assertNull($this->seoManager->save($values));
    }

    public function testAddIsSkippedWhenExpectedPageNameParamIsMissing()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $this->setUpEventsHandler($event);

        $params = array('LanguageId' => '',
                        'Permalink' => '');

        $this->assertNull($this->seoManager->save($params));
    }

    public function testAddIsSkippedWhenExpectedLanguageIdParamIsMissing()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $this->setUpEventsHandler($event);

        $params = array('PageId' => '',
                        'Permalink' => '');

        $this->assertNull($this->seoManager->save($params));
    }

    public function testAddIsSkippedWhenExpectedPermalinkParamIsMissing()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $this->setUpEventsHandler($event);

        $params = array('PageId' => '',
                        'LanguageId' => '');

        $this->assertNull($this->seoManager->save($params));
    }

    public function testAddIsSkippedWhenExpectedLanguageIdParamIsEmpty()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $this->setUpEventsHandler($event);

        $params = array('PageId'      => '2',
                        'LanguageId'  => '',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $this->assertNull($this->seoManager->save($params));
    }

    public function testAddIsSkippedWhenExpectedPermalinkParamIsEmpty()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $this->setUpEventsHandler($event);

        $params = array('PageId'      => '2',
                        'LanguageId'  => '2',
                        'Permalink'     => '',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');

        $this->assertNull($this->seoManager->save($params));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddThrownAnUnespectedException()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $this->setUpEventsHandler($event);

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
            ->method('rollback');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->once())
                ->method('save')
                ->will($this->throwException(new \RuntimeException()));

        $params = array('PageId'      => '2',
                        'LanguageId'  => '2',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');
        $this->seoManager->save($params);
    }

    public function testAddNewSeoFailsBecauseSaveFailsAtLast()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $this->setUpEventsHandler($event);

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
            ->method('rollback');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(false));

        $params = array('PageId'      => '2',
                        'LanguageId'  => '2',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');
        $this->assertFalse($this->seoManager->save($params));
    }

    public function testAddSeo()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeAddSeoCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $params = array('PageId'      => '2',
                        'LanguageId'  => '2',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');
        $expectedParams = $params;
        $expectedParams['Permalink'] = 'this-is-a-website-fake-page';
        $this->seoRepository->expects($this->once())
                ->method('save')
                ->with($expectedParams)
                ->will($this->returnValue(true));
        
        $this->assertTrue($this->seoManager->save($params));
    }
    
    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage The seo adding action has been aborted
     */
    public function testAddActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $this->seoRepository->expects($this->never())
            ->method('startTransaction');

        $this->seoRepository->expects($this->never())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $this->seoRepository->expects($this->never())
                ->method('setRepositoryObject');

        $this->seoRepository->expects($this->never())
                ->method('save');

        $params = array('PageId'      => '2',
                        'LanguageId'  => '2',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');
        $this->seoManager->save($params);
    }

    public function testAddParametersHaveBeenChangedByAnEvent()
    {
        $changedParams = array(
            'PageId'      => '2',
            'LanguageId'  => '2',
            'Permalink'     => 'permalink is changed by event',
            'Title'         => 'page title',
            'Description'   => 'page description',
            'Keywords'      => ''
        );

        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $event1->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue($changedParams));
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $changedParams['Permalink'] = 'permalink-is-changed-by-event';
        $this->seoRepository->expects($this->once())
                ->method('save')
                ->with($changedParams)
                ->will($this->returnValue(true));

        $params = array('PageId'      => '2',
                        'LanguageId'  => '2',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');
        $this->assertTrue($this->seoManager->save($params));
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

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->never())
            ->method('commit');

        $this->seoRepository->expects($this->once())
            ->method('rollback');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));

        $params = array('PageId'      => '2',
                        'LanguageId'  => '2',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');
        $this->assertFalse($this->seoManager->save($params));
    }

    public function testEditIsSkippedWhenAnyParamIsGiven()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));

        $this->seoRepository->expects($this->never())
            ->method('save');

        $params = array();
        $this->assertNull($this->seoManager->save($params));
    }

    public function testEditIsSkippedWhenAnyoneOfTheExpectedParamIsGiven()
    {
        $seo = $this->setUpSeoObject();

        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkOnceValidParamExists')
            ->will($this->throwException(new General\ParameterExpectedException()));

        $this->seoRepository->expects($this->never())
            ->method('save');

        $params = array('Keywords' => 'test');
        $this->seoManager->set($seo);
        $this->assertNull($this->seoManager->save($params));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEditBlockThrownAnUnespectedException()
    {
        $seo = $this->setUpSeoObject();

        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $this->setUpEventsHandler($event);

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
            ->method('rollback');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->once())
                ->method('save')
                ->will($this->throwException(new \RuntimeException()));

        $params = array('PageId'      => '2',
                        'LanguageId'  => '2',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');
        $this->seoManager->set($seo);
        $this->seoManager->save($params);
    }

    public function testEditFailsBecauseSaveFailsAtLast()
    {
        $seo = $this->setUpSeoObject();

        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $this->setUpEventsHandler($event);

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
            ->method('rollback');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(false));

        $params = array('PageId'      => '2',
                        'LanguageId'  => '2',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');
        $this->seoManager->set($seo);
        $this->assertFalse($this->seoManager->save($params));
    }

    public function testEditPermalink()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeEditSeoCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $seo = $this->setUpSeoObject();

        $seo->expects($this->any())
            ->method('getPermalink')
            ->will($this->returnValue('saved-permalink'));

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $expectedParams = array(
            'Permalink' => 'fake-page-has-been-renamed',
            'oldPermalink' => 'saved-permalink',
        );
        $this->seoRepository->expects($this->once())
            ->method('save')
            ->with($expectedParams)
            ->will($this->returnValue(true));

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $params = array('Permalink' => 'fake page has been renamed');
        $this->seoManager->set($seo);
        $res = $this->seoManager->save($params);
        $this->assertTrue($res);
    }
    
    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage The seo editing action has been aborted
     */
    public function testEditActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $seo = $this->setUpSeoObject();

        $this->seoRepository->expects($this->never())
                ->method('setRepositoryObject');

        $this->seoRepository->expects($this->never())
            ->method('save');
        
        $this->seoRepository->expects($this->never())
            ->method('startTransaction');

        $this->seoRepository->expects($this->never())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $params = array('Permalink' => 'fake page has been renamed');
        $this->seoManager->set($seo);
        $this->seoManager->save($params);
    }

    public function testEditParametersHaveBeenChangedByAnEvent()
    {
        $changedParams = array(
            'Permalink' => 'permalink changed by event',
        );

        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeEditSeoCommitEvent');
        $event1->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue($changedParams));
        $this->setUpEventsHandler(null, 3);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $seo = $this->setUpSeoObject();
        $seo->expects($this->once())
            ->method('getPermalink')
            ->will($this->returnValue('this-is-a-website-fake-page'));

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $expectedParams = array(
            'Permalink' => 'permalink-changed-by-event',
            'oldPermalink' => 'this-is-a-website-fake-page',
        );
        $this->seoRepository->expects($this->once())
            ->method('save')
            ->with($expectedParams)
            ->will($this->returnValue(true));

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $params = array('Permalink' => 'fake page has been renamed');
        $this->seoManager->set($seo);
        $res = $this->seoManager->save($params);
        $this->assertTrue($res);
    }

    public function testAListenerHasAbortedTheEditAction()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeEditSeoCommitEvent');
        $event2->expects($this->once())
                ->method('isAborted')
                ->will($this->returnValue(true));
        $this->setUpEventsHandler(null, 2);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $seo = $this->setUpSeoObject();
        $seo->expects($this->once())
            ->method('getPermalink')
            ->will($this->returnValue('this-is-a-website-fake-page'));

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $expectedParams = array(
            'Permalink' => 'fake-page-has-been-renamed',
            'oldPermalink' => 'this-is-a-website-fake-page',
        );
        $this->seoRepository->expects($this->once())
            ->method('save')
            ->with($expectedParams)
            ->will($this->returnValue(true));

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->never())
            ->method('commit');

        $this->seoRepository->expects($this->once())
            ->method('rollback');

        $params = array('Permalink' => 'fake page has been renamed');
        $this->seoManager->set($seo);
        $res = $this->seoManager->save($params);
        $this->assertFalse($res);
    }

    public function testWhenAParameterHasTheSameValueOfTheOneSaveIsremovedFromTheValuesArray()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $this->setUpEventsHandler($event);

        $seo = $this->setUpSeoObject();
        $seo->expects($this->once())
            ->method('getMetaTitle')
            ->will($this->returnValue('page-title'));

        $seo->expects($this->once())
            ->method('getMetaDescription')
            ->will($this->returnValue('page-description'));

        $seo->expects($this->once())
            ->method('getMetaKeywords')
            ->will($this->returnValue('page-keywords'));

        $seo->expects($this->once())
            ->method('getPermalink')
            ->will($this->returnValue('this-is-a-website-fake-page'));

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));

        $this->seoRepository->expects($this->never())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->never())
            ->method('save');

        $this->seoRepository->expects($this->never())
            ->method('startTransaction');

        $this->seoRepository->expects($this->never())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $params = array(
            'Permalink' => 'this-is-a-website-fake-page',
            'MetaTitle' => 'page-title',
            'MetaDescription' => 'page-description',
            'MetaKeywords' => 'page-keywords',
        );
        $this->seoManager->set($seo);
        $res = $this->seoManager->save($params);
        $this->assertNull($res);
    }

    public function testEditOthersThanPermalink()
    {
        $seo = $this->setUpSeoObject();

        $seo->expects($this->any())
            ->method('getMetaTitle')
            ->will($this->returnValue('title'));

        $seo->expects($this->any())
            ->method('getMetaDescription')
            ->will($this->returnValue('decription'));

        $seo->expects($this->any())
            ->method('getMetaKeywords')
            ->will($this->returnValue('some keywords'));

        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeEditSeoCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $params = array('MetaTitle' => 'new title',
                        'MetaDescription' => 'new decription',
                        'MetaKeywords' => 'new some keywords',);        
        $this->seoRepository->expects($this->once())
            ->method('save')
            ->with($params)
            ->will($this->returnValue(true));

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $this->seoManager->set($seo);
        $res = $this->seoManager->save($params);
        $this->assertTrue($res);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testDeleteFailsWhenTheManagedSeoIsNull()
    {
        $this->eventsHandler->expects($this->never())
            ->method('createEvent');

        $this->seoManager->set(null);
        $this->seoManager->delete();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDeleteThrownAnUnespectedException()
    {
        $seo = $this->setUpSeoObject();

        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoDeletingEvent');
        $this->setUpEventsHandler($event);

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->once())
                ->method('delete')
                ->will($this->throwException(new \RuntimeException()));

        $this->seoRepository->expects($this->once())
            ->method('rollBack');

        $this->seoManager->set($seo);
        $this->seoManager->delete();
    }

    public function testDeleteFailsBecauseSaveFailsAtLast()
    {
        $seo = $this->setUpSeoObject();

        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoDeletingEvent');
        $this->setUpEventsHandler($event);

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(false));

        $this->seoRepository->expects($this->once())
            ->method('rollBack');

        $this->seoManager->set($seo);
        $res = $this->seoManager->delete();
        $this->assertFalse($res);
    }

    public function testDelete()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoDeletingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeDeleteSeoCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $seo = $this->setUpSeoObject();
        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $this->seoRepository->expects($this->once())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $this->seoManager->set($seo);
        $res = $this->seoManager->delete();
        $this->assertTrue($res);
    }
    
    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage The seo deleting action has been aborted
     */
    public function testDeleteActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoDeletingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $seo = $this->setUpSeoObject();
        $this->seoRepository->expects($this->never())
            ->method('startTransaction');

        $this->seoRepository->expects($this->never())
                ->method('setRepositoryObject');

        $this->seoRepository->expects($this->never())
                ->method('delete');

        $this->seoRepository->expects($this->never())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $this->seoManager->set($seo);
        $this->seoManager->delete();
    }

    public function testAListenerHasAbortedTheDeleteAction()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoDeletingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeDeleteSeoCommitEvent');
        $event2->expects($this->once())
                ->method('isAborted')
                ->will($this->returnValue(true));
        $this->setUpEventsHandler(null, 2);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $seo = $this->setUpSeoObject();
        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $this->seoRepository->expects($this->never())
            ->method('commit');

        $this->seoRepository->expects($this->once())
            ->method('rollback');

        $this->seoManager->set($seo);
        $this->assertFalse($this->seoManager->delete());
    }

    public function testDeleteSeoAttributesFromLanguageReturnsTrueWhenSeoHaNotBeenFound()
    {        
        $this->seoRepository->expects($this->once())
                ->method('fromPageAndLanguage')
                ->will($this->returnValue(null));

        $res = $this->seoManager->deleteSeoAttributesFromLanguage(2, 9999);
        $this->assertTrue($res);
    }


    public function testDeleteSeoAttributesFromLanguage()
    {
        $event1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeSeoDeletingEvent');
        $event2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeDeleteSeoCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));
        
        $seo = $this->setUpSeoObject();
        $this->seoRepository->expects($this->once())
                ->method('fromPageAndLanguage')
                ->will($this->returnValue($seo));

        $this->seoRepository->expects($this->once())
            ->method('startTransaction');

        $this->seoRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->seoRepository->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $this->seoRepository->expects($this->once())
            ->method('commit');

        $this->seoRepository->expects($this->never())
            ->method('rollback');

        $res = $this->seoManager->deleteSeoAttributesFromLanguage(2, 2);
        $this->assertTrue($res);
    }

    private function setUpSeoObject()
    {
        $seo = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');
        $seo->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        return $seo;
    }
}
