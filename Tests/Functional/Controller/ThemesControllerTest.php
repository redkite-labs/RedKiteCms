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
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel;

/**
 * ThemesControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class ThemesControllerTest extends WebTestCaseFunctional
{
    private $pageModel;
    private $seoModel;
    private $blockModel;

    protected function setUp()
    {
        parent::setUp();

        $this->pageModel = new AlPageRepositoryPropel();
        $this->seoModel = new AlSeoRepositoryPropel();
        $this->blockModel = new AlBlockRepositoryPropel();
    }

    public function testOpeningAPageThatDoesNotExistShowsTheDefaultWelcomePage()
    {
        $crawler = $this->client->request('GET', 'backend/en/al_showThemes');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());$this->assertEquals(1, $crawler->filter('#al_themes')->count());
        $this->assertEquals(200, $response->getStatusCode());$this->assertEquals(1, $crawler->filter('html:contains("Active Theme")')->count());
        $this->assertEquals(200, $response->getStatusCode());$this->assertEquals(1, $crawler->filter('html:contains("Available Themes")')->count());
        $this->assertEquals(200, $response->getStatusCode());$this->assertEquals(1, $crawler->filter('html:contains("BusinessWebsiteThemeBundle")')->count());

    }

}