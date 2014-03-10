<?php
/**
 * This file is part of the TinyMceBlockBundle and it is distributed
 * under the MIT LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license     MIT LICENSE
 *
 */

namespace RedKiteCms\Block\TinyMceBlockBundle\Tests\Functional\Controller;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\WebTestCaseFunctional;

/**
 * TinyMceControllerTest
 */
class TinyMceControllerTest extends WebTestCaseFunctional
{
    public function testIndex()
    {
        $crawler = $this->client->request('GET', 'backend/en/al_createPermalinksList/en');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[{"title":"this-is-a-website-fake-page","value":"this-is-a-website-fake-page"}]', $crawler->text());
    }
}
