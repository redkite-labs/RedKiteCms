<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Base\ContentManagerBase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General;

class BlockManagerUnitTester extends BlockManager
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
    
    public function getPageTree()
    {
        return $this->pageTree; 
    }
}

/**
 * BlockManagerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagerTest extends ContentManagerBase
{
    private $blockManager;

    protected function setUp()
    {
        parent::setUp();

        $this->validator = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\BlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->blockManager = new BlockManagerUnitTester($this->eventsHandler, $this->factoryRepository, $this->validator);
        $this->blockManager->setDefaultValue(array("Content" => "Test value"));
    }
    
    public function testEditorDisable()
    {
        $this->assertFalse($this->blockManager->getEditorDisabled());
        $this->blockManager->setEditorDisabled(true);
        $this->assertTrue($this->blockManager->getEditorDisabled());
    }

    public function testPageTreeInjectedBySetters()
    {
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->assertSame($this->blockManager->getPageTree(), $this->blockManager->setPageTree($pageTree));
    }
    
    public function testFactoryRepositoryInjectedBySetters()
    {
        $factoryRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepository')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->assertSame($this->blockManager, $this->blockManager->setFactoryRepository($factoryRepository));
        $this->assertSame($factoryRepository, $this->blockManager->getFactoryRepository());
        $this->assertNotSame($this->factoryRepository, $this->blockManager->getFactoryRepository());
    }
    
    public function testEditorParametersIsEmptyByDefault()
    {
        $this->assertEmpty($this->blockManager->editorParameters());
    }
    
    public function testBlockManagerIsNotInternalByDefault()
    {
        $this->assertFalse($this->blockManager->getIsInternalBlock());
    }
    
    public function testBlockManagerDoesNotContainMetatagsByDefault()
    {
        $this->assertNull($this->blockManager->getMetaTags());
    }

    public function testGetBlockRepository()
    {
        $this->assertEquals($this->blockRepository, $this->blockManager->getBlockRepository());
    }

    public function testReloadSuggested()
    {
        $this->assertFalse($this->blockManager->getReloadSuggested());
    }

    public function testGetHideInEditMode()
    {
        $this->assertFalse($this->blockManager->getHideInEditMode());
    }

    public function testGetContentForEditor()
    {
        $this->assertEquals($this->blockManager->getContentForEditor(), $this->blockManager->getHtml());
    }

    public function testDefaultContentIsReturnedWhenTheInternalJavascriptIsNotSetAndTheContentIsNotHideInEditMode()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $this->blockManager->set($block);       
        $blockManagerArray = $this->blockManager->toArray();
        $this->assertEquals($this->blockManager->getHtml(), $blockManagerArray['Content']);
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     */
    public function testSetFailsWhenANotValidPropelObjectIsGiven()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page');
        $this->blockManager->set($block);
    }

    public function testSetANullBlock()
    {
        $this->blockManager->set(null);
        $this->assertNull($this->blockManager->get());
    }

    public function testSetBlock()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $this->blockManager->set($block);

        $this->assertEquals($block, $this->blockManager->get());
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     */
    public function testAddFailsWhenAnyParamIsGiven()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyArgumentsException()));

        $params = array();
        $this->blockManager->save($params);
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     */
    public function testAddFailsWhenAnyExpectedParamIsGiven()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $this->setUpEventsHandler($event);

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');

        $this->validator->expects($this->once())
            ->method('checkRequiredParamsExists')
            ->will($this->throwException(new General\ArgumentExpectedException()));

        $params = array('Fake' => 'content');
        $this->blockManager->set($block);
        $this->blockManager->save($params);
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     */
    public function testAddFailsWhenOneExpectedParamIsMissing()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkRequiredParamsExists')
            ->will($this->throwException(new General\ArgumentExpectedException()));

        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "Content" => 'Fake content',
                        "Type" => "Text");

        $this->blockManager->save($params);
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     */
    public function testAddFailsWhenTheDefaultValueDoesNotReturnAnArray()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $this->setUpEventsHandler($event);

        $blockManager = new BlockManagerUnitTester($this->eventsHandler, $this->factoryRepository, $this->validator);
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "Type" => "Text");

        $blockManager->save($params);
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     */
    public function testAddFailsWhenTheDefaultValueHasAnyOfTheRequiredOptions()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $this->setUpEventsHandler($event);

        $blockManager = new BlockManagerUnitTester($this->eventsHandler, $this->factoryRepository, $this->validator);
        $blockManager->setDefaultValue(array("Fake" => "Test value"));

        $this->validator->expects($this->once())
            ->method('checkOnceValidParamExists')
            ->will($this->throwException(new General\ArgumentExpectedException()));

        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "Type" => "Text");

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
                        "Type" => "Text");

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $this->setUpEventsHandler($event);

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');

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
                        "Type" => "Text");

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $this->setUpEventsHandler($event);

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');

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
                        "Type" => "Text");

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $this->setUpEventsHandler($event, 2);

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');

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
                        "Type" => "Text");

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $this->setUpEventsHandler($event, 2);

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
                ->will($this->returnValue('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block'));

        $this->blockManager->set(null);
        $result = $this->blockManager->save($params);
        $this->assertEquals(true, $result);
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage exception_block_adding_aborted
     */
    public function testAddActionIsInterruptedWhenEventHasBeenAborted()
    {
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "Type" => "Text");

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');

        $this->blockRepository->expects($this->never())
            ->method('startTransaction');

        $this->blockRepository->expects($this->never())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollBack');

        $this->blockRepository->expects($this->never())
            ->method('save');

        $this->blockRepository->expects($this->never())
                ->method('setRepositoryObject');

        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(false, $result);
    }

    public function testAddParametersHaveBeenChangedByAnEvent()
    {
        $changedParams = array(
            "PageId" => 2,
            "LanguageId" => 2,
            "SlotName" => 'test',
            "Type" => "Text",
            "Content" => "My new content"
        );

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent');
        $event->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue($changedParams));

        $this->setUpEventsHandler($event, 2);

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->with($changedParams)
            ->will($this->returnValue(true));

        $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block)
                ->will($this->returnSelf());

        $params = array(
            "PageId" => 2,
            "LanguageId" => 2,
            "SlotName" => 'test',
            "Type" => "Text",
            "Content" => "My content"
        );

        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(true, $result);
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     */
    public function testEditFailsWhenAnyParamIsGiven()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockEditingEvent');
        $this->setUpEventsHandler($event);

        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyArgumentsException()));

        $this->blockRepository->expects($this->never())
                ->method('setRepositoryObject');

        $params = array();
        $this->blockManager->set($block);
        $this->blockManager->save($params);
    }

    public function testSaveBlockDuringEditFails()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockEditingEvent');
        $this->setUpEventsHandler($event);

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

        $params = array('Content' => 'changed html content' );
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(false, $result);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEditBlockThrownAnUnespectedException()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $params = array('Content' => 'changed html content' );

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockEditingEvent');
        $this->setUpEventsHandler($event);

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
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $block->expects($this->once())
                ->method('getContent')
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

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockEditingEvent');
        $this->setUpEventsHandler($event, 2);

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

        $params = array('Content' => 'changed html content',
            'InternalJavascript' => 'changed internal javascript content',
            'ExternalJavascript' => 'changed external javascript content',
            'InternalJavascript' => 'changed internal stylesheet content',
            'ExternalStylesheet' => 'changed external stylesheet content',
            );
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(true, $result);
        $this->assertEquals('changed html content', $this->blockManager->get()->getContent());
        $this->assertEquals('changed internal javascript content', $this->blockManager->get()->getInternalJavascript());
        $this->assertEquals('changed external javascript content', $this->blockManager->get()->getExternalJavascript());
        $this->assertEquals('changed internal stylesheet content', $this->blockManager->get()->getInternalStylesheet());
        $this->assertEquals('changed external stylesheet content', $this->blockManager->get()->getExternalStylesheet());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage exception_block_editing_aborted
     */
    public function testEditActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockEditingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $this->blockRepository->expects($this->never())
            ->method('startTransaction');

        $this->blockRepository->expects($this->never())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->never())
                ->method('save');

        $this->blockRepository->expects($this->never())
                ->method('setRepositoryObject');

        $params = array('Content' => 'changed html content',
            );
        $this->blockManager->set($block);
        $this->blockManager->save($params);
    }

    public function testEditParametersHaveBeenChangedByAnEvent()
    {
        $changedParams = array(
            "Content" => "My new content"
        );

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockEditingEvent');
        $event->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue($changedParams));
        $this->setUpEventsHandler($event, 2);

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $block->expects($this->once())
                ->method('getContent')
                ->will($this->returnValue('My new content'));

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
                ->method('save')
                ->with($changedParams)
                ->will($this->returnValue(true));

         $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block);

        $params = array('Content' => 'changed html content',
            );
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params);
        $this->assertEquals(true, $result);
        $this->assertEquals('My new content', $this->blockManager->get()->getContent());
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     */
    public function testDeleteBlockFailsWhenAnyBlockIsSet()
    {
        $this->eventsHandler->expects($this->never())
            ->method('dispatch');

        $this->blockManager->set(null);
        $this->blockManager->delete();
    }

    public function testSaveBlockDuringDeleteFails()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent');
        $this->setUpEventsHandler($event);

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
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent');
        $this->setUpEventsHandler($event);

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
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $block->expects($this->any())
                ->method('getToDelete')
                ->will($this->returnValue(1));

        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent');
        $this->setUpEventsHandler($event, 2);

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

    /**
     * @expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Event\EventAbortedException
     * @expectedExceptionMessage exception_block_removing_aborted
     */
    public function testDeleteActionIsInterruptedWhenEventHasBeenAborted()
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent');
        $event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        $this->setUpEventsHandler($event);

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $this->blockRepository->expects($this->never())
                ->method('setRepositoryObject');

        $this->blockRepository->expects($this->never())
                ->method('delete');

        $this->blockManager->set($block);
        $this->blockManager->delete();
    }

    public function testToArrayReturnsAnEmptyArrayWhenAnyBlockHasBeenSet()
    {
        $array = $this->blockManager->toArray();

        $this->assertEmpty($array);

    }

    public function testBlockToArrayReturnsAnEmptyArrayWhenBlockIsNull()
    {
        $this->blockManager->set(null);
        $array = $this->blockManager->toArray();

        $this->assertEmpty($array);
    }

    public function testBlockToArray()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $this->blockManager->set($block);
        $array = $this->blockManager->toArray();

        $this->assertTrue(array_key_exists('HideInEditMode', $array));
        $this->assertTrue(array_key_exists('Content', $array));
        $this->assertTrue(array_key_exists('Block', $array));   

        $this->assertTrue(is_array($array['Content']));
    }
}