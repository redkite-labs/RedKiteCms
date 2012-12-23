<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Controller;

use AlphaLemon\AlphaLemonCmsBundle\Tests\WebTestCaseFunctional;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel;

/**
 * BlocksControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
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

    public function testEditorReturnsAnErrorMessageWhenTheBlockIdIsNotGiven()
    {
        $crawler = $this->client->request('POST', '/backend/en/al_showBlocksEditor');
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The content does not exist anymore or the slot has any content inside")')->count() > 0);
    }

    public function testEditorReturnsAnErrorMessageWhenTheBlockIdDoesNotExist()
    {
        $params = array("idBlock" => 9999);
        $crawler = $this->client->request('POST', '/backend/en/al_showBlocksEditor', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The content does not exist anymore or the slot has any content inside")')->count() > 0);
    }

    public function testShowContentsEditor()
    {
        $params = array("idBlock" => 3);
        $crawler = $this->client->request('POST', '/backend/en/al_showBlocksEditor', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(1, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("editor", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertRegExp('/id="al_editor_tabs"/s', $json[0]["value"]);
        $this->assertRegExp('/id="al_html_editor"/s', $json[0]["value"]);
        $this->assertRegExp("/tinyMCE.init/s", $json[0]["value"]);
    }

    public function testShowContentsEditorRenderedFromAListener()
    {
        $params = array("idBlock" => 2);
        $crawler = $this->client->request('POST', '/backend/en/al_showBlocksEditor', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(1, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("editor", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertRegExp('/class="al_items_list"/s', $json[0]["value"]);
        $this->assertRegExp('/\<td\>Home\<\/td\>/s', $json[0]["value"]);
        $this->assertRegExp("/\('\.al_add_item'\)\.AddItem\(2\);/s", $json[0]["value"]);
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
        $referenceBlockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'left_sidebar_content',
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
        $this->assertEquals("The content has been successfully added", $json[0]["value"]);

        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("add-block", $json[1]["key"]);
        $this->assertTrue(array_key_exists("insertAfter", $json[1]));
        $this->assertEquals("block_22", $json[1]["insertAfter"]);
        $this->assertTrue(array_key_exists("slotName", $json[1]));
        $this->assertEquals("al_left_sidebar_content", $json[1]["slotName"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/This is the default text for a new text content/s", $json[1]["value"]);

        $blocks = $this->blockRepository->retrieveContents(2, 2, "left_sidebar_content");
        $this->assertEquals(2, $blocks[count($blocks) - 1]->getContentPosition());
    }

    public function testAddNewBlockOnEmptySlot()
    {
        $blocks = $this->getSlotBlocks("left_sidebar_content");
        $blocks->delete();

        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'left_sidebar_content');

        $crawler = $this->client->request('POST', '/backend/en/addBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertEquals("The content has been successfully added", $json[0]["value"]);

        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("add-block", $json[1]["key"]);
        $this->assertTrue(array_key_exists("insertAfter", $json[1]));
        $this->assertEquals("block_0", $json[1]["insertAfter"]);
        $this->assertTrue(array_key_exists("slotName", $json[1]));
        $this->assertEquals("al_left_sidebar_content", $json[1]["slotName"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/This is the default text for a new text content/s", $json[1]["value"]);
        
        $blocks = $this->blockRepository->retrieveContents(2, 2, "left_sidebar_content");
        $this->assertEquals(1, $blocks[count($blocks) - 1]->getContentPosition());
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

        $this->assertTrue($crawler->filter('html:contains("It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore. Nothing has been made")')->count() > 0);
    }

    public function testEditBlockFailsWhenTheRequiredBlockDoesNotExist()
    {
        $crawler = $this->blockIdDoesNotExist('/backend/en/editBlock');

        $this->assertTrue($crawler->filter('html:contains("It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore. Nothing has been made")')->count() > 0);
    }

    public function testEditBlockDoesNothingWhenKeyDoesNotMatchAnyBlockFieldName()
    {
        $blockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'left_sidebar_content',
                        "key" => "fake",
                        "value" => "new content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', '/backend/en/editBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore. Nothing has been made")')->count() > 0);
    }

    public function testEditBlockDoesNothingWhenTheSameSavedValueIsGiven()
    {
        $blockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'left_sidebar_content',
                        "key" => "Content",
                        "value" => "This is the default text for a new text content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', '/backend/en/editBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore. Nothing has been made")')->count() > 0);
    }

    public function testEditBlock()
    {
        $blockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'left_sidebar_content',
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
        $this->assertEquals("The content has been successfully edited", $json[0]["value"]);

        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("edit-block", $json[1]["key"]);
        $this->assertTrue(array_key_exists("blockName", $json[1]));
        $this->assertEquals("block_" . $blockId, $json[1]["blockName"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/New content/s", $json[1]["value"]);

        $blocks = $this->blockRepository->retrieveContents(2, 2, "left_sidebar_content");
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

        $this->assertTrue($crawler->filter('html:contains("The content you tried to remove does not exist anymore in the website")')->count() > 0);
    }

    public function testDeleteBlockFailsWhenTheRequiredBlockDoesNotExist()
    {
        $crawler = $this->blockIdDoesNotExist('/backend/en/deleteBlock');

        $this->assertTrue($crawler->filter('html:contains("The content you tried to remove does not exist anymore in the website")')->count() > 0);
    }

    public function testDeleteBlock()
    {
        $blockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'left_sidebar_content',
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
        $this->assertEquals("The content has been successfully removed", $json[0]["value"]);

        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("redraw-slot", $json[1]["key"]);
        $this->assertTrue(array_key_exists("slotName", $json[1]));
        $this->assertEquals("al_left_sidebar_content", $json[1]["slotName"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/This slot has any content inside. Use the contextual menu to add a new one/s", $json[1]["value"]);

        $blocks = $this->blockRepository->retrieveContents(2, 2, "left_sidebar_content");
        $this->assertEquals(0, count($blocks));
    }
    
    public function testDeleteBlockPlacedOnASlotThatHasMoreThanOneBlocks()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'left_sidebar_content');
        $crawler = $this->client->request('POST', '/backend/en/addBlock', $params);
        $crawler = $this->client->request('POST', '/backend/en/addBlock', $params);
        $blocks = $this->blockRepository->retrieveContents(2, 2, "left_sidebar_content");
        $this->assertEquals(2, count($blocks));
        
        $blockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => '2',
                        'languageId' => '2',
                        'slotName' => 'left_sidebar_content',
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
        $this->assertEquals("The content has been successfully removed", $json[0]["value"]);

        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("remove-block", $json[1]["key"]);
        $this->assertTrue(array_key_exists("blockName", $json[1]));
        $this->assertEquals("block_26", $json[1]["blockName"]);

        $blocks = $this->blockRepository->retrieveContents(2, 2, "left_sidebar_content");
        $this->assertEquals(1, count($blocks));
    }
    
    public function testShowFilesManagerFailsWhenAnyKeyIsGiven()
    {
        $crawler = $this->client->request('POST', '/backend/en/showExternalFilesManager');
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());

        $this->assertTrue($crawler->filter('html:contains("The key param is mandatory to open the right file manager")')->count() > 0);
    }

    public function testShowFilesManagerFailsWhenKeyIsInvalid()
    {
        $params = array("key" => "fake");
        $crawler = $this->client->request('POST', '/backend/en/showExternalFilesManager', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());

        $this->assertTrue($crawler->filter('html:contains("Unable to find template")')->count() > 0);
    }

    public function testShowJavascriptsFilesManager()
    {
        $params = array("key" => "javascript");
        $crawler = $this->client->request('POST', '/backend/en/showExternalFilesManager', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp("/al\_elFinderJavascriptsConnect/s", $crawler->text());
    }

    public function testShowStylesheetsFilesManager()
    {
        $params = array("key" => "stylesheet");
        $crawler = $this->client->request('POST', '/backend/en/showExternalFilesManager', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp("/al\_elFinderStylesheetsConnect/s", $crawler->text());
    }

    public function testExternalFileIsNotAddedWhenAnyValidParameterHasBeenGiven()
    {
        $crawler = $this->browse('/backend/en/addExternalFile');
        $this->assertTrue($crawler->filter('html:contains("External file cannot be added because any file has been given")')->count() > 0);
    }

    public function testExternalFileIsNotAddedWhenFieldParameterMissing()
    {
        $params = array('page' => 'index', 'language' => 'en', "file" => "myfile");
        $crawler = $this->browse('/backend/en/addExternalFile', $params);
        $this->assertTrue($crawler->filter('html:contains("External file cannot be added because any valid field name has been given")')->count() > 0);
    }

    public function testExternalFileIsNotAddedWhenAnyValidSlotNameHasBeenGiven()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "myfile",
            'field' => "ExternalJavascript"
        );
        $this->slotNameIsInvalid('/backend/en/addExternalFile', $params);
    }

    public function testExternalFileIsNotAddedWhenAnyValidBlockIdHasBeenGiven()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "myfile",
            'field' => "ExternalJavascript",
            "slotName" => "left_sidebar_content"
        );
        $crawler = $this->browse('/backend/en/addExternalFile', $params);
        $this->assertTrue($crawler->filter('html:contains("You are trying to add an external file on a content that doesn\'t exist anymore")')->count() > 0);
    }

    public function testExternalFileIsAdded()
    {
        $blockId = $this->getLastBlock("right_sidebar_content")->getId();
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "myfile",
            'field' => "ExternalJavascript",
            "slotName" => "right_sidebar_content",
            "idBlock" => $blockId
        );
        $crawler = $this->client->request('POST', '/backend/en/addExternalFile', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));

        $messageSection = $json[0];
        $this->assertTrue(array_key_exists("key", $messageSection));
        $this->assertEquals("message", $messageSection["key"]);
        $this->assertTrue(array_key_exists("value", $messageSection));
        $this->assertEquals("The file has been successfully added", $messageSection["value"]);

        $assetsSection = $json[1];
        $this->assertTrue(array_key_exists("key", $assetsSection));
        $this->assertEquals("externalAssets", $assetsSection["key"]);
        $this->assertTrue(array_key_exists("value", $assetsSection));
        $this->assertRegExp("/\<li[^\>]+class=\"al-Javascript-removable-item row[^\>]+rel=\"myfile\"\>myfile\<\/li\>/s", $assetsSection["value"]);
        $this->assertTrue(array_key_exists("section", $assetsSection));
        $this->assertEquals("Javascript", $assetsSection["section"]);

        $block = $this->blockRepository->fromPK($blockId);
        $this->assertEquals('myfile', $block->getExternalJavascript());
    }

    public function testAnotherExternalFileIsAdded()
    {
        $blockId = $this->getLastBlock("right_sidebar_content")->getId();
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "another-file",
            'field' => "ExternalJavascript",
            "slotName" => "right_sidebar_content",
            "idBlock" => $blockId
        );
        $crawler = $this->client->request('POST', '/backend/en/addExternalFile', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));

        $messageSection = $json[0];
        $this->assertTrue(array_key_exists("key", $messageSection));
        $this->assertEquals("message", $messageSection["key"]);
        $this->assertTrue(array_key_exists("value", $messageSection));
        $this->assertEquals("The file has been successfully added", $messageSection["value"]);

        $assetsSection = $json[1];
        $this->assertTrue(array_key_exists("key", $assetsSection));
        $this->assertEquals("externalAssets", $assetsSection["key"]);
        $this->assertTrue(array_key_exists("value", $assetsSection));
        $this->assertRegExp("/\<li[^\>]+class=\"al-Javascript-removable-item alternate_row[^\>]+rel=\"another-file\"\>another-file<\/li\>/s", $assetsSection["value"]);
        $this->assertTrue(array_key_exists("section", $assetsSection));
        $this->assertEquals("Javascript", $assetsSection["section"]);

        $block = $this->blockRepository->fromPK($blockId);
        $this->assertEquals('myfile,another-file', $block->getExternalJavascript());
    }

    public function testAnExternalFileCannotBeAddedMoreThanOnce()
    {
        $blockId = $this->getLastBlock("right_sidebar_content")->getId();
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "another-file",
            'field' => "ExternalJavascript",
            "slotName" => "right_sidebar_content",
            "idBlock" => $blockId
        );
        $crawler = $this->browse('/backend/en/addExternalFile', $params);
        $this->assertTrue($crawler->filter('html:contains("The block has already assigned the external file you are trying to add")')->count() > 0);
    }

    public function testExternalFileIsNotRemovedWhenAnyValidParameterHasBeenGiven()
    {
        $crawler = $this->browse('/backend/en/removeExternalFile');
        $this->assertTrue($crawler->filter('html:contains("External file cannot be removed because any file has been given")')->count() > 0);
    }

    public function testExternalFileIsNotDeletedWhenFieldParameterMissing()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "myfile"
        );
        $crawler = $this->browse('/backend/en/removeExternalFile', $params);
        $this->assertTrue($crawler->filter('html:contains("External file cannot be removed because any valid field name has been given")')->count() > 0);
    }

    public function testExternalFileIsNotDeletedWhenAnyValidSlotNameHasBeenGiven()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "myfile",
            'field' => "ExternalJavascript"
        );
        $this->slotNameIsInvalid('/backend/en/removeExternalFile', $params);
    }

    public function testExternalFileIsNotDeletedWhenAnyValidBlockIdHasBeenGiven()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "myfile",
            'field' => "ExternalJavascript",
            "slotName" => "right_sidebar_content"
        );
        $crawler = $this->browse('/backend/en/removeExternalFile', $params);
        $this->assertTrue($crawler->filter('html:contains("You are trying to delete an external file from a content that doesn\'t exist anymore")')->count() > 0);
    }
    
    public function testExternalFileIsDeletedButNotRemovedFromTheDbBecauseItIsNotSavedIn()
    {
        $blockId = $this->getLastBlock("right_sidebar_content")->getId();
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "not-saved-file",
            'field' => "ExternalJavascript",
            "slotName" => "right_sidebar_content",
            "idBlock" => $blockId
        );
        $crawler = $this->client->request('POST', '/backend/en/removeExternalFile', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));
        $this->assertTrue(array_key_exists("key", $json));
        $this->assertEquals("message", $json["key"]);
        $this->assertTrue(array_key_exists("value", $json));
        $this->assertEquals("The file has been removed", $json["value"]);
    }

    public function testExternalFileIsDeleted()
    {
        // Adds a fake-file reference just to check that the result will be a comma separated string
        $block = $this->getLastBlock("right_sidebar_content");
        $block->setExternalJavascript($block->getExternalJavascript() . ',fake-file');
        $block->save();
        $blockId = $block->getId();
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "myfile",
            'field' => "ExternalJavascript",
            "slotName" => "right_sidebar_content",
            "idBlock" => $blockId
        );
        $crawler = $this->client->request('POST', '/backend/en/removeExternalFile', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(3, count($json));
        $this->assertTrue(array_key_exists("key", $json));
        $this->assertEquals("externalAssets", $json["key"]);
        $this->assertTrue(array_key_exists("value", $json));
        $this->assertNotRegExp("/\<li[^\>]+class=\"al-Javascript-removable-item row[^\>]+rel=\"myfile\"\>myfile\<\/li\>/s", $json["value"]);
        $this->assertRegExp("/\<li[^\>]+class=\"al-Javascript-removable-item row[^\>]+rel=\"another-file\"\>another-file<\/li\>/s", $json["value"]);
        $this->assertTrue(array_key_exists("section", $json));
        $this->assertEquals("Javascript", $json["section"]);

        $block = $this->blockRepository->fromPK($blockId);
        $this->assertEquals('another-file,fake-file', $block->getExternalJavascript());
    }

    public function testDeletesAnotherExternalFile()
    {
        $blockId = $this->getLastBlock("right_sidebar_content")->getId();
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '2',
            'languageId' => '2',
            "file" => "fake-file",
            'field' => "ExternalJavascript",
            "slotName" => "right_sidebar_content",
            "idBlock" => $blockId
        );
        $crawler = $this->client->request('POST', '/backend/en/removeExternalFile', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(3, count($json));
        $this->assertTrue(array_key_exists("key", $json));
        $this->assertEquals("externalAssets", $json["key"]);
        $this->assertTrue(array_key_exists("value", $json));
        $this->assertNotRegExp("/\<li[^\>]+class=\"al-Javascript-removable-item alternate_row[^\>]+rel=\"fake-file\"\>fake-file<\/li\>/s", $json["value"]);
        $this->assertRegExp("/\<li[^\>]+class=\"al-Javascript-removable-item row[^\>]+rel=\"another-file\"\>another-file<\/li\>/s", $json["value"]);
        $this->assertTrue(array_key_exists("section", $json));
        $this->assertEquals("Javascript", $json["section"]);

        $block = $this->blockRepository->fromPK($blockId);
        $this->assertEquals('another-file', $block->getExternalJavascript());
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
        $this->assertTrue($crawler->filter('html:contains("The page you are trying to edit does not exist")')->count() > 0);
    }

    private function anyValidPageIsRetrievedWithGivenParameters($route)
    {
        $params = array('pageId' => '4',
                        'language' => 'en');
        $crawler = $this->browse($route, $params);
        $this->assertTrue($crawler->filter('html:contains("The page you are trying to edit does not exist")')->count() > 0);
    }

    private function slotNameIsInvalid($route, $params = null)
    {
        $params = (null === $params) ? array('page' => 'index', 'language' => 'en', 'slotName' => 'fake') : $params;

        $crawler = $this->browse($route, $params);
        $this->assertTrue($crawler->filter('html:contains("You are trying to manage a block on a slot that does not exist on this page, or the slot name is empty")')->count() > 0);
    }

    private function blockIdIsNull($route)
    {
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'left_sidebar_content');

        return $this->browse($route, $params);
    }

    private function blockIdDoesNotExist($route)
    {
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'left_sidebar_content',
                        'idBlock' => 9999);

        return $this->browse($route, $params);
    }

}
