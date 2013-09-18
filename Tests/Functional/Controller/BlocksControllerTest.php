<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Functional\Controller;

use RedKiteLabs\RedKiteCmsBundle\Tests\WebTestCaseFunctional;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel;

/**
 * BlocksControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlocksControllerTest extends WebTestCaseFunctional
{
    private $pageRepository;
    private $seoRepository;
    private $blockRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->pageRepostestAddNewBlockitory = new AlPageRepositoryPropel();
        $this->seoRepository = new AlSeoRepositoryPropel();
        $this->blockRepository = new AlBlockRepositoryPropel();

        $this->blockRepository->fromPK(2);
    }
    
    public function testShowAvailableBlocks()
    {
        $crawler = $this->client->request('POST', '/backend/en/showAvailableBlocks');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertRegExp('/File block/s', $content);        
        $this->assertRegExp('/Image block/s', $content);       
        $this->assertRegExp('/Link/s', $content);  
        $this->assertRegExp('/Menu/s', $content);  
        $this->assertRegExp('/Badge/s', $content);  
        $this->assertRegExp('/Button/s', $content);  
        $this->assertRegExp('/Carousel/s', $content);
        $this->assertRegExp('/Bootbusiness product/s', $content);
    }

    public function testAddBlockFailsWhenAnyValidParameterIsGiven()
    {
        $this->anyValidParameterIsGiven('/backend/en/addBlock');
    }

    public function testAddBlockFailsWhenAnyValidPageIsRetrievedWithGivenParameters()
    {
        $this->anyValidPageIsRetrievedWithGivenParameters('/backend/en/addBlock');
    }

    public function testAddBlockFailsWhenTheSlotNameIsInvalid()
    {
        $this->slotNameIsInvalid('/backend/en/addBlock');
    }

    public function testAddNewBlock()
    {
        $referenceBlockId = $this->getLastBlock("content_title_1")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'content_title_1',
                        'included' => 'false',
                        'idBlock' => $referenceBlockId);

        $crawler = $this->client->request('POST', '/backend/en/addBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertRegExp(
            '/blocks_controller_block_added|The block has been successfully added/si',
            $json[0]["value"]
        );
        
        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("add-block", $json[1]["key"]);
        $this->assertTrue(array_key_exists("insertAfter", $json[1]));
        $this->assertEquals("block_" . $referenceBlockId, $json[1]["insertAfter"]);
        $this->assertTrue(array_key_exists("slotName", $json[1]));
        $this->assertEquals("content_title_1", $json[1]["slotName"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/This is the default content for a new hypertext block/s", $json[1]["value"]);

        $blocks = $this->blockRepository->retrieveContents(2, 2, "content_title_1");
        $this->assertEquals(2, $blocks[count($blocks) - 1]->getContentPosition());
    }

    public function testAddNewBlockOnEmptySlot()
    {
        $blocks = $this->getSlotBlocks("content_title_1");
        $blocks->delete();
        $blocks = $this->blockRepository->retrieveContents(2, 2, "content_title_1");
        $this->assertCount(0, $blocks);

        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'idBlock' => 0,
                        'included' => 'false',
                        'slotName' => 'content_title_1');

        $crawler = $this->client->request('POST', '/backend/en/addBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));        
        $this->assertRegExp(
            '/blocks_controller_block_added|The block has been successfully added/si',
            $json[0]["value"]
        );

        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("add-block", $json[1]["key"]);
        $this->assertTrue(array_key_exists("insertAfter", $json[1]));
        $this->assertEquals("block_0", $json[1]["insertAfter"]);
        $this->assertTrue(array_key_exists("slotName", $json[1]));
        $this->assertEquals("content_title_1", $json[1]["slotName"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/This is the default content for a new hypertext block/s", $json[1]["value"]);
        
        $blocks = $this->blockRepository->retrieveContents(2, 2, "content_title_1");
        $this->assertCount(1, $blocks);
    }

    public function testEditBlockFailsWhenAnyValidParameterIsGiven()
    {
        $this->anyValidParameterIsGiven('/backend/en/editBlock');
    }

    public function testEditBlockFailsWhenAnyValidPageIsRetrievedWithGivenParameters()
    {
        $this->anyValidPageIsRetrievedWithGivenParameters('/backend/en/editBlock');
    }

    public function testEditBlockFailsWhenTheSlotNameIsInvalid()
    {
        $this->slotNameIsInvalid('/backend/en/editBlock');
    }

    public function testEditBlockFailsWhenTheRequiredBlockIdIsNull()
    {
        $crawler = $this->blockIdIsNull('/backend/en/editBlock');
        $this->assertRegExp(
            '/blocks_controller_nothing_changed_with_these_values|It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore: nothing has been made/si',
            $this->client->getResponse()->getContent()
        );
    }

    public function testEditBlockFailsWhenTheRequiredBlockDoesNotExist()
    {
        $crawler = $this->blockIdDoesNotExist('/backend/en/editBlock');

        $this->assertRegExp(
            '/blocks_controller_nothing_changed_with_these_values|It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore: nothing has been made/si',
            $this->client->getResponse()->getContent()
        );
    }

    public function testEditBlockDoesNothingWhenKeyDoesNotMatchAnyBlockFieldName()
    {
        $blockId = $this->getLastBlock("content_title_1")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'content_title_1',
                        'included' => 'false',
                        "key" => "fake",
                        "value" => "new content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', '/backend/en/editBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertRegExp(
            '/blocks_controller_nothing_changed_with_these_values|It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore: nothing has been made/si',
            $this->client->getResponse()->getContent()
        );
    }

    public function testEditBlockDoesNothingWhenTheSameSavedValueIsGiven()
    {
        $blockId = $this->getLastBlock("content_title_1")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'content_title_1',
                        'included' => 'false',
                        "key" => "Content",
                        "value" => "This is the default content for a new hypertext block",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', '/backend/en/editBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertRegExp(
            '/blocks_controller_nothing_changed_with_these_values|It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore: nothing has been made/si',
            $this->client->getResponse()->getContent()
        );
    }

    public function testEditBlock()
    {
        $blockId = $this->getLastBlock("content_title_1")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'content_title_1',
                        'included' => 'false',
                        "key" => "Content",
                        "value" => "New content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', '/backend/en/editBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertRegExp(
            '/blocks_controller_block_edited|The block has been successfully edited/si',
            $this->client->getResponse()->getContent()
        );

        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("edit-block", $json[1]["key"]);
        $this->assertTrue(array_key_exists("blockName", $json[1]));
        $this->assertEquals("block_" . $blockId, $json[1]["blockName"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/New content/s", $json[1]["value"]);

        $blocks = $this->blockRepository->retrieveContents(2, 2, "content_title_1");
        $this->assertEquals(1, $blocks[count($blocks) - 1]->getContentPosition());
    }

    public function testDeleteBlockFailsWhenAnyValidParameterIsGiven()
    {
        $this->anyValidParameterIsGiven('/backend/en/deleteBlock');
    }

    public function testDeleteBlockFailsWhenAnyValidPageIsRetrievedWithGivenParameters()
    {
        $this->anyValidPageIsRetrievedWithGivenParameters('/backend/en/deleteBlock');
    }

    public function testDeleteBlockFailsWhenTheSlotNameIsInvalid()
    {
        $this->slotNameIsInvalid('/backend/en/deleteBlock');
    }

    public function testDeleteBlockFailsWhenTheRequiredBlockIdIsNull()
    {
        $crawler = $this->blockIdIsNull('/backend/en/deleteBlock');
        
        $this->assertRegExp(
            '/blocks_controller_block_does_not_exists|The block you tried to remove does not exist anymore in the website/si',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDeleteBlockFailsWhenTheRequiredBlockDoesNotExist()
    {
        $crawler = $this->blockIdDoesNotExist('/backend/en/deleteBlock');

        $this->assertRegExp(
            '/blocks_controller_block_does_not_exists|The block you tried to remove does not exist anymore in the website/si',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDeleteBlock()
    {
        $blockId = $this->getLastBlock("content_title_1")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'content_title_1',
                        'included' => 'false',
                        "key" => "fake",
                        "value" => "new content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', '/backend/en/deleteBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertRegExp(
            '/blocks_controller_block_removed|The block has been successfully removed/si',
            $json[0]["value"]
        );

        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("redraw-slot", $json[1]["key"]);
        $this->assertTrue(array_key_exists("slotName", $json[1]));
        $this->assertEquals("content_title_1", $json[1]["slotName"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp(
            '/twig_extension_empty_slot|This slot has any blocks inside/si',
            $json[1]["value"]
        );
        
        $blocks = $this->blockRepository->retrieveContents(2, 2, "content_title_1");
        $this->assertEquals(0, count($blocks));
    }
    
    public function testDeleteBlockPlacedOnASlotThatHasMoreThanOneBlocks()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'content_title_1',
                        'included' => 'false',
                        );
        $crawler = $this->client->request('POST', '/backend/en/addBlock', $params);
        $crawler = $this->client->request('POST', '/backend/en/addBlock', $params);
        $blocks = $this->blockRepository->retrieveContents(2, 2, "content_title_1");
        $this->assertEquals(2, count($blocks));
        
        $blockId = $this->getLastBlock("content_title_1")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'content_title_1',
                        'included' => 'false',
                        "key" => "fake",
                        "value" => "new content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', '/backend/en/deleteBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));        
        $this->assertRegExp(
            '/blocks_controller_block_removed|The block has been successfully removed/si',
            $this->client->getResponse()->getContent()
        );

        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("remove-block", $json[1]["key"]);
        $this->assertTrue(array_key_exists("blockName", $json[1]));
        $this->assertEquals("block_27", $json[1]["blockName"]);

        $blocks = $this->blockRepository->retrieveContents(2, 2, "content_title_1");
        $this->assertEquals(1, count($blocks));
    }
    
    public function testAddIncludedBlockFails()
    {
        $referenceBlockId = $this->getLastBlock("content_title_1")->getId();
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'contentType' => 'BootstrapThumbnailsBlock',
            'pageId' => '2',
            'languageId' => '2',
            'slotName' => 'content_title_1',
            'included' => 'false',
            'idBlock' => $referenceBlockId,
        );

        $crawler = $this->client->request('POST', '/backend/en/addBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());        
        
        $referenceBlockId = $this->getLastBlock("content_title_1")->getId();  
        $slotName = $referenceBlockId . '-0';
        $blocks = $this->blockRepository->retrieveContents(2, 2, $slotName);
        $this->assertCount(1, $blocks);      
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            'slotName' => $slotName,
            'included' => 'true',
        );

        $crawler = $this->client->request('POST', '/backend/en/addBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        
        $this->assertRegExp(
            '/blocks_controller_included_blocks_accept_only_a_block|You can add just one block into an included block/si',
            $this->client->getResponse()->getContent()
        );
        
        return $slotName;
    }
    
    /**
     * @depends testAddIncludedBlockFails
     */
    public function testDeleteIncludedBlock($slotName)
    {
        $blockId = $this->getLastBlock($slotName)->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => $slotName,
                        'included' => 'true',
                        "key" => "fake",
                        "value" => "new content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', '/backend/en/deleteBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $blocks = $this->blockRepository->retrieveContents(2, 2, $slotName);
        $this->assertCount(0, $blocks);
        
        return $slotName;
    }
    
    /**
     * @depends testDeleteIncludedBlock
     */
    public function testAddNewIncludedBlock($slotName)
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            'slotName' => $slotName,
            'included' => 'true',
        );

        $crawler = $this->client->request('POST', '/backend/en/addBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $blocks = $this->blockRepository->retrieveContents(2, 2, $slotName);
        $this->assertCount(1, $blocks);
    }

    private function getSlotBlocks($slotName)
    {
        return $this->blockRepository->retrieveContents(2, 2, $slotName);
    }

    private function getLastBlock($slotName)
    {
        $blocks = $this->getSlotBlocks($slotName);
        
        return $blocks[count($blocks) - 1];
    }

    private function browse($route, $params = array(), $method = 'POST')
    {
        $crawler = $this->client->request($method, $route, $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());

        return $crawler;
    }

    private function anyValidParameterIsGiven($route)
    {
        $crawler = $this->browse($route);
        $this->assertRegExp(
            '/blocks_controller_page_does_not_exists|The page you are trying to edit does not exist/si',
            $this->client->getResponse()->getContent()
        );
    }

    private function anyValidPageIsRetrievedWithGivenParameters($route)
    {
        $params = array('pageId' => '4',
                        'language' => 'en');
        $crawler = $this->browse($route, $params);
        $this->assertRegExp(
            '/blocks_controller_page_does_not_exists|The page you are trying to edit does not exist/si',
            $this->client->getResponse()->getContent()
        );
    }

    private function slotNameIsInvalid($route, $params = null)
    {
        $params = (null === $params) ? array('page' => 'index', 'language' => 'en', 'slotName' => 'fake') : $params;

        $crawler = $this->browse($route, $params);
        $this->assertRegExp(
            '/blocks_controller_invalid_or_empty_slot|You are trying to manage a block on a slot that does not exist on this page, or the slot name is empty/si',
            $this->client->getResponse()->getContent()
        );
    }

    private function blockIdIsNull($route)
    {
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'content_title_1');

        return $this->browse($route, $params);
    }

    private function blockIdDoesNotExist($route)
    {
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'content_title_1',
                        'idBlock' => 9999);

        return $this->browse($route, $params);
    }

}
