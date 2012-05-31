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
 * LanguagesControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class LanguagesControllerTest extends WebTestCaseFunctional
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

    public function testFormElements()
    {
        $crawler = $this->client->request('GET', 'backend/en/al_showLanguages');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('#languages_language')->count());
        $this->assertEquals(1, $crawler->filter('#languages_isMain')->count());
        $this->assertEquals(1, $crawler->filter('#al_language_saver')->count());
    }

    public function testAddLanguageFailsWhenPageNameParamIsMissing()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'isMain' => "0",);

        $crawler = $this->client->request('POST', 'backend/en/al_saveLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('The name to assign to the page cannot be null. Please provide a valid page name to add your page', $crawler->text());
    }
}
