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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Translation;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page as Listener;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainer;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager;
use AlphaLemon\Theme\BusinessWebsiteThemeBundle\Core\Slots\BusinessWebsiteThemeBundleHomeSlots;

/**
 * WebTestCase
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class WebTestCaseFunctional extends WebTestCase {
    
    protected $client;
    
    public static function setUpBeforeClass()
    {
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
        $translator = new Translation\Translator('en', new Translation\MessageSelector());
        $seoModel = new Propel\AlSeoModelPropel($dispatcher);    
        $languageModel = new Propel\AlLanguageModelPropel($dispatcher);   
        $pageModel = new Propel\AlPageModelPropel($dispatcher);
        $blockModel = new Propel\AlBlockModelPropel($dispatcher);
        
        $dir = realpath(__DIR__ . '/Functional/Resources/fixtures');
        $templateSlots = new BusinessWebsiteThemeBundleHomeSlots(null, $dir);         
        $pageContentsContainer = new AlPageContentsContainer($dispatcher, $blockModel);
        $templateManager = new AlTemplateManager($dispatcher, $translator, $pageContentsContainer, $blockModel);
        $templateManager->setTemplateSlots($templateSlots)
                ->refresh();
        $seoManager = new AlSeoManager($dispatcher, $translator, $seoModel);
        
        $dispatcher->addListener('pages.before_add_page_commit', array(new Listener\AddSeoListener($seoManager, $languageModel), 'onBeforeAddPageCommit'));
        $dispatcher->addListener('pages.before_add_page_commit', array(new Listener\AddPageContentsListener($languageModel), 'onBeforeAddPageCommit'));
        
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
        
        $language = new \AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage();
        $language->setLanguage('en');
        $language->setMainLanguage(1);
        $language->save();
        // Temporary
        /*
        $language = new \AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage();
        $language->setLanguage('it');
        $language->setMainLanguage(0);
        $language->save();*/
        
        $alPageManager = new AlPageManager($dispatcher, $translator, $templateManager, $pageModel, new AlParametersValidatorPageManager($translator, $languageModel, $pageModel));
        $params = array('PageName'      => 'index', 
                        'TemplateName'  => 'home',
                        'IsHome'        => '1',
                        'Permalink'     => 'this is a website fake page',
                        'Title'         => 'page title',
                        'Description'   => 'page description',
                        'Keywords'      => '');
        $alPageManager->set(null)->save($params);        
    }
    
}