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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

require_once __DIR__ . '/project/src/Acme/WebSiteBundle/AcmeWebSiteBundle.php';

/**
 * InstallerControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class InstallerControllerTest extends WebTestCase
{
    private $pageRepository;
    private $seoRepository;
    private $blockRepository;
/*
    public static function setUpBeforeClass()
    {
        self::$languages = array(array('Language'      => 'en',));

        self::$pages = array(array('PageName'      => 'index',
                                    'TemplateName'  => 'home',
                                    'IsHome'        => '1',
                                    'Permalink'     => 'this is a website fake page',
                                    'MetaTitle'         => 'page title',
                                    'MetaDescription'   => 'page description',
                                    'MetaKeywords'      => 'key'),
                            array('PageName'      => 'page1',
                                    'TemplateName'  => 'fullpage',
                                    'Permalink'     => 'page-1',
                                    'MetaTitle'         => 'page 1 title',
                                    'MetaDescription'   => 'page 1 description',
                                    'MetaKeywords'      => ''));
        self::populateDb();
    }

    protected function setUp()
    {
        parent::setUp();

        $this->pageRepository = new AlPageRepositoryPropel();
        $this->seoRepository = new AlSeoRepositoryPropel();
        $this->blockRepository = new AlBlockRepositoryPropel();
    }*/

    protected function setUp()
    {
        $this->client = static::createClient(array(
            'environment' => 'test',
            'debug'       => true,
            ));
    }

    public function testOpeningAPageThatDoesNotExistShowsTheDefaultWelcomePage()
    {
        $crawler = $this->client->request('GET', 'install');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("AlphaLemon CMS requires a bundle where AlphaLemon CMS will save the contents you insert")')->count() > 0);
        $this->assertEquals(1, $crawler->filter('#install_company')->count());
        //$this->assertTrue($crawler->filter('html:contains("This is the AlphaLemon CMS background and usually it should be hide")')->count() > 0);
    }
}