<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Language\AlLanguageManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Page\AlPageManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepository;
use RedKiteLabs\RedKiteCmsBundle\Model\AlRole;

/**
 * WebTestCase
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class WebTestCaseFunctional extends WebTestCase
{
    protected $client;
    protected static $languages;
    protected static $pages;
    protected static $roles = array();

    public static function setUpBeforeClass()
    {
        self::$languages = array(
            array(
                'LanguageName'      => 'en',
                'CreatedAt'       => date("Y-m-d H:i:s")
            )
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

    protected function setUp()
    {
        $this->client = static::createClient(
            array(
                'environment' => 'rkcms_test',
                'debug'       => true,
            ),
            array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin',
            )    
        );

        $activeThemeManager = $this->client->getContainer()->get('red_kite_cms.active_theme');
        $activeThemeManager->writeActiveTheme('BootbusinessThemeBundle'); //BusinessWebsiteThemeBundle        
    }

    protected static function populateDb()
    {
        $client = static::createClient(array(
            'environment' => 'rkcms_test',
            'debug'       => true,
        ));
            
        $connection = \Propel::getConnection();
        $connection->getConfiguration();
        $adapter = $connection->getConfiguration()->getParameter('datasources.default.adapter');
        switch ($adapter)
        {
            case "mysql":
                $queries = array(
                    'SET FOREIGN_KEY_CHECKS=0;',
                    'TRUNCATE al_block;',
                    'TRUNCATE al_configuration;',
                    'TRUNCATE al_language;',
                    'TRUNCATE al_locked_resource;',
                    'TRUNCATE al_page;',
                    'TRUNCATE al_seo;',
                    'TRUNCATE al_role;',
                    'TRUNCATE al_user;',
                    'INSERT INTO al_language (language_name) VALUES(\'-\');',
                    'INSERT INTO al_page (page_name) VALUES(\'-\');',
                    'INSERT INTO al_configuration (parameter, value) VALUES(\'language\', \'en\');',
                    'SET FOREIGN_KEY_CHECKS=1;',
                );
                break;
            case "pgsql":
                $queries = array(
                    'TRUNCATE al_configuration RESTART IDENTITY;',
                    'TRUNCATE al_page RESTART IDENTITY CASCADE;',
                    'TRUNCATE al_language RESTART IDENTITY CASCADE;',
                    'TRUNCATE al_block RESTART IDENTITY CASCADE;',
                    'TRUNCATE al_locked_resource RESTART IDENTITY;',                    
                    'TRUNCATE al_seo RESTART IDENTITY CASCADE;',
                    'TRUNCATE al_user RESTART IDENTITY CASCADE;',
                    'TRUNCATE al_role RESTART IDENTITY CASCADE;',
                    'INSERT INTO al_language (language_name) VALUES(\'-\');',
                    'INSERT INTO al_page (page_name) VALUES(\'-\');',
                    'INSERT INTO al_configuration (parameter, value) VALUES(\'language\', \'en\');',
                );
                break;
            case "sqlite":
                $queries = array(
                    'DELETE FROM al_block;',
                    'DELETE FROM al_configuration;',
                    'DELETE FROM al_language;',
                    'DELETE FROM al_locked_resource;',
                    'DELETE FROM al_page;',
                    'DELETE FROM al_seo;',
                    'DELETE FROM al_role;',
                    'DELETE FROM al_user;',
                    'INSERT INTO al_language (language_name) VALUES(\'-\');',
                    'INSERT INTO al_page (page_name) VALUES(\'-\');',
                    'INSERT INTO al_configuration (parameter, value) VALUES(\'language\', \'en\');',
                );
                break;
        }
        
        foreach ($queries as $query) {
            $statement = $connection->prepare($query);
            $statement->execute();
        }
        
        $factoryRepository = new AlFactoryRepository('Propel');
        
        $themes = $client->getContainer()->get('red_kite_labs_theme_engine.themes');
        $theme = $themes->getTheme('BootbusinessThemeBundle');
        $template = $theme->getTemplate('home');

        $eventsHandler = $client->getContainer()->get('red_kite_cms.events_handler');
        $pageContentsContainer = new AlPageBlocks($factoryRepository);
        $templateManager = new AlTemplateManager($eventsHandler, $factoryRepository, $template, $pageContentsContainer, $client->getContainer()->get('red_kite_cms.block_manager_factory'));
        $templateManager->refresh();

        
        $alLanguageManager = new AlLanguageManager($eventsHandler, $factoryRepository, new Validator\AlParametersValidatorLanguageManager($factoryRepository));
        foreach (self::$languages as $language) {
            $alLanguageManager->set(null)->save($language);
        }

        $alPageManager = new AlPageManager($eventsHandler, $templateManager, $factoryRepository, new Validator\AlParametersValidatorPageManager($factoryRepository));
        foreach (self::$pages as $page) {
            if (isset($page["TemplateName"]))
            {
                $template = $theme->getTemplate($page["TemplateName"]);
                $templateManager->setTemplate($template);
                $alPageManager->setTemplateManager($templateManager);
            }
            $alPageManager->set(null)->save($page);
        }
        
        $roles = array('ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN');
        foreach ($roles as $role) {
            $alRole = new AlRole();
            $alRole->setRole($role);
            $alRole->save();

            self::$roles[$role] = $alRole->getId();
        }

        self::addUser('admin', 'admin', self::$roles['ROLE_ADMIN']);
        
    }
    
    protected static function addUser($username, $password, $adminRoleId)
    {
        $user = new \RedKiteLabs\RedKiteCmsBundle\Model\AlUser();
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder();
        $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $password = $encoder->encodePassword($password, $salt);

        $user->setSalt($salt);
        $user->setPassword($password);
        $user->setRoleId($adminRoleId);
        $user->setUsername($username);
        $user->setEmail('');
        $user->save();
    }
}
