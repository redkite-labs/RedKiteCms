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
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page\DeletePageAttributesListener;

class DeletePageAttributesListenerTest extends TestCase
{   
    private $event;
    private $testListener;
    private $pageManager;
    private $pageAttributesManager;
    private $validator;
    
    protected function setUp() 
    {
        parent::setUp();
        
        $this->pageAttributesManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageAttributeModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageAttributeModel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->event = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeDeletePageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        
        $this->testListener = new DeletePageAttributesListener($this->pageAttributesManager, $this->pageAttributeModel);
    }
    
    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    public function testDeleteWithOneLanguageFailsWhen()
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
        
        $this->pageAttributesManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(false));
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage'); 
        $page->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
        
    public function testDeleteWithOneLanguage()
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
        
        $this->event->expects($this->never())
            ->method('abort');
        
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
        
        $this->pageAttributesManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));
        
        $pageAttribute = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttribute');
        $this->pageAttributeModel->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($pageAttribute));
                
        $this->pageAttributesManager->expects($this->once())
            ->method('set')
            ->with($pageAttribute);
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage'); 
        $page->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
        $this->pageManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    public function testDeleteWithMoreLanguagesFailsWhenOneDeleteOperationFails()
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
            ->method('getValidator')
            ->will($this->returnValue($this->validator));
        
        $this->pageManager->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($this->connection));
        
        $this->pageAttributesManager->expects($this->exactly(2))
            ->method('delete')
            ->will($this->onConsecutiveCalls(true, false));
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage'); 
        $page->expects($this->exactly(2))
            ->method('getId')
            ->will($this->returnValue(2));
        $this->pageManager->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    public function testDeleteWithMoreLanguages()
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
        
        $this->event->expects($this->never())
            ->method('abort');
        
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
            ->method('getValidator')
            ->will($this->returnValue($this->validator));
        
        $this->pageManager->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($this->connection));
        
        $pageAttribute = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttribute');
        $this->pageAttributeModel->expects($this->exactly(2))
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($pageAttribute));
                
        $this->pageAttributesManager->expects($this->exactly(2))
            ->method('set')
            ->with($pageAttribute);
        
        $this->pageAttributesManager->expects($this->exactly(2))
            ->method('delete')
            ->will($this->returnValue(true));
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage'); 
        $page->expects($this->exactly(2))
            ->method('getId')
            ->will($this->returnValue(2));
        $this->pageManager->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($page));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
}