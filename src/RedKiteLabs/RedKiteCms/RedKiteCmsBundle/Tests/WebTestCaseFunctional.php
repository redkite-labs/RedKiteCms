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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page as Listener;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepository;

/**
 * WebTestCase
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class WebTestCaseFunctional extends WebTestCase
{
    protected $client;
    protected static $languages;
    protected static $pages;

    public static function setUpBeforeClass()
    {
        self::$languages = array(
            array(
                'Language'      => 'en',
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
        $this->client = static::createClient(array(
            'environment' => 'alcms_test',
            'debug'       => true,
            ));

        $activeThemeManager = $this->client->getContainer()->get('alphalemon_theme_engine.active_theme');
        $activeThemeManager->writeActiveTheme('BusinessWebsiteThemeBundle');
    }

    protected static function populateDb()
    {
        $dispatcher = new EventDispatcher();
        $factoryRepository = new AlFactoryRepository('Propel');
        $client = static::createClient(array(
            'environment' => 'alcms_test',
            'debug'       => true,
            ));

        $themes = $client->getContainer()->get('alpha_lemon_theme_engine.themes');
        $theme = $themes->getTheme('BusinessWebsiteThemeBundle');
        $template = $theme->getTemplate('home');

        $pageContentsContainer = new AlPageBlocks($dispatcher, $factoryRepository);
        $templateManager = new AlTemplateManager($dispatcher, $factoryRepository, $template, $pageContentsContainer, $client->getContainer()->get('alpha_lemon_cms.block_manager_factory'));
        $templateManager->refresh();
        $seoManager = new AlSeoManager($dispatcher, $factoryRepository);

        $dispatcher->addListener('pages.before_add_page_commit', array(new Listener\AddSeoListener($seoManager, $factoryRepository), 'onBeforeAddPageCommit'));
        $dispatcher->addListener('pages.before_add_page_commit', array(new Listener\AddPageBlocksListener($factoryRepository), 'onBeforeAddPageCommit'));

        $connection = \Propel::getConnection();
        $queries = array('TRUNCATE al_block;',
                         'TRUNCATE al_language;',
                         'TRUNCATE al_page;',
                         'TRUNCATE al_seo;',
                         'INSERT INTO al_language (language) VALUES(\'-\');',
                         'INSERT INTO al_page (page_name) VALUES(\'-\');',
                        );

        foreach ($queries as $query) {
            $statement = $connection->prepare($query);
            $statement->execute();
        }

        $alLanguageManager = new AlLanguageManager($dispatcher, $factoryRepository, new Validator\AlParametersValidatorLanguageManager($factoryRepository));
        foreach (self::$languages as $language) {
            $alLanguageManager->set(null)->save($language);
        }

        $alPageManager = new AlPageManager($dispatcher, $templateManager, $factoryRepository, new Validator\AlParametersValidatorPageManager($factoryRepository));
        foreach (self::$pages as $page) {
            $alPageManager->set(null)->save($page);
        }
    }
}
