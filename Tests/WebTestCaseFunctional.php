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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepository;

/**
 * WebTestCase
 *
 * @author alphalemon <webmaster@alphalemon.com>
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
                'environment' => 'alcms_test',
                'debug'       => true,
            ),
            array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin',
            )    
        );

        $activeThemeManager = $this->client->getContainer()->get('alphalemon_theme_engine.active_theme');
        $activeThemeManager->writeActiveTheme('BootbusinessThemeBundle'); //BusinessWebsiteThemeBundle
    }

    protected static function populateDb()
    {
        $factoryRepository = new AlFactoryRepository('Propel');
        $client = static::createClient(array(
            'environment' => 'alcms_test',
            'debug'       => true,
            ));

        $themes = $client->getContainer()->get('alpha_lemon_theme_engine.themes');
        $theme = $themes->getTheme('BootbusinessThemeBundle'); //BusinessWebsiteThemeBundle
        $template = $theme->getTemplate('home');

        $eventsHandler = $client->getContainer()->get('alpha_lemon_cms.events_handler');
        $pageContentsContainer = new AlPageBlocks($factoryRepository);
        $templateManager = new AlTemplateManager($eventsHandler, $factoryRepository, $template, $pageContentsContainer, $client->getContainer()->get('alpha_lemon_cms.block_manager_factory'));
        $templateManager->refresh();

        $connection = \Propel::getConnection(); 
        /*$queries = array(
            'DELETE FROM al_block;',
            'DELETE FROM al_language;',
            'DELETE FROM al_locked_resource;',
            'DELETE FROM al_page;',
            'DELETE FROM al_seo;',
            'DELETE FROM al_role;',
            'DELETE FROM al_user;',
            'INSERT INTO al_language (language_name) VALUES(\'-\');',
            'INSERT INTO al_page (page_name) VALUES(\'-\');',
        );*/
        
        
        $queries = array(
            'TRUNCATE  al_block;',
            'TRUNCATE  al_language;',
            'TRUNCATE  al_locked_resource;',
            'TRUNCATE  al_page;',
            'TRUNCATE  al_seo;',
            'TRUNCATE  al_role;',
            'TRUNCATE  al_user;',
            'INSERT INTO al_language (language_name) VALUES(\'-\');',
            'INSERT INTO al_page (page_name) VALUES(\'-\');',
        );

        foreach ($queries as $query) {
            $statement = $connection->prepare($query);
            $statement->execute();
        }
        
        $roles = array('ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN');
        foreach ($roles as $role) {
            $alRole = new \AlphaLemon\AlphaLemonCmsBundle\Model\AlRole();
            $alRole->setRole($role);
            $alRole->save();

            self::$roles[$role] = $alRole->getId();
        }

        self::addUser('admin', 'admin', self::$roles['ROLE_ADMIN']);
        
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
    }
    
    protected static function addUser($username, $password, $adminRoleId)
    {
        $user = new \AlphaLemon\AlphaLemonCmsBundle\Model\AlUser();
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
