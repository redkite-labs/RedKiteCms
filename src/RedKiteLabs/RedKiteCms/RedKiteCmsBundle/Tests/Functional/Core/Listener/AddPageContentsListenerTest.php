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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Page;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page\AddPageContentsListener;

class AddPageContentsListenerTest extends TestCase
{   
    private $event;
    private $testListener;
    private $pageManager;
    private $templateManager;
    private $validator;
    
    protected function setUp() 
    {
        parent::setUp();
        
        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->event = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->testListener = new AddPageContentsListener($this->templateManager);
    }
    
    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
        
    public function testSaveFailsWhenContentsAreNotSaved()
    {
        $this->connection->expects($this->once())
            ->method('beginTransaction')
            ->will($this->returnValue($this->pageManager));
        
        $this->connection->expects($this->once())
            ->method('rollback')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
                
        $this->event->expects($this->once())
            ->method('abort');
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
        
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
        
        $this->validator->expects($this->once())
            ->method('getSiteLanguages')
            ->will($this->returnValue(array($language)));
        
        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));
        
        $this->pageManager->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($this->connection));
        
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->templateManager->expects($this->once())
            ->method('populate')
            ->will($this->returnValue(false));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
    
    public function testSave()
    {
        $this->connection->expects($this->once())
            ->method('beginTransaction')
            ->will($this->returnValue($this->pageManager));
        
        $this->connection->expects($this->once())
            ->method('commit')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
                
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
        
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
                
        $this->validator->expects($this->once())
            ->method('getSiteLanguages')
            ->will($this->returnValue(array($language)));
        
        $this->pageManager->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($this->connection));
        
        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));
        
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->templateManager->expects($this->once())
            ->method('populate')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
    
    public function testSaveFailsWhenAtLeastAtributeIsNotSaved()
    {
        $this->connection->expects($this->once())
            ->method('beginTransaction')
            ->will($this->returnValue($this->pageManager));
        
        $this->connection->expects($this->once())
            ->method('rollback')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
                
        $this->event->expects($this->once())
            ->method('abort');
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->exactly(2))
            ->method('getId')
            ->will($this->returnValue(2));
        
        $language1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language1->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
        
        $language2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language2->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(3));
                
        $this->validator->expects($this->once())
            ->method('getSiteLanguages')
            ->will($this->returnValue(array($language1, $language2)));
        
        $this->pageManager->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($this->connection));
        
        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));
        
        $this->pageManager->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->templateManager->expects($this->exactly(2))
            ->method('populate')
            ->will($this->onConsecutiveCalls(true, false));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
    
    public function testSaveWhenSiteHasMoreLanguages()
    {
        $this->connection->expects($this->once())
            ->method('beginTransaction')
            ->will($this->returnValue($this->pageManager));
        
        $this->connection->expects($this->once())
            ->method('commit')
            ->will($this->returnValue($this->pageManager));
        
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
                       
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->exactly(2))
            ->method('getId')
            ->will($this->returnValue(2));
        
        $language1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language1->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
        
        $language2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language2->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(3));
        
        $this->validator->expects($this->once())
            ->method('getSiteLanguages')
            ->will($this->returnValue(array($language1, $language2)));
        
        $this->pageManager->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($this->connection));
        
        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));
        
        $this->pageManager->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->templateManager->expects($this->exactly(2))
            ->method('populate')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeAddPageCommit($this->event);
    }
}