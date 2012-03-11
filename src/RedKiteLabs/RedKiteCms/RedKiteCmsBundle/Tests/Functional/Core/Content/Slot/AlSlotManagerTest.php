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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Slot;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

class AlSlotManagerTest extends TestCase 
{    
    public function testAddContent()
    {
        AlphaLemonDataPopulator::depopulate();
        
        $container = $this->setupPageTree()->getContainer(); 
        $slotManager = new AlSlotManager($container, new AlSlot('test', array('repeated' => 'page')));
        $slotManager->addBlock();
        $this->assertEquals(1, $slotManager->length(), '->addBlock() method has not added the content as expected');
        
        $slotManager->addBlock("Script");
        $this->assertEquals(2, $slotManager->length(), '->addBlock() method has not added the content as expected');
        
        $slotManager->addBlock("Media", $slotManager->first()->get()->getId());
        $this->assertEquals(3, $slotManager->length(), '->addBlock() method has not added the content as expected');
        $this->assertEquals('Text', $slotManager->first()->get()->getClassName(), '->addBlock() method has changed the first content position');
        $this->assertEquals('Media', $slotManager->indexAt(1)->get()->getClassName(), '->addBlock() method has not changed the content position of the last content added');
        $this->assertEquals('Script', $slotManager->last()->get()->getClassName(), '->addBlock() method has not changed the content position of the second content added');
        $this->assertEquals(1, $slotManager->first()->get()->getContentPosition());
        $this->assertEquals(2, $slotManager->indexAt(1)->get()->getContentPosition());
        $this->assertEquals(3, $slotManager->last()->get()->getContentPosition());
        
        $slotManager->addBlock("Text", $slotManager->indexAt(1)->get()->getId());
        $this->assertEquals('Text', $slotManager->first()->get()->getClassName());
        $this->assertEquals('Media', $slotManager->indexAt(1)->get()->getClassName());
        $this->assertEquals('Text', $slotManager->indexAt(2)->get()->getClassName());
        $this->assertEquals('Script', $slotManager->last()->get()->getClassName());
        
        $slotManager->addBlock("Text", $slotManager->last()->get()->getId());
        $this->assertEquals('Text', $slotManager->first()->get()->getClassName());
        $this->assertEquals('Media', $slotManager->indexAt(1)->get()->getClassName());
        $this->assertEquals('Text', $slotManager->indexAt(2)->get()->getClassName());
        $this->assertEquals('Script', $slotManager->indexAt(3)->get()->getClassName());
        $this->assertEquals('Text', $slotManager->last()->get()->getClassName());
        
        $alPage = new AlPage();
        $alPage->setId(2);
        
        $alLanguage = new AlLanguage();
        $alLanguage->setId(2);
        $slotManager1 = new AlSlotManager($container,
                                         new AlSlot('test1', array('repeated' => 'page')), $alPage, $alLanguage);
        $slotManager1->addBlock("Text");
        $this->assertEquals(2, $slotManager1->first()->get()->getLanguageId(), '->save() method has not set to the given id language, when saving a slot repeated at page level');
        $this->assertEquals(2, $slotManager1->first()->get()->getPageId(), '->save() method has set the given id page, saving a slot repeated at page level');
        
        $slotManager2 = new AlSlotManager($container,
                                         new AlSlot('test2', array('repeated' => 'language')), $alPage, $alLanguage);
        $slotManager2->addBlock("Text");
        $this->assertEquals(2, $slotManager2->first()->get()->getLanguageId(), '->save() method has not set to the 1 the language id, when saving a slot repeated at language level');
        $this->assertEquals(1, $slotManager2->first()->get()->getPageId(), '->save() method has set to 1 the page id, saving a slot repeated at language level');
        
        $slotManager3 = new AlSlotManager($container,
                                         new AlSlot('test3', array('repeated' => 'site')), $alPage, $alLanguage);
        $slotManager3->addBlock("Text");
        $this->assertEquals(1, $slotManager3->first()->get()->getLanguageId(), '->save() method has not set to 1 the language id, when saving a slot repeated at site level');
        $this->assertEquals(1, $slotManager3->first()->get()->getPageId(), '->save() method has set to 1 the page id, saving a slot repeated at site level');
        
        
        return $slotManager;
    }
    

    /**
     * @depends testAddContent
     */
    public function testEditContent(AlSlotManager $slotManager)
    {
        $res = $slotManager->editBlock(99999999999, array('HtmlContent', 'fake'));
        $this->assertNull($res, '->save() method is expected to return null when the content to edit does not exist');
        
        $res = $slotManager->editBlock($slotManager->first()->get()->getId(), array('HtmlContent', 'fake'));
        $this->assertTrue($res, '->save() method is expected to return null when the content to edit does not exist');        
    }
    
    /**
     * @depends testAddContent
     */
    public function testDeleteContent(AlSlotManager $slotManager)
    {
        $length = $slotManager->length() - 1;
        $slotManager->deleteBlock($slotManager->indexAt(2)->get()->getId()); 
        $this->assertEquals($length, $slotManager->length(), '->deleteBlock() method has not deleted the content as expected');
        $this->assertEquals('Text', $slotManager->first()->get()->getClassName());
        $this->assertEquals('Media', $slotManager->indexAt(1)->get()->getClassName());
        $this->assertEquals('Script', $slotManager->indexAt(2)->get()->getClassName());
        $this->assertEquals('Text', $slotManager->last()->get()->getClassName());
        
        $length--;
        $slotManager->deleteBlock($slotManager->indexAt(1)->get()->getId()); 
        $this->assertEquals($length, $slotManager->length(), '->deleteBlock() method has not deleted the content as expected');
        $this->assertEquals('Text', $slotManager->first()->get()->getClassName());
        $this->assertEquals('Script', $slotManager->indexAt(1)->get()->getClassName());
        $this->assertEquals('Text', $slotManager->last()->get()->getClassName());
        
        $length--;
        $slotManager->deleteBlock($slotManager->last()->get()->getId()); 
        $this->assertEquals($length, $slotManager->length(), '->deleteBlock() method has not deleted the content as expected');
        $this->assertEquals('Text', $slotManager->first()->get()->getClassName());
        $this->assertEquals('Script', $slotManager->indexAt(1)->get()->getClassName());
        
        return $slotManager;
        
        /*
        $length--;
        $slotManager->deleteBlock($slotManager->first()->get()->getId()); 
        $this->assertEquals($length, $slotManager->length(), '->deleteBlock() method has not deleted the content as expected');
        $this->assertEquals('Script', $slotManager->first()->get()->getClassName());*/
    }
    
    /**
     * @depends testAddContent
     */
    public function testDeleteContents(AlSlotManager $slotManager)
    {
        $slotManager->deleteBlocks(); 
        $this->assertEquals(0, AlBlockQuery::create()->fromPageIdAndSlotName(2, $slotManager->getSlotName())->count(), '->deleteBlocks() method has not deleted the contents as expected');
        $this->assertEquals(0, $slotManager->length(), '->deleteBlocks() method has not deleted the contents as expected');        
    }
    
    
    public function testFirst()
    {
        $container = $this->setupPageTree()->getContainer(); 
        $slotManager = new AlSlotManager($container,
                                         new AlSlot('first', array('repeated' => 'page')));
        $this->assertNull($slotManager->first(), '->first() method has not returned a null value as expected');
        $slotManager->addBlock("Text");
        $this->assertEquals('Text', $slotManager->first()->get()->getClassName(), '->first() method has not returned a null value as expected');
    }
    
    public function testLast()
    {
        $container = $this->setupPageTree()->getContainer(); 
        $slotManager = new AlSlotManager($container,
                                         new AlSlot('last', array('repeated' => 'page')));
        $this->assertNull($slotManager->last(), '->last() method has not returned a null value as expected');
        $slotManager->addBlock("Text");
        $slotManager->addBlock("Media");
        $this->assertEquals('Media', $slotManager->last()->get()->getClassName(), '->last() method has not returned a null value as expected');
    }
    
    public function testIndexAt()
    {
        $container = $this->setupPageTree()->getContainer(); 
        $slotManager = new AlSlotManager($container,
                                         new AlSlot('indexat', array('repeated' => 'page')));
        $this->assertNull($slotManager->indexAt(0), '->indexAt() method has not returned a null value as expected');
        $slotManager->addBlock("Text");
        $slotManager->addBlock("Script");
        $slotManager->addBlock("Media");
        $this->assertNull($slotManager->indexAt(-1), '->indexAt() method has not returned a null value as expected');
        $this->assertNull($slotManager->indexAt(3), '->indexAt() method has not returned a null value as expected');
        $this->assertEquals('Text', $slotManager->indexAt(0)->get()->getClassName(), '->indexAt() method has not returned the expected content manager');
        $this->assertEquals('Script', $slotManager->indexAt(1)->get()->getClassName(), '->indexAt() method has not returned the expected content manager');
        $this->assertEquals('Media', $slotManager->indexAt(2)->get()->getClassName(), '->indexAt() method has not returned the expected content manager');
    }
    
    public function testLength()
    {
        $container = $this->setupPageTree()->getContainer(); 
        $slotManager = new AlSlotManager($container,
                                         new AlSlot('length', array('repeated' => 'page')));
        $this->assertEquals(0, $slotManager->length(), '->length() method has not returned 0 as expected ');
        $slotManager->addBlock("Text");
        $this->assertEquals(1, $slotManager->length(), '->length() method has not returned 1 as expected ');
        $slotManager->addBlock("Script");
        $this->assertEquals(2, $slotManager->length(), '->length() method has not returned 2 as expected ');
    }
    
    public function testGetContentManager()
    {
        $container = $this->setupPageTree()->getContainer(); 
        $slotManager = new AlSlotManager($container,
                                         new AlSlot('length', array('repeated' => 'page')));
        $this->assertNull($slotManager->getContentManager(999999999), '->getContentManager() method has not returned a null value as expected');
        $slotManager->addBlock("Text");
        $this->assertEquals($slotManager->first(), $slotManager->getContentManager($slotManager->first()->get()->getId()), '->getContentManager() method has not returned the expected content manager');        
    }
    
}