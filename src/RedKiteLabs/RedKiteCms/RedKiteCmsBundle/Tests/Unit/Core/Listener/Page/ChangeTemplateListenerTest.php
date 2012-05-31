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
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page\ChangeTemplateListener;
use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Base\BaseListenerTest;

/**
 * ChangeTemplateListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ChangeTemplateListenerTest extends BaseListenerTest
{   
    private $event;
    private $testListener;
    private $pageManager;
    private $blockModel;
    private $templateManager;
    private $pageContents;
    private $templateSlotsFactory;
    private $templateChanger;
    
    
    protected function setUp() 
    {
        parent::setUp();
        
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        
        $this->pageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->blockModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->event = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeEditPageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                           ->disableOriginalConstructor()
                            ->getMock();
        
        $this->pageContents = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainer')
                           ->disableOriginalConstructor()
                            ->getMock();
        
        $this->templateSlotsFactory = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsFactory')
                           ->disableOriginalConstructor()
                            ->getMock();
        
        $this->templateChanger = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Changer\AlTemplateChanger')
                           ->disableOriginalConstructor()
                            ->getMock();
        
        $this->templateSlots = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots')
                            ->disableOriginalConstructor()
                            ->getMockForAbstractClass();
        
        $this->testListener = new ChangeTemplateListener($this->templateChanger ,$this->templateSlotsFactory);
    }
    
    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeEditPageCommit($this->event);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testValuesParamIsNotAnArray()
    {
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue('fake'));
        
        $this->testListener->onBeforeEditPageCommit($this->event);
    }
    
    public function testAnythingIsMadeWhenMandatoryValueMisses()
    {
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array('TemplateName' => 'new')));
        
        $this->blockModel->expects($this->never())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->never())
            ->method('commit');
        
        $this->blockModel->expects($this->never())
            ->method('rollBack');
        
        $this->testListener->onBeforeEditPageCommit($this->event);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testTemplateChangingFailsWhenAnUnespectedExceptionIsThrown()
    {
        $this->init();
        
        $this->event->expects($this->once())
            ->method('abort');
        
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->never())
            ->method('commit');
        
        $this->blockModel->expects($this->once())
            ->method('rollBack');
        
        $this->templateChanger->expects($this->once())
            ->method('change')
            ->will($this->throwException(new \RuntimeException));
        
        $this->testListener->onBeforeEditPageCommit($this->event);
    }
    
    public function testTemplateChangingFailsWhenChangeFails()
    {
        $this->init();
        
        $this->event->expects($this->once())
            ->method('abort');
        
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->never())
            ->method('commit');
        
        $this->blockModel->expects($this->once())
            ->method('rollBack');
        
        $this->templateChanger->expects($this->once())
            ->method('change')
            ->will($this->returnValue(false));
        
        $this->testListener->onBeforeEditPageCommit($this->event);
    }
    
    public function testTemplateChanged()
    {
        $this->init();
        
        $this->event->expects($this->never())
            ->method('abort');
        
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('commit');
        
        $this->blockModel->expects($this->never())
            ->method('rollBack');
        
        $this->templateChanger->expects($this->once())
            ->method('change')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeEditPageCommit($this->event);
    }
    
    private function init()
    {
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array('TemplateName' => 'new', 'oldTemplateName' => 'old')));
        
        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $this->templateManager->expects($this->once())
            ->method('getDispatcher')
            ->will($this->returnValue($this->dispatcher));
        
        $this->templateManager->expects($this->once())
            ->method('getPageContentsContainer')
            ->will($this->returnValue($this->pageContents));
        
        $this->templateManager->expects($this->once())
            ->method('getBlockModel')
            ->will($this->returnValue($this->blockModel));
        
        $this->templateManager->expects($this->once())
            ->method('getTemplateSlots')
            ->will($this->returnValue($this->templateSlots));
        
        $this->templateSlots->expects($this->any())
            ->method('getThemeName')
            ->will($this->returnValue("FakeTheme"));
        
        $newTemplateSlots = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots')
                            ->disableOriginalConstructor()
                            ->getMockForAbstractClass();
        
        $this->templateSlotsFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($newTemplateSlots));
        
        $this->templateChanger->expects($this->once())
            ->method('setCurrentTemplateManager')
            ->with($this->templateManager)
            ->will($this->returnSelf());
        
        $this->templateChanger->expects($this->once())
            ->method('setNewTemplateManager')
            ->will($this->returnSelf());
    }
}