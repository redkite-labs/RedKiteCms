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
 * AlCmsElFinderControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlCmsElFinderControllerTest extends WebTestCaseFunctional
{
    public function testShowFilesManager()
    {
        $crawler = $this->client->request('GET', '/backend/en/al_showFilesManager');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('#elfinder')->count());
    }
}
