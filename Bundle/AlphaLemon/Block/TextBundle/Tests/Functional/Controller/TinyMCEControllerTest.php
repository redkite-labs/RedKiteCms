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

/**
 * TinyMCEControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class TinyMCEControllerTest extends WebTestCaseFunctional
{
    public function testCreateImageList()
    {
        $crawler = $this->client->request('GET', 'backend/en/al_createImagesList');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/var tinyMCEImageList = new Array\(([\[\",\.\]\/\_\-\w\s\\\]+)\)/', $crawler->text());
    }

    public function testCreatePermalinkList()
    {
        $crawler = $this->client->request('GET', 'backend/en/al_createPermalinksList/2');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/var tinyMCELinkList = new Array\(([\[\",\.\]\/\_\-\w\s\\\]+)\)/', $crawler->text());
    }
}
