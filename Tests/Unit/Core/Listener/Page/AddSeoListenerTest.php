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

use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page\AddSeoListener;
use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Base\BaseListenerTest;

/**
 * AddSeoListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AddSeoListenerTest extends BaseListenerTest
{   
    protected $event;
    protected $testListener;
    protected $pageManager;
    protected $seoManager;
    protected $pageModel;
    protected $languageModel;
    
    protected function setUp() 
    {
        parent::setUp();
        
        $this->seoManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->event = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->languageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlLanguageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->testListener = new AddSeoListener($this->seoManager, $this->languageModel);
    }
    
    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
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
        
        $this->pageModel->expects($this->never())
            ->method('startTransaction');
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
    
    public function testNothingIsAddedWhenAnyLanguageExists()
    {
        $this->pageModel->expects($this->never())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->never())
            ->method('commit');
        
        $this->pageModel->expects($this->never())
            ->method('rollBack');
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array()));
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->never())
            ->method('abort');
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array()));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
    
    public function testSaveFailsWhenAttributesAreNotSaved()
    {
        $page = $this->setUpPage(2);
        $language = $this->setUpLanguage(2);
        
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('rollBack');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array()));
        
        $this->event->expects($this->once())
            ->method('abort');
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));
                
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->seoManager->expects($this->once())
            ->method('save')
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
        
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('rollback');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('abort');
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array()));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));
                
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->seoManager->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
    
    public function testSave()
    {
        $page = $this->setUpPage(2);
        $language = $this->setUpLanguage(2);
        
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('commit');
        
        $this->pageModel->expects($this->never())
            ->method('rollback');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array()));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));
                
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->seoManager->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
    
    public function testSaveFailsWhenAtLeastAtributeIsNotSaved()
    {
        $page = $this->setUpPage(2);
        $language1 = $this->setUpLanguage(2);
        $language2 = $this->setUpLanguage(3);
        
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('rollBack');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array()));
        
        $this->event->expects($this->once())
            ->method('abort');
                
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language1, $language2)));
        
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->seoManager->expects($this->exactly(2))
            ->method('save')
            ->will($this->onConsecutiveCalls(true, false));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
    
    public function testSaveWhenSiteHasMoreLanguages()
    {
        $page = $this->setUpPage(2);
        $language1 = $this->setUpLanguage(2);
        $language2 = $this->setUpLanguage(3);
        
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('commit');
        
        $this->pageModel->expects($this->never())
            ->method('rollback');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array()));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language1, $language2)));
        
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->seoManager->expects($this->exactly(2))
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
}