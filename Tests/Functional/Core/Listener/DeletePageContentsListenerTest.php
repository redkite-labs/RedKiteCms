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
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page\DeletePageContentsListener;

class DeletePageContentsListenerTest extends TestCase
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
        
        
        $this->event = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeDeletePageCommitEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageManagerContainer = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainer')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        
        $this->testListener = new DeletePageContentsListener($this->templateManager);
    }
    
    public function testAnythingIsExecutedWhenTheEventHadBeenAborted()
    {
        $this->event->expects($this->once())
            ->method('isAborted')
            ->will($this->returnValue(true));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
        
    public function testDeleteFailsWhenBlockDeleteFails()
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
                
        $this->pageManager->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
                        
        $this->pageManager->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($this->connection));
        
        $this->templateManager->expects($this->once())
            ->method('getSlotManagers')
            ->will($this->returnValue(array(
                    $this->createSlotManager('page', true),
                    $this->createSlotManager('site', true),
                    $this->createSlotManager('language', true),
                    $this->createSlotManager('page', false))));
        
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
                                
        $this->pageManager->expects($this->exactly(2))
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');   
        $this->validator->expects($this->once())
            ->method('getSiteLanguages')
            ->will($this->returnValue(array($language)));
        
        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));
        
        $this->pageManager->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($this->connection));
        
        $this->templateManager->expects($this->once())
            ->method('getSlotManagers')
            ->will($this->returnValue(array(
                    $this->createSlotManager('page', true),
                    $this->createSlotManager('site', true),
                    $this->createSlotManager('language', true),
                    $this->createSlotManager('page', true))));
        
        $this->templateManager->expects($this->any())
            ->method('getPageContentsContainer')
            ->will($this->returnValue($this->pageManagerContainer));
        
        $this->pageManagerContainer->expects($this->once())
            ->method('getAlLanguage')
            ->will($this->returnValue($language));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    public function testDeleteWithMoreLanguagesFailsWhenOneDeleteOperationFails()
    {
        $language1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        
        // Connection
        $this->connection->expects($this->once())
            ->method('beginTransaction')
            ->will($this->returnValue($this->pageManager));
        
        $this->connection->expects($this->once())
            ->method('rollback')
            ->will($this->returnValue($this->pageManager));
        
        // Event
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        // Template manager
        $this->templateManager->expects($this->once())
            ->method('getSlotManagers')
            ->will($this->returnValue(array(
                    $this->createSlotManager('page', true),
                    $this->createSlotManager('site', true),
                    $this->createSlotManager('language', true),
                    $this->createSlotManager('page', true))));
        
        $this->templateManager->expects($this->any())
            ->method('getPageContentsContainer')
            ->will($this->returnValue($this->pageManagerContainer));
        
        // Fake template manager
        // Calling method setPageContentsContainer will setup the template manager to handle the new page.
        // In production the template manager instance is always the same
        $templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $templateManager->expects($this->once())
                ->method('getSlotManagers')
                ->will($this->returnValue(array(
                    $this->createSlotManager('page', true),
                    $this->createSlotManager('site', true),
                    $this->createSlotManager('language', true),
                    $this->createSlotManager('page', false))));
        
        $this->templateManager->expects($this->any())
            ->method('setPageContentsContainer')
            ->will($this->returnValue($templateManager));
        
        // Validator
        $this->validator->expects($this->once())
            ->method('getSiteLanguages')
            ->will($this->returnValue(array($language1, $language2)));
        
        // Page manager
        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));
        
        $this->pageManager->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($this->connection));
        
        $this->pageManagerContainer->expects($this->exactly(2))
            ->method('getAlLanguage')
            ->will($this->returnValue($language1)); 
        
        $this->pageManagerContainer->expects($this->once())
            ->method('setAlLanguage')
            ->will($this->returnValue($language2));
        
        $this->pageManager->expects($this->exactly(4))
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
    
    public function testDeleteWithMoreLanguages()
    {
        $language1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        
        // Connection
        $this->connection->expects($this->once())
            ->method('beginTransaction')
            ->will($this->returnValue($this->pageManager));
        
        $this->connection->expects($this->once())
            ->method('commit')
            ->will($this->returnValue($this->pageManager));
        
        // Event
        $this->event->expects($this->once())
            ->method('getContentManager')
            ->will($this->returnValue($this->pageManager));
        
        // Template manager
        $this->templateManager->expects($this->once())
            ->method('getSlotManagers')
            ->will($this->returnValue(array(
                    $this->createSlotManager('page', true),
                    $this->createSlotManager('site', true),
                    $this->createSlotManager('language', true),
                    $this->createSlotManager('page', true))));
        
        $this->templateManager->expects($this->any())
            ->method('getPageContentsContainer')
            ->will($this->returnValue($this->pageManagerContainer));
        
        // Fake template manager
        // Calling method setPageContentsContainer will setup the template manager to handle the new page.
        // In production the template manager instance is always the same
        $templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $templateManager->expects($this->once())
                ->method('getSlotManagers')
                ->will($this->returnValue(array(
                    $this->createSlotManager('page', true),
                    $this->createSlotManager('site', true),
                    $this->createSlotManager('language', true),
                    $this->createSlotManager('page', true))));
        
        $this->templateManager->expects($this->any())
            ->method('setPageContentsContainer')
            ->will($this->returnValue($templateManager));
        
        // Validator
        $this->validator->expects($this->once())
            ->method('getSiteLanguages')
            ->will($this->returnValue(array($language1, $language2)));
        
        // Page manager
        $this->pageManager->expects($this->once())
            ->method('getValidator')
            ->will($this->returnValue($this->validator));
        
        $this->pageManager->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue($this->connection));
        
        $this->pageManagerContainer->expects($this->exactly(2))
            ->method('getAlLanguage')
            ->will($this->returnValue($language1)); 
        
        $this->pageManagerContainer->expects($this->once())
            ->method('setAlLanguage')
            ->will($this->returnValue($language2));
        
        $this->pageManager->expects($this->exactly(4))
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $this->testListener->onBeforeDeletePageCommit($this->event);
    }
        
    private function createSlotManager($repeated, $deleteResult)
    {
        $slotManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager')
                                    ->disableOriginalConstructor()
                                    ->getMock(); 
        
        $slotManager->expects($this->any())
            ->method('getRepeated')
            ->will($this->returnValue($repeated));
        
        $expectation = ($repeated == 'page') ? $this->once() : $this->never();
        $slotManager->expects($expectation)
            ->method('deleteBlocks')
            ->will($this->returnValue($deleteResult));
        
        return $slotManager;
    }
}