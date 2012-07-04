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
use AlphaLemon\Theme\BusinessWebsiteThemeBundle\Core\Slots\BusinessWebsiteThemeBundleHomeSlots;

/**
 * WebTestCase
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class WebTestCaseFunctional extends WebTestCase {

    protected $client;
    protected static $languages;
    protected static $pages;

    public static function setUpBeforeClass()
    {
        self::$languages = array(array('Language'      => 'en',));

        self::$pages = array(array('PageName'    => 'index',
                                    'TemplateName'      => 'home',
                                    'IsHome'            => '1',
                                    'Permalink'         => 'this is a website fake page',
                                    'MetaTitle'         => 'page title',
                                    'MetaDescription'   => 'page description',
                                    'MetaKeywords'      => ''));
        self::populateDb();
    }

    protected function setUp()
    {
        $this->client = static::createClient(array(
            'environment' => 'alcms_test',
            'debug'       => true,
            ));
    }

    protected static function populateDb()
    {
        if (!\Propel::isInit()) {
            include __DIR__ . '/Functional/Resources/config/propelConfiguration.php';
            \Propel::setConfiguration($config);
            \Propel::initialize();
        }

        $dispatcher = new EventDispatcher();
        $seoRepository = new Propel\AlSeoRepositoryPropel();
        $languageRepository = new Propel\AlLanguageRepositoryPropel();
        $pageRepository = new Propel\AlPageRepositoryPropel();
        $blockRepository = new Propel\AlBlockRepositoryPropel();

        $client = static::createClient(array(
            'environment' => 'alcms_test',
            'debug'       => true,
            ));

        $dir = realpath(__DIR__ . '/Functional/Resources/fixtures');
        $templateSlots = new BusinessWebsiteThemeBundleHomeSlots(null, $dir);
        $template = new \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate(new \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets(), $client->getContainer()->get('kernel'), $client->getContainer()->get('template_slots_factory'));
        $template->setTemplateSlots($templateSlots);
        $pageContentsContainer = new AlPageBlocks($dispatcher, $blockRepository);
        $templateManager = new AlTemplateManager($dispatcher, $template, $pageContentsContainer, $blockRepository, $client->getContainer()->get('alphalemon_cms.block_manager_factory'));
        $templateManager
                ->refresh();
        $seoManager = new AlSeoManager($dispatcher, $seoRepository);

        $dispatcher->addListener('pages.before_add_page_commit', array(new Listener\AddSeoListener($seoManager, $languageRepository), 'onBeforeAddPageCommit'));
        $dispatcher->addListener('pages.before_add_page_commit', array(new Listener\AddPageBlocksListener($languageRepository), 'onBeforeAddPageCommit'));

        $connection = \Propel::getConnection();
        $queries = array('TRUNCATE al_block;',
                         'TRUNCATE al_language;',
                         'TRUNCATE al_page;',
                         'TRUNCATE al_seo;',
                         'TRUNCATE al_theme;',
                         'INSERT INTO al_language (language) VALUES(\'-\');',
                         'INSERT INTO al_page (page_name) VALUES(\'-\');',
                        );

        foreach($queries as $query)
        {
            $statement = $connection->prepare($query);
            $statement->execute();
        }

        // Temporary
        $theme = new \AlphaLemon\ThemeEngineBundle\Model\AlTheme();
        $theme->setThemeName('BusinessWebsiteThemeBundle');
        $theme->setActive(1);
        $theme->save();

        $alLanguageManager = new AlLanguageManager($dispatcher, $languageRepository, new Validator\AlParametersValidatorLanguageManager($languageRepository));
        foreach(self::$languages as $language) {
            $alLanguageManager->set(null)->save($language);
        }

        $alPageManager = new AlPageManager($dispatcher, $templateManager, $pageRepository, new Validator\AlParametersValidatorPageManager($languageRepository, $pageRepository));
        foreach(self::$pages as $page) {
            $alPageManager->set(null)->save($page);
        }
    }
}