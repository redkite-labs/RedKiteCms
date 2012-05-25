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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page\EditSeoListener;

/**
 * EditSeoListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class EditSeoListenerTest extends Base\BaseListenerTest
{   
    private $event;
    private $testListener;
    private $pageManager;
    private $seoManager;
    private $pageModel;
    private $seoModel;
    private $templateManager;
    private $pageContents;
    
    
    protected function setUp() 
    {
        parent::setUp();
        
        $this->seoManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->seoModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlSeoModelPropel')
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
        
        $this->testListener = new EditSeoListener($this->seoManager);
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
        
    public function testAnythingIsMadeWhenTheSeoObjectIsNotFound()
    {
        $this->pageModel->expects($this->never())
            ->method('startTransaction');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array()));
        
        $this->event->expects($this->never())
            ->method('abort');
        
        $this->setUpCommonObjects();
        
        $this->seoManager->expects($this->never())
            ->method('save');
        
        $this->seoModel->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue(null));
        
        $this->testListener->onBeforeEditPageCommit($this->event);
    }
    
    public function testSaveFailsWhenAttributesAreNotSaved()
    {
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
        
        $this->setUpCommonObjects();
        
        $this->seoManager->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));
        
        $seo= $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');  
        $this->seoModel->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($seo));
        
        $this->testListener->onBeforeEditPageCommit($this->event);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testSaveFailsBecauseAndUnespectedExceptionIsThrown()
    {
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
        
        $this->setUpCommonObjects();
        
        $this->seoManager->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));
        
        $seo= $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');  
        $this->seoModel->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($seo));
        
        $this->testListener->onBeforeEditPageCommit($this->event);
    }
    
    public function testSave()
    {
        $this->pageModel->expects($this->once())
            ->method('startTransaction');
        
        $this->pageModel->expects($this->once())
            ->method('commit');
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array()));
        
        $this->event->expects($this->never())
            ->method('abort');
        
        $this->setUpCommonObjects();
        
        $this->seoManager->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));
        
        $seo= $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');  
        $this->seoModel->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($seo));
        
        $this->testListener->onBeforeEditPageCommit($this->event);
    }
    
    private function setUpCommonObjects()
    {
        $this->templateManager->expects($this->once())
            ->method('getPageContentsContainer')
            ->will($this->returnValue($this->pageContents));
        
        $this->pageContents->expects($this->once())
            ->method('getIdLanguage')
            ->will($this->returnValue(2));
        
        $this->pageManager->expects($this->once())
            ->method('getPageModel')
            ->will($this->returnValue($this->pageModel));
        
        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');        
        $page->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));
        
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->seoManager->expects($this->once())
            ->method('getSeoModel')
            ->will($this->returnValue($this->seoModel));
    }
}