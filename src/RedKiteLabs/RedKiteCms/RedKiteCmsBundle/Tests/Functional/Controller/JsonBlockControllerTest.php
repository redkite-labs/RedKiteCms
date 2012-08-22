<?php
/*
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
 * JsonBlockControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class JsonBlockControllerTest extends WebTestCaseFunctional
{
    public function testAnExceptionIsThrownWhenRequiringTheJsonItemsListAndTheBlockDoesNotExists()
    {
        $params = array("blockId" => 999);
        $crawler = $this->client->request('GET', 'backend/en/al_listJsonItems', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("It seems that the block to edit does not exist anymore")')->count() > 0);
    }

    public function testShowAGenericJsonBlockEditorDisplayedAsList()
    {
        $params = array("blockId" => 2);
        $crawler = $this->client->request('GET', 'backend/en/al_listJsonItems', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('table tr')->count() == 5);
        $this->assertTrue($crawler->filter('table tr td')->count() > 0);
        $this->assertTrue($crawler->filter('script')->count() == 1);
        $this->assertTrue($crawler->filter('.al_edit_item')->count() == 5);
        $this->assertTrue($crawler->filter('.al_delete_item')->count() == 5);
    }

    public function testShowAnEmptyFormToAddNewItems()
    {
        $params = array("blockId" => 2, "itemId" => -1);
        $crawler = $this->client->request('GET', 'backend/en/al_showJsonItem', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(1, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("editor", $json[0]["key"]);
        $editor = $json[0]["value"];
        $this->assertRegExp('/form id="al_item_form"/s', $editor);
        $this->assertRegExp('/id="al_json_block_id"/s', $editor);
        $this->assertRegExp('/id="al_json_block_title"/s', $editor);
        $this->assertRegExp('/id="al_json_block_subtitle"/s', $editor);
        $this->assertRegExp('/id="al_json_block_internal_link"/s', $editor);
        $this->assertRegExp('/id="al_json_block_external_link"/s', $editor);
        $this->assertRegExp('/\<input type="text" id="al_json_block_title" name="al_json_block\[title\]" required="required"    class="input" \/\>/s', $editor);
        $this->assertRegExp('/\<input type="text" id="al_json_block_subtitle" name="al_json_block\[subtitle\]" required="required"    class="input" \/\>/s', $editor);
        $this->assertRegExp('/\<select id="al_json_block_internal_link"/s', $editor);
        $this->assertRegExp('/\<input type="text" id="al_json_block_external_link" name="al_json_block\[external_link\]" required="required"    class="input" \/\>/s', $editor);
        $this->assertRegExp('/al_save_item/s', $editor);
        $this->assertRegExp('/al_list_items/s', $editor);
        $this->assertRegExp('/id="al_save_item_errors"/s', $editor);
    }

    public function testAnExceptionIsThrownWhenRequiringAJsonItemAndTheBlockDoesNotExist()
    {
        $params = array("blockId" => 999, "itemId" => 0);
        $crawler = $this->client->request('GET', 'backend/en/al_showJsonItem', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("It seems that the block to edit does not exist anymore")')->count() > 0);
    }

    public function testAnExceptionIsThrownWhenTryingToEditAJsonItemThatDoesNotExist()
    {
        $params = array("blockId" => 2, "itemId" => 999);
        $crawler = $this->client->request('GET', 'backend/en/al_showJsonItem', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("It seems that the item requested does not exist anymore")')->count() > 0);
    }

    public function testShowAJsonItem()
    {
        $params = array("blockId" => 2, "itemId" => 0);
        $crawler = $this->client->request('GET', 'backend/en/al_showJsonItem', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(1, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("editor", $json[0]["key"]);
        $editor = $json[0]["value"];
        $this->assertRegExp('/form id="al_item_form"/s', $editor);
        $this->assertRegExp('/id="al_json_block_id"/s', $editor);
        $this->assertRegExp('/id="al_json_block_title"/s', $editor);
        $this->assertRegExp('/id="al_json_block_subtitle"/s', $editor);
        $this->assertRegExp('/id="al_json_block_external_link"/s', $editor);
        $this->assertRegExp('/id="al_json_block_title".*?value="Home"/s', $editor);
        $this->assertRegExp('/id="al_json_block_title".*?value="Welcome!"/s', $editor);
        $this->assertRegExp('/al_save_item/s', $editor);
        $this->assertRegExp('/al_list_items/s', $editor);
        $this->assertRegExp('/id="al_save_item_errors"/s', $editor);
    }

    public function testAnExceptionIsThrownWhenDeletingAJsonItemAndTheBlockDoesNotExist()
    {
        $params = array("blockId" => 999, "itemId" => 0);
        $crawler = $this->client->request('POST', 'backend/en/al_deleteJsonItem', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("It seems that the block to edit does not exist anymore")')->count() > 0);
    }

    public function testAnExceptionIsThrownWhenTryingToDeleteAJsonItemThatDoesNotExist()
    {
        $params = array("blockId" => 2, "RemoveItem" => 999);
        $crawler = $this->client->request('POST', 'backend/en/al_deleteJsonItem', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("It seems that the item requested does not exist anymore")')->count() > 0);
    }

    public function testDeleteAJsonItem()
    {
        $params = array("blockId" => 2, "RemoveItem" => 1);
        $crawler = $this->client->request('POST', 'backend/en/al_deleteJsonItem', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("content", $json[0]["key"]);
        $this->assertEquals("list", $json[1]["key"]);
        $editor = $json[0]["value"];
        $this->assertNotRegExp('/\<a href="#"\>News<span\>Fresh\<\/span\>\<\/a\>/s', $editor);
        $this->assertNotRegExp('/\<td\>News\<\/td\>/s', $editor);
    }
}

