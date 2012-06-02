<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageModelation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Page;

use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page\DeletePageBlocksListener;
use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Base\BaseListenerTest;

/**
 * DeletePageBlocksListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class DeletePageBlocksListenerTest extends BaseListenerTest
{   
    protected $event;
    protected $testListener;
    protected $pageManager;
    protected $templateManager;    
    protected $pageContentsContainer;
    protected $pageModel;
    protected $languageModel;
    
    protected function setUp() 
    {
        parent::setUp();
        
        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();        
        
        
        $this->event = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeDeletePageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageContentsContainer = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainer')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->languageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlLanguageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->testListener = new DeletePageBlocksListener($this->languageModel);
    }
    
    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    public function testDeleteFailsWhenAnyLanguageExists()
    {
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array()));
        
        $this->pageModel->expects($this->never())
            ->method('startTransaction');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
                
        $this->event->expects($this->never())
            ->method('abort');
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));        
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    public function testDeleteFailsWhenBlockDeleteFails()
    {
        $page = $this->setUpPage(2);
        $language = $this->setUpLanguage(2);
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));
        
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('rollBack');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
                
        $this->event->expects($this->once())
            ->method('abort');
                
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $this->templateManager->expects($this->once())
            ->method('clearPageBlocks')
            ->will($this->returnValue(false));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testSaveFailsBecauseAndUnespectedExceptionIsThrown()
    {
        $page = $this->setUpPage(2);
        $language = $this->setUpLanguage(2);
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));
        
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('rollback');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('abort');
                                
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $this->templateManager->expects($this->once())
            ->method('clearPageBlocks')
            ->will($this->throwException(new \RuntimeException()));
        
        $this->templateManager->expects($this->any())
            ->method('getPageContentsContainer')
            ->will($this->returnValue($this->pageContentsContainer));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    public function testDeleteWithOneLanguage()
    {
        $page = $this->setUpPage(2);
        $language = $this->setUpLanguage(2);
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));
        
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('commit');
        
        $this->pageModel->expects($this->never())
            ->method('rollback');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
                                
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $this->templateManager->expects($this->once())
            ->method('clearPageBlocks')
            ->will($this->returnValue(true));
        
        $this->templateManager->expects($this->any())
            ->method('getPageContentsContainer')
            ->will($this->returnValue($this->pageContentsContainer));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    public function testDeleteWithMoreLanguagesFailsWhenOneDeleteOperationFails()
    {
        $page = $this->setUpPage(2);
        $language1 = $this->setUpLanguage(2);
        $language2  = $this->setUpLanguage(3);
        
        // Orm
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('rollBack');
        
        // Event
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        // Template manager
        $this->templateManager->expects($this->exactly(2))
            ->method('clearPageBlocks')
            ->will($this->onConsecutiveCalls(true, false));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language1, $language2)));
                
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->pageManager->expects($this->exactly(2))
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    public function testDeleteWithMoreLanguages()
    {
        $page = $this->setUpPage(2);
        $language1 = $this->setUpLanguage(2);
        $language2  = $this->setUpLanguage(3);
        
        // Orm
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('commit');
        
        $this->pageModel->expects($this->never())
            ->method('rollback');
        
        // Event
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language1, $language2)));
        
        // Template manager
        $this->templateManager->expects($this->exactly(2))
            ->method('clearPageBlocks')
            ->will($this->returnValue(true));
        
        // Page manager        
        $this->pageManager->expects($this->exactly(2))
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
}