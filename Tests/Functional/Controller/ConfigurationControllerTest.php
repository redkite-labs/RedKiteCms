<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Functional\Controller;

use RedKiteLabs\RedKiteCmsBundle\Tests\WebTestCaseFunctional;

/**
 * ConfigurationControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ConfigurationControllerTest extends WebTestCaseFunctional
{
    public static function setUpBeforeClass()
    {
        self::$languages = array(
            array(
                'LanguageName'      => 'en',
                'CreatedAt'       => date("Y-m-d H:i:s")
            ),
            array(
                'LanguageName'      => 'it',
                'CreatedAt'       => date("Y-m-d H:i:s")
            ),
        );

        self::$pages = array(
            array(
                'PageName'          => 'index',
                'TemplateName'      => 'home',
                'IsHome'            => '1',
                'Permalink'         => 'this is a website fake page',
                'MetaTitle'         => 'page title',
                'MetaDescription'   => 'page description',
                'MetaKeywords'      => '',
                'CreatedAt'       => date("Y-m-d H:i:s")
            )
        );

        self::populateDb();
    }

    /**
     * @dataProvider languagesProvider
     */
    public function testChangeLanguage($params, $currentLanguage, $newLanguage, $statusCode, $message)
    {
        $configurationRepository = new \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlConfigurationRepositoryPropel();
        $this->assertEquals($currentLanguage, $configurationRepository->fetchParameter('language')->getValue());
        
        $crawler = $this->client->request('POST', '/backend/en/al_changeCmsLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals($statusCode, $response->getStatusCode());
        if (null !== $message) {
            $this->assertCount(1, $crawler->filter('html:contains(\'' . $message . '\')'));
        }
        
        $this->assertEquals($newLanguage, $configurationRepository->fetchParameter('language')->getValue());
    }
    
    public function languagesProvider()
    {
        return array(
            array(
                array(
                    'languageName' => 'en',
                ),
                'en',
                'en',
                404,
                'The language "en" is the one already in use',
            ),
            array(
                array(
                    'languageName' => 'it',
                ),
                'en',
                'it',
                200,
                null,
            ),
            array(
                array(
                    'languageName' => 'en',
                ),
                'it',
                'en',
                200,
                null,
            ),
        );
    }
}
