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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Language;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;

/**
 * AlLanguageManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlLanguageManagerTest extends TestCase
{
    private $dispatcher;
    private $languageManager;
    private $templateManager;

    protected function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorLanguageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlLanguageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageModel->expects($this->any())
            ->method('getModelObjectClassName')
            ->will($this->returnValue('\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage'));

        $this->languageManager = new AlLanguageManager($this->dispatcher, $this->languageModel, $this->validator);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     */
    public function testSetFailsWhenANotValidPropelObjectIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $this->languageManager->set($block);
    }

    public function testSetANullAlPageObject()
    {
        $this->languageManager->set(null);
        $this->assertNull($this->languageManager->get());
    }

    public function testSetAlPageObject()
    {
        $language =$this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $this->languageManager->set($language);
        $this->assertEquals($language, $this->languageManager->get());
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyParametersException
     */
    public function testAddFailsWhenAnyParamIsGiven()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));

        $values = array();
        $this->languageManager->save($values);
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testAddFailsWhenAnyExpectedParamIsGiven()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->validator->expects($this->once())
            ->method('checkRequiredParamsExists')
            ->will($this->throwException(new General\ParameterExpectedException()));

        $values = array('fake' => 'value');

        $this->languageManager->save($values);
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language\LanguageExistsException
     */
    public function testAddThrownAnExceptionWhenTheLanguageAlreadyExists()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->validator->expects($this->once())
            ->method('languageExists')
            ->will($this->returnValue(true));

        $this->languageModel->expects($this->never())
                ->method('save');

        $params = array('Language'  => 'en');
        $this->languageManager->save($params);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddThrownAnUnespectedException()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('rollback');

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('save')
                ->will($this->throwException(new \RuntimeException()));

        $params = array('Language'  => 'en');
        $this->languageManager->save($params);
    }

    public function testAddNewLanguageFailsBecauseSaveFailsAtLast()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('rollback');

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('save')
                ->will($this->returnValue(false));

        $params = array('Language'  => 'en');
        $this->assertFalse($this->languageManager->save($params));
    }

    public function testAddLanguage()
    {
        $this->dispatcher->expects($this->exactly(3))
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollback');

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));

        $params = array('Language'  => 'en');
        $this->assertTrue($this->languageManager->save($params));
    }

    public function testAddMainLanguageFailsBecauseMainLanguageHasNotBeenResetted()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('rollback');

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('activeLanguages')
                ->will($this->returnValue(true));

        $this->languageModel->expects($this->once())
                ->method('save')
                ->will($this->returnValue(false));

        $this->languageModel->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage')));

        $params = array('Language'  => 'en', 'MainLanguage' => 1);
        $this->assertFalse($this->languageManager->save($params));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddMainLanguageFailsBecauseAnUnexpectedExceptionIsThrownWhenTheMainLanguageIsResetted()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->exactly(2))
            ->method('rollback');

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('activeLanguages')
                ->will($this->returnValue(true));

        $this->languageModel->expects($this->once())
                ->method('save')
                ->will($this->throwException(new \RuntimeException()));

        $this->languageModel->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage')));

        $params = array('Language'  => 'en', 'MainLanguage' => 1);
        $this->assertFalse($this->languageManager->save($params));
    }

    public function testAddMainLanguageFailsBecauseMainLanguageHasBeenResettedButSaveFailsAtLast()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('rollback');

        $this->languageModel->expects($this->exactly(2))
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('activeLanguages')
                ->will($this->returnValue(true));

        $this->languageModel->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage')));

        $this->languageModel->expects($this->exactly(2))
                ->method('save')
                ->will($this->onConsecutiveCalls(true, false));

        $params = array('Language'  => 'en', 'MainLanguage' => 1);
        $this->assertFalse($this->languageManager->save($params));
    }

    public function testAddMainLanguage()
    {
        $this->dispatcher->expects($this->exactly(3))
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollback');

        $this->languageModel->expects($this->exactly(2))
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('activeLanguages')
                ->will($this->returnValue(true));

        $this->languageModel->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage')));

        $this->languageModel->expects($this->exactly(2))
                ->method('save')
                ->will($this->returnValue(true));

        $params = array('Language'  => 'en', 'MainLanguage' => 1);
        $this->assertTrue($this->languageManager->save($params));
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyParametersException
     */
    public function testEditFailsWhenAnyParamIsGiven()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));

        $this->languageModel->expects($this->never())
            ->method('save');

        $params = array();
        $this->languageManager->save($params);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testEditFailsWhenAnyoneOfTheExpectedParamIsGiven()
    {
        $language = $this->setUpLanguageObject();

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->validator->expects($this->once())
            ->method('checkOnceValidParamExists')
            ->will($this->throwException(new General\ParameterExpectedException()));

        $this->languageModel->expects($this->never())
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

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('rollback');

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('save')
                ->will($this->throwException(new \RuntimeException()));

        $params = array('Language'  => 'fr');
        $this->languageManager->set($language);
        $this->languageManager->save($params);
    }

    public function testEditFailsBecauseSaveFailsAtLast()
    {
        $language =$this->setUpLanguageObject();

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('rollback');

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('save')
                ->will($this->returnValue(false));

        $params = array('Language'  => 'fr');
        $this->languageManager->set($language);
        $this->assertFalse($this->languageManager->save($params));
    }

    public function testEditDoesNothingWhenTheSameParameterIsGiven()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getLanguage')
            ->will($this->returnValue('en'));

        $this->languageModel->expects($this->never())
            ->method('save');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->never())
            ->method('commit');

        $this->languageModel->expects($this->once())
            ->method('rollback');

        $params = array('Language' => 'en');
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertFalse($res);
    }

    public function testEdit()
    {
        $this->dispatcher->expects($this->exactly(3))
            ->method('dispatch');

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getLanguage')
            ->will($this->returnValue('en'));

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollback');

        $params = array('Language' => 'fr');
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertTrue($res);
    }

    public function testEditMainLanguageFailsWhenResetMainLanguageFails()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getMainLanguage')
            ->will($this->returnValue(1));

        $this->languageModel->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage')));

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
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
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getMainLanguage')
            ->will($this->returnValue(1));

        $this->languageModel->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage')));

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->exactly(2))
            ->method('rollback');

        $params = array('MainLanguage' => 1);
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertFalse($res);
    }

    public function testEditMainLanguageFails()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getMainLanguage')
            ->will($this->returnValue(1));

        $this->languageModel->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage')));

        $this->languageModel->expects($this->exactly(2))
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->exactly(2))
            ->method('save')
            ->will($this->onConsecutiveCalls(true, false));

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('rollback');

        $params = array('MainLanguage' => 1);
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertFalse($res);
    }

    public function testEditMainLanguage()
    {
        $this->dispatcher->expects($this->exactly(3))
            ->method('dispatch');

        $language =$this->setUpLanguageObject();
        $language->expects($this->any())
            ->method('getMainLanguage')
            ->will($this->returnValue(1));

        $this->languageModel->expects($this->once())
                ->method('mainLanguage')
                ->will($this->returnValue($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage')));

        $this->languageModel->expects($this->exactly(2))
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->exactly(2))
            ->method('save')
            ->will($this->returnValue(true));

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollback');

        $params = array('MainLanguage' => 1);
        $this->languageManager->set($language);
        $res = $this->languageManager->save($params);
        $this->assertTrue($res);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testDeleteFailsWhenTheManagedLanguageIsNull()
    {
        $this->dispatcher->expects($this->never())
            ->method('dispatch');

        $this->languageManager->set(null);
        $this->languageManager->delete();
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language\RemoveMainLanguageException
     */
    public function testTryingToDeleteTheMainLanguageThrowsAnException()
    {
        $language =$this->setUpLanguageObject();

        $this->languageModel->expects($this->never())
                ->method('delete');

        $this->languageModel->expects($this->once())
                ->method('mainLanguage')
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

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('delete')
                ->will($this->throwException(new \RuntimeException()));

        $this->languageModel->expects($this->once())
            ->method('rollBack');

        $this->languageManager->set($language);
        $this->languageManager->delete();
    }

    public function testDeleteFailsBecauseSaveFailsAtLast()
    {
        $language =$this->setUpLanguageObject();

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(false));

        $this->languageModel->expects($this->once())
            ->method('rollBack');

        $this->languageManager->set($language);
        $res = $this->languageManager->delete();
        $this->assertFalse($res);
    }

    public function testDelete()
    {
        $language =$this->setUpLanguageObject();

        $this->dispatcher->expects($this->exactly(3))
            ->method('dispatch');

        $this->languageModel->expects($this->once())
            ->method('startTransaction');

        $this->languageModel->expects($this->once())
                ->method('setModelObject')
                ->will($this->returnSelf());

        $this->languageModel->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $this->languageModel->expects($this->once())
            ->method('commit');

        $this->languageModel->expects($this->never())
            ->method('rollback');

        $this->languageManager->set($language);
        $res = $this->languageManager->delete();
        $this->assertTrue($res);
    }

    /**/
    private function setUpLanguageObject()
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        return $language;
    }
}