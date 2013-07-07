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

/**
 * ConfigurationControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
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
        $configurationRepository = new \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlConfigurationRepositoryPropel();
        $this->assertEquals($currentLanguage, $configurationRepository->fetchParameter('language')->getValue());
        
        $crawler = $this->client->request('POST', '/backend/en/al_changeCmsLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals($statusCode, $response->getStatusCode());
        if (null !== $message) {
            $this->assertTrue($crawler->filter('html:contains(\'' . $message . '\')')->count() > 0);
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
                'CMS language has been changed. Please wait while your site is reloading',
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
