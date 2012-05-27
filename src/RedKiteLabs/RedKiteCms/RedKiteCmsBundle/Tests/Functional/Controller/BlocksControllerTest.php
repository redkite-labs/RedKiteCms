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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Controller;

use AlphaLemon\AlphaLemonCmsBundle\Tests\WebTestCaseFunctional;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageModelPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlSeoModelPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel;

/**
 * BlocksControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class BlocksControllerTest extends WebTestCaseFunctional
{
    private $pageModel;
    private $seoModel;
    private $blockModel;

    protected function setUp()
    {
        parent::setUp();

        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->pageModel = new AlPageModelPropel($dispatcher);
        $this->seoModel = new AlSeoModelPropel($dispatcher);
        $this->blockModel = new AlBlockModelPropel($dispatcher);
    }

    public function testEditorReturnsAnErrorMessageWhenTheBlockIdIsNotGiven()
    {
        $crawler = $this->client->request('GET', 'backend/en/al_showBlocksEditor');
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The content does not exist anymore or the slot has any content inside")')->count() > 0);
    }

    public function testEditorReturnsAnErrorMessageWhenTheBlockIdDoesNotExist()
    {
        $params = array("idBlock" => 9999);
        $crawler = $this->client->request('GET', 'backend/en/al_showBlocksEditor', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The content does not exist anymore or the slot has any content inside")')->count() > 0);
    }

    public function testShowContentsEditor()
    {
        $params = array("idBlock" => 2);
        $crawler = $this->client->request('GET', 'backend/en/al_showBlocksEditor', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(1, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("editor", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertRegExp("/al_editor_tabs/s", $json[0]["value"]);
        $this->assertRegExp("/\<textarea[^\>]+id=\"al_html_editor\"[^\>]+name=\"al_html_editor\"[^\>]+\>/s", $json[0]["value"]);
        $this->assertRegExp("/tinyMCE.init/s", $json[0]["value"]);
    }

    public function testAddBlockFailsWhenAnyValidParameterIsGiven()
    {
        $this->anyValidParameterIsGiven('backend/en/addBlock');
    }

    public function testAddBlockFailsWhenAnyValidPageIsRetrievedWithGivenParameters()
    {
        $this->anyValidPageIsRetrievedWithGivenParameters('backend/en/addBlock');
    }

    public function testAddBlockFailsWhenTheSlotNameIsInvalid()
    {
        $this->slotNameIsInvalid('backend/en/addBlock');
    }

    public function testAddNewBlock()
    {
        $referenceBlockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array('page' => '2',
                        'language' => '2',
                        'slotName' => 'left_sidebar_content',
                        'idBlock' => $referenceBlockId);

        $crawler = $this->client->request('POST', 'backend/en/addBlock', $params);
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
        $this->assertEquals("block_21", $json[1]["insertAfter"]);
        $this->assertTrue(array_key_exists("slotName", $json[1]));
        $this->assertEquals("al_left_sidebar_content", $json[1]["slotName"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/\<div[^\>]+class=\"al_left_sidebar_content\"\>This is the default text for a new text content\<\/div\>/s", $json[1]["value"]);

        $blocks = $this->blockModel->retrieveContents(2, 2, "left_sidebar_content");
        $this->assertEquals(2, $blocks[count($blocks) - 1]->getContentPosition());
    }

    public function testAddNewBlockOnEmptySlot()
    {
        $blocks = $this->getSlotBlocks("left_sidebar_content");
        $blocks->delete();

        $params = array('page' => '2',
                        'language' => '2',
                        'slotName' => 'left_sidebar_content');

        $crawler = $this->client->request('POST', 'backend/en/addBlock', $params);
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
        $this->assertRegExp("/\<div[^\>]+class=\"al_left_sidebar_content\"\>This is the default text for a new text content\<\/div\>/s", $json[1]["value"]);

        $blocks = $this->blockModel->retrieveContents(2, 2, "left_sidebar_content");
        $this->assertEquals(1, $blocks[count($blocks) - 1]->getContentPosition());
    }

    public function testEditBlockFailsWhenAnyValidParameterIsGiven()
    {
        $this->anyValidParameterIsGiven('backend/en/editBlock');
    }

    public function testEditBlockFailsWhenAnyValidPageIsRetrievedWithGivenParameters()
    {
        $this->anyValidPageIsRetrievedWithGivenParameters('backend/en/editBlock');
    }

    public function testEditBlockFailsWhenTheSlotNameIsInvalid()
    {
        $this->slotNameIsInvalid('backend/en/editBlock');
    }

    public function testEditBlockFailsWhenTheRequiredBlockIdIsNull()
    {
        $crawler = $this->blockIdIsNull('backend/en/editBlock');

        $this->assertTrue($crawler->filter('html:contains("It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore. Nothing has been made")')->count() > 0);
    }

    public function testEditBlockFailsWhenTheRequiredBlockDoesNotExist()
    {
        $crawler = $this->blockIdDoesNotExist('backend/en/editBlock');

        $this->assertTrue($crawler->filter('html:contains("It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore. Nothing has been made")')->count() > 0);
    }

    public function testEditBlockDoesNothingWhenKeyDoesNotMatchAnyBlockFieldName()
    {
        $blockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'left_sidebar_content',
                        "key" => "fake",
                        "value" => "new content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', 'backend/en/editBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore. Nothing has been made")')->count() > 0);
    }

    public function testEditBlockDoesNothingWhenTheSameSavedValueIsGiven()
    {
        $blockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'left_sidebar_content',
                        "key" => "HtmlContent",
                        "value" => "This is the default text for a new text content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', 'backend/en/editBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore. Nothing has been made")')->count() > 0);
    }

    public function testEditBlock()
    {
        $blockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'left_sidebar_content',
                        "key" => "HtmlContent",
                        "value" => "New content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', 'backend/en/editBlock', $params);
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
        $this->assertRegExp("/\<div[^\>]+class=\"al_left_sidebar_content\"\>New content\<\/div\>/s", $json[1]["value"]);

        $blocks = $this->blockModel->retrieveContents(2, 2, "left_sidebar_content");
        $this->assertEquals(1, $blocks[count($blocks) - 1]->getContentPosition());
    }

    public function testDeleteBlockFailsWhenAnyValidParameterIsGiven()
    {
        $this->anyValidParameterIsGiven('backend/en/deleteBlock');
    }

    public function testDeleteBlockFailsWhenAnyValidPageIsRetrievedWithGivenParameters()
    {
        $this->anyValidPageIsRetrievedWithGivenParameters('backend/en/deleteBlock');
    }

    public function testDeleteBlockFailsWhenTheSlotNameIsInvalid()
    {
        $this->slotNameIsInvalid('backend/en/deleteBlock');
    }

    public function testDeleteBlockFailsWhenTheRequiredBlockIdIsNull()
    {
        $crawler = $this->blockIdIsNull('backend/en/deleteBlock');

        $this->assertTrue($crawler->filter('html:contains("The content you tried to remove does not exist anymore in the website")')->count() > 0);
    }

    public function testDeleteBlockFailsWhenTheRequiredBlockDoesNotExist()
    {
        $crawler = $this->blockIdDoesNotExist('backend/en/deleteBlock');

        $this->assertTrue($crawler->filter('html:contains("The content you tried to remove does not exist anymore in the website")')->count() > 0);
    }

    public function testDeleteBlockDoesNothingWhenKeyDoesNotMatchAnyBlockFieldName()
    {
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'left_sidebar_content');

        $crawler = $this->client->request('POST', 'backend/en/deleteBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The content you tried to remove does not exist anymore in the website")')->count() > 0);
    }

    public function testDeleteBlockDoesNothingWhenKeyDoesNotMatchAnyBlockFieldName2()
    {
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'left_sidebar_content',
                        'idBlock' => 9999);

        $crawler = $this->client->request('POST', 'backend/en/deleteBlock', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The content you tried to remove does not exist anymore in the website")')->count() > 0);
    }

    public function testDeleteBlockDoesNothingWhenKeyDoesNotMatchAnyBlockFieldName1()
    {
        $blockId = $this->getLastBlock("left_sidebar_content")->getId();
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'left_sidebar_content',
                        "key" => "fake",
                        "value" => "new content",
                        "idBlock" => $blockId);

        $crawler = $this->client->request('POST', 'backend/en/deleteBlock', $params);
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
        $this->assertRegExp("/\<div[^\>]+class=\"al_left_sidebar_content\"\><div class=\"al_editable[^\>]+\>This slot has any content inside. Use the contextual menu to add a new one\<\/div\>\<\/div\>/s", $json[1]["value"]);

        $blocks = $this->blockModel->retrieveContents(2, 2, "left_sidebar_content");
        $this->assertEquals(0, count($blocks));
    }

    public function testShowFilesManagerFailsWhenAnyKeyIsGiven()
    {
        $crawler = $this->client->request('GET', 'backend/en/showExternalFilesManager');
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());

        $this->assertTrue($crawler->filter('html:contains("The key param is mandatory to open the right file manager")')->count() > 0);
    }

    public function testShowFilesManagerFailsWhenKeyIsInvalid()
    {
        $params = array("key" => "fake");
        $crawler = $this->client->request('GET', 'backend/en/showExternalFilesManager', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());

        $this->assertTrue($crawler->filter('html:contains("Unable to find template")')->count() > 0);
    }

    public function testShowJavascriptsFilesManager()
    {
        $params = array("key" => "javascript");
        $crawler = $this->client->request('GET', 'backend/en/showExternalFilesManager', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp("/al\_elFinderJavascriptsConnect/s", $crawler->text());
    }

    public function testShowStylesheetsFilesManager()
    {
        $params = array("key" => "stylesheet");
        $crawler = $this->client->request('GET', 'backend/en/showExternalFilesManager', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp("/al\_elFinderStylesheetsConnect/s", $crawler->text());
    }

    private function getSlotBlocks($slotName)
    {
        return $this->blockModel->retrieveContents(2, 2, $slotName);
    }

    private function getLastBlock($slotName)
    {
        $blocks = $this->getSlotBlocks($slotName);

        return $blocks[count($blocks) - 1];
    }

    private function anyValidParameterIsGiven($route)
    {
        $crawler = $this->client->request('POST', $route);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The page you are trying to edit does not exist")')->count() > 0);
    }

    private function anyValidPageIsRetrievedWithGivenParameters($route)
    {
        $params = array('page' => '4',
                        'language' => '2');

        $crawler = $this->client->request('POST', $route, $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The page you are trying to edit does not exist")')->count() > 0);
    }

    private function slotNameIsInvalid($route)
    {
        $params = array('page' => '2',
                        'language' => '2',
                        "slotName" => "fake");

        $crawler = $this->client->request('POST', $route, $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The slot you are trying to add a new block does not exist on this page, or the slot name is empty")')->count() > 0);
    }

    public function blockIdIsNull($route)
    {
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'left_sidebar_content');

        $crawler = $this->client->request('POST', $route, $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());

        return $crawler;
    }

    public function blockIdDoesNotExist($route)
    {
        $params = array("page" => "2",
                        "language" => "2",
                        'slotName' => 'left_sidebar_content',
                        'idBlock' => 9999);

        $crawler = $this->client->request('POST', $route, $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());

        return $crawler;
    }


}
