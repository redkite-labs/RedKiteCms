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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Language;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Base\AlContentManagerBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Language\AlLanguageManager;
use RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General;

/**
 * AlLanguageManagerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlLanguageManagerTest extends AlContentManagerBase
{
    private $languageManager;

    protected function setUp()
    {
        parent::setUp();

        $this->validator = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorLanguageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageRepository->expects($this->any())
            ->method('getRepositoryObjectClassName')
            ->will($this->returnValue('\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage'));

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->languageRepository));

        $this->languageManager = new AlLanguageManager($this->eventsHandler, $this->factoryRepository, $this->validator);
    }

    public function testLanguageRepositoryInjectedBySetters()
    {
        $languageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->assertEquals($this->languageManager, $this->languageManager->setLanguageRepository($languageRepository));
        $this->assertEquals($languageRepository, $this->languageManager->getLanguageRepository());
        $this->assertNotSame($this->languageRepository, $this->languageManager->getLanguageRepository());
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     */
    public function testSetFailsWhenANotValidPropelObjectIsGiven()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');

        $this->languageManager->set($block);
    }

    public function testSetANullAlPageObject()
    {
        $this->languageManager->set(null);
        $this->assertNull($this->languageManager->get());
    }

    public function testSetAlPageObject()
    {
        $language =$this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage');
        $this->languageManager->set($language);
        $this->assertEquals($language, $this->languageManager->get());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     */
    public function testAddFailsWhenAnyParamIsGiven()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyArgumentsException()));

        $values = array();
        $this->languageManager->save($values);
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     */
    public function testAddFailsWhenAnyExpectedParamIsGiven()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkRequiredParamsExists')
            ->will($this->throwException(new General\ArgumentExpectedException()));

        $values = array('fake' => 'value');

        $this->languageManager->save($values);
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\Language\LanguageExistsException
     */
    public function testAddThrownAnExceptionWhenTheLanguageAlreadyExists()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('languageExists')
            ->will($this->returnValue(true));

        $this->languageRepository->expects($this->never())
                ->method('save');

        $params = array('LanguageName'  => 'en');
        $this->languageManager->save($params);
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     */
    public function testAddThrownAnExceptionWhenTheLanguageAIsEmpty()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('languageExists')
            ->will($this->returnValue(false));

        $this->languageRepository->expects($this->never())
                ->method('save');

        $params = array('LanguageName'  => '');
        $this->languageManager->save($params);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddThrownAnUnespectedException()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
                ->method('save')
                ->will($this->throwException(new \RuntimeException()));

        $params = array('LanguageName'  => 'en');
        $this->languageManager->save($params);
    }

    public function testAddNewLanguageFailsBecauseSaveFailsAtLast()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(false));

        $params = array('LanguageName'  => 'en');
        $this->assertFalse($this->languageManager->save($params));
    }

    public function testAddLanguage()
    {
        $event1 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $params = array('LanguageName'  => 'en');
        $expectedParams = $params;
        $expectedParams["MainLanguage"] = 1;
        $this->languageRepository->expects($this->once())
                ->method('save')
                ->with($expectedParams)
                ->will($this->returnValue(true));

        $this->assertTrue($this->languageManager->save($params));
    }
    
    public function testResetMainIsSkippedBecauseAnyMainLanguageHasBeenDefined()
    {
        $event1 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');
        
        $this->validator->expects($this->once())
                ->method('hasLanguages')
                ->will($this->returnValue(true));

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());
        
        $this->languageRepository->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue(null));
        
        $params = array('LanguageName'  => 'en', 'MainLanguage' => 1);
        $expectedParams = $params;
        $expectedParams["MainLanguage"] = 1;
        $this->languageRepository->expects($this->once())
                ->method('save')
                ->with($expectedParams)
                ->will($this->returnValue(true));

        $this->assertTrue($this->languageManager->save($params));
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage The language adding action has been aborted
     */
    public function testAddActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $this->languageRepository->expects($this->never())
            ->method('startTransaction');

        $this->languageRepository->expects($this->never())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');

        $this->languageRepository->expects($this->never())
                ->method('setRepositoryObject');

        $this->languageRepository->expects($this->never())
                ->method('save');

        $params = array('LanguageName'  => 'en');
        $this->languageManager->save($params);
    }

    public function testAddParametersHaveBeenChangedByAnEvent()
    {
        $changedParams = array('LanguageName'  => 'es');

        $event1 = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent');
        $event1->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue($changedParams));
        $this->setUpEventsHandler(null, 3);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));

        $params = array('LanguageName'  => 'en');
        $this->assertTrue($this->languageManager->save($params));
    }

    public function testAListenerHasAbortedTheAddAction()
    {
        $event1 = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent');
        $event2->expects($this->once())
                ->method('isAborted')
                ->will($this->returnValue(true));
        $this->setUpEventsHandler(null, 2);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->never())
            ->method('commit');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));

        $params = array('LanguageName'  => 'en');
        $res = $this->languageManager->save($params);
        $this->assertFalse($res);
    }

    public function testAddMainLanguageFailsBecauseMainLanguageHasNotBeenResetted()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->validator->expects($this->once())
                ->method('hasLanguages')
                ->will($this->returnValue(true));

        $this->languageRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(false));

        $this->languageRepository->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage')));

        $params = array('LanguageName'  => 'en', 'MainLanguage' => 1);
        $this->assertFalse($this->languageManager->save($params));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddMainLanguageFailsBecauseAnUnexpectedExceptionIsThrownWhenTheMainLanguageIsResetted()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->exactly(2))
            ->method('rollback');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->validator->expects($this->once())
                ->method('hasLanguages')
                ->will($this->returnValue(true));

        $this->languageRepository->expects($this->once())
                ->method('save')
                ->will($this->throwException(new \RuntimeException()));

        $this->languageRepository->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage')));

        $params = array('LanguageName'  => 'en',
                        'MainLanguage' => 1);
        $this->assertFalse($this->languageManager->save($params));
    }

    public function testAddMainLanguageFailsBecauseMainLanguageHasBeenResettedButSaveFailsAtLast()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $this->setUpEventsHandler($event);

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $this->languageRepository->expects($this->exactly(2))
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->validator->expects($this->once())
                ->method('hasLanguages')
                ->will($this->returnValue(true));

        $this->languageRepository->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage')));

        $this->languageRepository->expects($this->exactly(2))
                ->method('save')
                ->will($this->onConsecutiveCalls(true, false));

        $params = array('LanguageName'  => 'en', 'MainLanguage' => 1);
        $this->assertFalse($this->languageManager->save($params));
    }

    public function testAddMainLanguage()
    {
        $event1 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent');
        $this->setUpEventsHandler(null, 3);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');

        $this->languageRepository->expects($this->exactly(2))
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->validator->expects($this->once())
                ->method('hasLanguages')
                ->will($this->returnValue(true));

        $this->languageRepository->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage')));

        $params = array(
            'LanguageName'  => 'en', 
            'MainLanguage' => 1
        );
        $this->languageRepository->expects($this->exactly(2))
                ->method('save')
                ->will($this->returnValue(true));
        
        $this->assertTrue($this->languageManager->save($params));
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     */
    public function testEditFailsWhenAnyParamIsGiven()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyArgumentsException()));

        $this->languageRepository->expects($this->never())
            ->method('save');

        $params = array();
        $this->languageManager->save($params);
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     */
    public function testEditFailsWhenAnyoneOfTheExpectedParamIsGiven()
    {
        $language = $this->setUpLanguageObject();

        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkOnceValidParamExists')
            ->will($this->throwException(new General\ArgumentExpectedException()));

        $this->languageRepository->expects($this->never())
            ->method('save');

        $params = array('Fake' => 'test');
        $this->languageManager->set($language);
        $this->languageManager->save($params);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEditBlockThrownAnUnespectedException()
    {
        $language =$this->setUpLanguageObject();

        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $this->setUpEventsHandler($event);

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
                ->method('save')
                ->will($this->throwException(new \RuntimeException()));

        $params = array('LanguageName'  => 'fr');
        $this->languageManager->set($language);
        $this->languageManager->save($params);
    }

    public function testEditFailsBecauseSaveFailsAtLast()
    {
        $language =$this->setUpLanguageObject();

        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $this->setUpEventsHandler($event);

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(false));

        $params = array('LanguageName'  => 'fr');
        $this->languageManager->set($language);
        $this->assertFalse($this->languageManager->save($params));
    }

    public function testEditDoesNothingWhenTheSameParameterIsGiven()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $this->setUpEventsHandler($event);

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getLanguageName')
            ->will($this->returnValue('en'));

        $this->languageRepository->expects($this->never())
            ->method('save');

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->never())
            ->method('commit');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $params = array('LanguageName' => 'en');
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertFalse($res);
    }

    public function testEdit()
    {
        $event1 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeEditLanguageCommitEvent');
        $this->setUpEventsHandler(null, 3);
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getLanguageName')
            ->will($this->returnValue('en'));

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');

        $params = array('LanguageName' => 'fr');
        $this->languageRepository->expects($this->once())
            ->method('save')
            ->with($params)
            ->will($this->returnValue(true));

        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertTrue($res);
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage The language editing action has been aborted
     */
    public function testEditActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getLanguageName')
            ->will($this->returnValue('en'));

        $this->languageRepository->expects($this->never())
                ->method('setRepositoryObject');

        $this->languageRepository->expects($this->never())
            ->method('save');

        $this->languageRepository->expects($this->never())
            ->method('startTransaction');

        $this->languageRepository->expects($this->never())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');

        $params = array('LanguageName' => 'fr');
        $this->languageManager->set($language);
        $this->languageManager->save($params);
    }

    public function testEditParametersHaveBeenChangedByAnEvent()
    {
        $changedParams = array('LanguageName'  => 'es');

        $event1 = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeEditLanguageCommitEvent');
        $event1->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue($changedParams));
        $this->setUpEventsHandler(null, 3);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $language =$this->setUpLanguageObject();
        $language->expects($this->once())
            ->method('getLanguageName')
            ->will($this->returnValue('en'));

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
            ->method('save')
            ->with($changedParams)
            ->will($this->returnValue(true));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');

        $params = array('LanguageName' => 'fr');
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertTrue($res);
    }

    public function testAListenerHasAbortedTheEditAction()
    {
        $event1 = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent');
        $event2->expects($this->once())
                ->method('isAborted')
                ->will($this->returnValue(true));
        $this->setUpEventsHandler(null, 2);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getLanguageName')
            ->will($this->returnValue('en'));

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->never())
            ->method('commit');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $params = array('LanguageName' => 'fr');
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertFalse($res);
    }

    public function testEditMainLanguageFailsWhenResetMainLanguageFails()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $this->setUpEventsHandler($event);

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getMainLanguage')
            ->will($this->returnValue(1));

        $this->languageRepository->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage')));

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $params = array('MainLanguage' => 1);
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertFalse($res);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEditMainLanguageFailsWhenResetMainLanguageThrowsAnUnexpectedException()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $this->setUpEventsHandler($event);

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getMainLanguage')
            ->will($this->returnValue(1));

        $this->languageRepository->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage')));

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->exactly(2))
            ->method('rollback');

        $params = array('MainLanguage' => 1);
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertFalse($res);
    }

    public function testEditMainLanguageFails()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $this->setUpEventsHandler($event);

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getMainLanguage')
            ->will($this->returnValue(1));

        $this->languageRepository->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage')));

        $this->languageRepository->expects($this->exactly(2))
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->exactly(2))
            ->method('save')
            ->will($this->onConsecutiveCalls(true, false));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $params = array('MainLanguage' => 1);
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertFalse($res);
    }

    public function testEditMainLanguage()
    {
        $event1 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeEditLanguageCommitEvent');
        $this->setUpEventsHandler(null, 3);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getMainLanguage')
            ->will($this->returnValue(1));

        $this->languageRepository->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage')));

        $this->languageRepository->expects($this->exactly(2))
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->exactly(2))
            ->method('save')
            ->will($this->returnValue(true));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');

        $params = array('MainLanguage' => 1);
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertTrue($res);
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     */
    public function testDeleteFailsWhenTheManagedLanguageIsNull()
    {
        $this->eventsHandler->expects($this->never())
            ->method('dispatch');

        $this->languageManager->set(null);
        $this->languageManager->delete();
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\Language\RemoveMainLanguageException
     */
    public function testTryingToDeleteTheMainLanguageThrowsAnException()
    {
        $language = $this->setUpLanguageObject();

        $this->eventsHandler->expects($this->never())
            ->method('dispatch');

        $this->languageRepository->expects($this->never())
                ->method('delete');

        $language->expects($this->once())
                ->method('getMainLanguage')
                ->will($this->returnValue(1));

        $this->languageManager->set($language);
        $this->languageManager->delete();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDeleteThrownAnUnespectedException()
    {
        $language =$this->setUpLanguageObject();

        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageDeletingEvent');
        $this->setUpEventsHandler($event);

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
                ->method('delete')
                ->will($this->throwException(new \RuntimeException()));

        $this->languageRepository->expects($this->once())
            ->method('rollBack');

        $this->languageManager->set($language);
        $this->languageManager->delete();
    }

    public function testDeleteFailsBecauseSaveFailsAtLast()
    {
        $language =$this->setUpLanguageObject();

        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageDeletingEvent');
        $this->setUpEventsHandler($event);

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(false));

        $this->languageRepository->expects($this->once())
            ->method('rollBack');

        $this->languageManager->set($language);
        $res = $this->languageManager->delete();
        $this->assertFalse($res);
    }

    public function testDelete()
    {
        $event1 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageDeletingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeDeleteLanguageCommitEvent');
        $this->setUpEventsHandler(null, 3);

        $language =$this->setUpLanguageObject();
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $this->languageRepository->expects($this->once())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');

        $this->languageManager->set($language);
        $res = $this->languageManager->delete();
        $this->assertTrue($res);
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage The language deleting action has been aborted
     */
    public function testDeleteActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageDeletingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $language =$this->setUpLanguageObject();
        $this->languageRepository->expects($this->never())
            ->method('startTransaction');

        $this->languageRepository->expects($this->never())
                ->method('setRepositoryObject');

        $this->languageRepository->expects($this->never())
                ->method('delete');

        $this->languageRepository->expects($this->never())
            ->method('commit');

        $this->languageRepository->expects($this->never())
            ->method('rollback');

        $this->languageManager->set($language);
        $this->languageManager->delete();
    }

    public function testAListenerHasAbortedTheDeleteAction()
    {
        $event1 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageDeletingEvent');
        $event2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeDeleteLanguageCommitEvent');
        $event2->expects($this->once())
                ->method('isAborted')
                ->will($this->returnValue(true));
        $this->setUpEventsHandler(null, 2);

        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $language =$this->setUpLanguageObject();
        $this->eventsHandler->expects($this->exactly(2))
                        ->method('getEvent')
                        ->will($this->onConsecutiveCalls($event1, $event2));

        $this->languageRepository->expects($this->once())
            ->method('startTransaction');

        $this->languageRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->languageRepository->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $this->languageRepository->expects($this->never())
            ->method('commit');

        $this->languageRepository->expects($this->once())
            ->method('rollback');

        $this->languageManager->set($language);
        $res = $this->languageManager->delete();
        $this->assertFalse($res);
    }

    private function setUpLanguageObject()
    {
        $language = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        return $language;
    }
}
