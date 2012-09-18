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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;

class AlBlockManagerUnitTester extends AlBlockManager
{
    private $defaultValue = null;
    private $hideInEditMode = null;
    private $executeInternalJavascript = null;

    public function setDefaultValue($value)
    {
        $this->defaultValue = $value;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function setHideInEditMode($value)
    {
        $this->hideInEditMode = $value;
    }

    public function getHideInEditMode()
    {
        return (null === $this->hideInEditMode) ? parent::getHideInEditMode() : $this->hideInEditMode;
    }

    public function setExecuteInternalJavascript($value)
    {
        $this->executeInternalJavascript = $value;
    }

    public function getExecuteInternalJavascript()
    {
        return (null === $this->executeInternalJavascript) ? parent::getExecuteInternalJavascript() :$this->executeInternalJavascript;
    }
}

/**
 * AlBlockManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerTest extends TestCase
{
    private $dispatcher;
    private $blockManager;

    protected function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->blockManager = new AlBlockManagerUnitTester($this->dispatcher, $this->factoryRepository, $this->validator);
        $this->blockManager->setDefaultValue(array("HtmlContent" => "Test value"));
    }

    public function testFactoryRepositoryInjectedBySetters()
    {
        $factoryRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepository')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->assertEquals($this->blockManager, $this->blockManager->setFactoryRepository($factoryRepository));
        $this->assertEquals($factoryRepository, $this->blockManager->getFactoryRepository());
        $this->assertNotSame($this->dispatcher, $this->blockManager->getFactoryRepository());
    }

    public function testGetBlockRepository()
    {
        $this->assertEquals($this->blockRepository, $this->blockManager->getBlockRepository());
    }

    public function testReloadSuggested()
    {
        $this->assertFalse($this->blockManager->getReloadSuggested());
    }

    public function testExecuteInternalJavascript()
    {
        $this->assertTrue($this->blockManager->getExecuteInternalJavascript());
    }

    public function testGetHideInEditMode()
    {
        $this->assertFalse($this->blockManager->getHideInEditMode());
    }

    public function testGetHtmlContentForEditor()
    {
        $this->assertEmpty($this->blockManager->getHtmlContentForEditor());
    }

    public function testHtmlCmsActiveReturnsTheBlockContentWhenTheInternalJavascriptIsNotSetAndTheContentIsNotHideInEditMode()
    {
        $htmlContent = '<p>A great App-Bundle</p>';
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getHtmlContent')
            ->will($this->returnValue($htmlContent));
        $this->blockManager->set($block);

        $this->assertEquals($htmlContent, $this->blockManager->getHtmlCmsActive());
    }

    public function testHtmlCmsActiveReturnsTheBlockContentAndAnExtraJavascriptWhenTheContentIsHideInEditMode()
    {
        $htmlContent = '<p>A great App-Bundle</p>';
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));

        $block->expects($this->once())
            ->method('getHtmlContent')
            ->will($this->returnValue($htmlContent));
        $blockManager = new AlBlockManagerUnitTester($this->dispatcher, $this->factoryRepository, $this->validator);
        $blockManager->setHideInEditMode(true);
        $blockManager->set($block);

        $extraJavascript = '<script type="text/javascript">$(document).ready(function(){$(\'#block_2\').data(\'block\', $(\'#block_2\').html());});</script>';
        $this->assertEquals($htmlContent . $extraJavascript, $blockManager->getHtmlCmsActive());
    }

    public function testHtmlCmsActiveReturnsTheBlockContentAndTheInternalJavascript()
    {
        $htmlContent = '<p>A great App-Bundle</p>';
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $block->expects($this->once())
            ->method('getHtmlContent')
            ->will($this->returnValue($htmlContent));

        $block->expects($this->once())
            ->method('getInternalJavascript')
            ->will($this->returnValue('a great javascript'));
        $blockManager = new AlBlockManagerUnitTester($this->dispatcher, $this->factoryRepository, $this->validator);
        $blockManager->set($block);

        $extraJavascript = '<script type="text/javascript">$(document).ready(function(){try {' . PHP_EOL;
        $extraJavascript .= 'a great javascript' . PHP_EOL;
        $extraJavascript .= '} catch (e) {' . PHP_EOL;
        $extraJavascript .= 'alert(\'The javascript added to the slot  has been generated an error, which reports: \' + e);' . PHP_EOL;
        $extraJavascript .= '}' . PHP_EOL;
        $extraJavascript .= '});</script>';
        $this->assertEquals($htmlContent . $extraJavascript, $blockManager->getHtmlCmsActive());
    }

    public function testHtmlCmsActiveReturnsJustTheBlockContentBecauseExecuteInternalJavascriptIsFalse()
    {
        $htmlContent = '<p>A great App-Bundle</p>';
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $block->expects($this->once())
            ->method('getHtmlContent')
            ->will($this->returnValue($htmlContent));

        $block->expects($this->once())
            ->method('getInternalJavascript')
            ->will($this->returnValue('a great javascript'));
        $blockManager = new AlBlockManagerUnitTester($this->dispatcher, $this->factoryRepository, $this->validator);
        $blockManager->set($block);
        $blockManager->setExecuteInternalJavascript(false);

        $this->assertEquals($htmlContent, $blockManager->getHtmlCmsActive());
    }

    public function testGetInternalJavascriptSafeMode()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $block->expects($this->once())
            ->method('getInternalJavascript')
            ->will($this->returnValue('a great javascript'));
        $blockManager = new AlBlockManagerUnitTester($this->dispatcher, $this->factoryRepository, $this->validator);
        $blockManager->set($block);
        $expectedJavascript = 'try {' . PHP_EOL;
        $expectedJavascript .= 'a great javascript' . PHP_EOL;
        $expectedJavascript .= '} catch (e) {' . PHP_EOL;
        $expectedJavascript .= 'alert(\'The javascript added to the slot  has been generated an error, which reports: \' + e);' . PHP_EOL;
        $expectedJavascript .= '}' . PHP_EOL;
        $this->assertEquals($expectedJavascript, $blockManager->getInternalJavascript());
    }

    public function testGetInternalJavascriptUnsafeMode()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $block->expects($this->once())
            ->method('getInternalJavascript')
            ->will($this->returnValue('a great javascript'));
        $blockManager = new AlBlockManagerUnitTester($this->dispatcher, $this->factoryRepository, $this->validator);
        $blockManager->set($block);
        $this->assertEquals('a great javascript', $blockManager->getInternalJavascript(false));
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     */
    public function testSetFailsWhenANotValidPropelObjectIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->blockManager->set($block);
    }

    public function testSetANullAlBlock()
    {
        $this->blockManager->set(null);
        $this->assertNull($this->blockManager->get());
    }

    public function testSetAlBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $this->blockManager->set($block);

        $this->assertEquals($block, $this->blockManager->get());
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyParametersException
     */
    public function testAddFailsWhenAnyParamIsGiven()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));

        $params = array();
        $this->blockManager->save($params);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testAddFailsWhenAnyExpectedParamIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->validator->expects($this->once())
            ->method('checkRequiredParamsExists')
            ->will($this->throwException(new General\ParameterExpectedException()));

        $params = array('Fake' => 'content');
        $this->blockManager->set($block);
        $this->blockManager->save($params);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testAddFailsWhenOneExpectedParamIsMissing()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->validator->expects($this->once())
            ->method('checkRequiredParamsExists')
            ->will($this->throwException(new General\ParameterExpectedException()));

        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "HtmlContent" => 'Fake content',
                        "ClassName" => "Text");

        $this->blockManager->save($params);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     */
    public function testAddFailsWhenTheDefaultValueDoesNotReturnAnArray()
    {
        $blockManager = new AlBlockManagerUnitTester($this->dispatcher, $this->factoryRepository, $this->validator);

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");

        $blockManager->save($params);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testAddFailsWhenTheDefaultValueHasAnyOfTheRequiredOptions()
    {
        $blockManager = new AlBlockManagerUnitTester($this->dispatcher, $this->factoryRepository, $this->validator);
        $blockManager->setDefaultValue(array("Fake" => "Test value"));

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->validator->expects($this->once())
            ->method('checkOnceValidParamExists')
            ->will($this->throwException(new General\ParameterExpectedException()));

        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");

        $blockManager->save($params);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddBlockThrownAnUnespectedException()
    {
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block)
                ->will($this->returnSelf());

        $this->blockManager->set($block);
        $this->blockManager->save($params);
    }

    public function testSaveBlockDuringAddFails()
    {
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block)
                ->will($this->returnSelf());

        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(false, $result);
    }

    public function testAdd()
    {
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block)
                ->will($this->returnSelf());

        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(true, $result);
    }

    public function testAddInstantiatesAnEmptyBlockObject()
    {
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");

        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->will($this->returnSelf());

        $this->blockRepository->expects($this->once())
                ->method('getRepositoryObjectClassName')
                ->will($this->returnValue('\AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock'));

        $this->blockManager->set(null);
        $result = $this->blockManager->save($params);
        $this->assertEquals(true, $result);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyParametersException
     */
    public function testEditFailsWhenAnyParamIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));

        $this->blockRepository->expects($this->never())
                ->method('setRepositoryObject');

        $params = array();
        $this->blockManager->set($block);
        $this->blockManager->save($params);
    }

    public function testSaveBlockDuringEditFails()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));

         $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block);

        $params = array('HtmlContent' => 'changed html content' );
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(false, $result);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEditBlockThrownAnUnespectedException()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $params = array('HtmlContent' => 'changed html content' );

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block)
                ->will($this->returnSelf());

        $this->blockManager->set($block);
        $this->blockManager->save($params);
    }

    public function testEdit()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $block->expects($this->once())
                ->method('getHtmlContent')
                ->will($this->returnValue('changed html content'));

        $block->expects($this->once())
                ->method('getInternalJavascript')
                ->will($this->returnValue('changed internal javascript content'));

        $block->expects($this->once())
                ->method('getExternalJavascript')
                ->will($this->returnValue('changed external javascript content'));

        $block->expects($this->once())
                ->method('getInternalStylesheet')
                ->will($this->returnValue('changed internal stylesheet content'));

        $block->expects($this->once())
                ->method('getExternalStylesheet')
                ->will($this->returnValue('changed external stylesheet content'));

        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));

         $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block);

        $params = array('HtmlContent' => 'changed html content',
            'InternalJavascript' => 'changed internal javascript content',
            'ExternalJavascript' => 'changed external javascript content',
            'InternalJavascript' => 'changed internal stylesheet content',
            'ExternalStylesheet' => 'changed external stylesheet content',
            );
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(true, $result);
        $this->assertEquals('changed html content', $this->blockManager->get()->getHtmlContent());
        $this->assertEquals('changed internal javascript content', $this->blockManager->get()->getInternalJavascript());
        $this->assertEquals('changed external javascript content', $this->blockManager->get()->getExternalJavascript());
        $this->assertEquals('changed internal stylesheet content', $this->blockManager->get()->getInternalStylesheet());
        $this->assertEquals('changed external stylesheet content', $this->blockManager->get()->getExternalStylesheet());
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testDeleteBlockFailsWhenAnyBlockIsSet()
    {
        $this->dispatcher->expects($this->never())
            ->method('dispatch');

        $this->blockManager->set(null);
        $this->blockManager->delete();
    }

    public function testSaveBlockDuringDeleteFails()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block)
                ->will($this->returnSelf());

        $this->blockRepository->expects($this->any())
                ->method('delete')
                ->will($this->returnValue(false));

        $this->blockManager->set($block);
        $result = $this->blockManager->delete();
        $this->assertEquals(false, $result);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDeleteBlockThrownAnUnespectedException()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $this->blockRepository->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \RuntimeException()));

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block)
                ->will($this->returnSelf());

        $this->blockManager->set($block);
        $this->blockManager->delete();
    }

    public function testDeleteBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $block->expects($this->any())
                ->method('getToDelete')
                ->will($this->returnValue(1));

        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block)
                ->will($this->returnSelf());

        $this->blockRepository->expects($this->any())
                ->method('delete')
                ->will($this->returnValue(true));

        $this->blockManager->set($block);
        $result = $this->blockManager->delete();
        $this->assertEquals(true, $result);
        $this->assertEquals(1, $this->blockManager->get()->getToDelete());
    }

    public function testToArrayReturnsAnEmptyArrayWhenAnyBlockHasBeenSet()
    {
        $array = $this->blockManager->toArray();

        $this->assertEmpty($array);

    }

    public function testAlBlockToArrayReturnsAnEmptyArrayWhenBlockIsNull()
    {
        $this->blockManager->set(null);
        $array = $this->blockManager->toArray();

        $this->assertEmpty($array);
    }

    public function testAlBlockToArray()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getHtmlContent')
                ->will($this->returnValue('my fancy content'));

        $this->blockManager->set($block);
        $array = $this->blockManager->toArray();

        $this->assertTrue(array_key_exists('HideInEditMode', $array));
        $this->assertTrue(array_key_exists('HtmlContent', $array));
        $this->assertTrue(array_key_exists('ExternalJavascript', $array));
        $this->assertTrue(array_key_exists('InternalJavascript', $array));
        $this->assertTrue(array_key_exists('ExternalStylesheet', $array));
        $this->assertTrue(array_key_exists('InternalStylesheet', $array));
        $this->assertTrue(array_key_exists('Block', $array));

        $this->assertEquals('my fancy content', $array['HtmlContent']);
    }

    private function setRepositoryObjectMethods($block)
    {
        $this->blockRepository->expects($this->any())
            ->method('setRepositoryObject')
            ->with($block)
            ->will($this->returnSelf());

        $this->blockRepository->expects($this->any())
            ->method('getModelObject')
            ->will($this->returnValue($block));
    }
}