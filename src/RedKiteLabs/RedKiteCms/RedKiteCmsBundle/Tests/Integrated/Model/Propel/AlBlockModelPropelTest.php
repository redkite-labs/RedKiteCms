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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Integrated\Model\Propel;

use AlphaLemon\AlphaLemonCmsBundle\Tests\WebTestCaseFunctional;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageModelPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlSeoModelPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\ContentsEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content;



use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page as Listener;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;
use AlphaLemon\Theme\BusinessWebsiteThemeBundle\Core\Slots\BusinessWebsiteThemeBundleHomeSlots;


/**
 * CmsControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockModelPropelTest extends WebTestCaseFunctional
{
    private $pageModel;
    private $seoModel;
    private $blockModel;

    public static function setUpBeforeClass()
    {
        self::$languages = array(
            array('Language'      => 'en'),
            array('Language'      => 'es',)
        );

        self::$pages = array(
            array('PageName'      => 'index',
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
                  'MetaKeywords'      => '')

            );
        self::populateDb();
    }
    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $this->blockModel = $container->get('block_model');
    }

    public function testABlockIsRetrievedFromItsPrimaryKey()
    {
        $block = $this->blockModel->fromPk(2);
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock', $block);
        $this->assertEquals(2, $block->getId());
    }

    public function testRetrieveContentsWithoutRepeatedOnes()
    {
        $blocks = $this->blockModel->retrieveContents(2, 2);
        $this->assertEquals(11, count($blocks));
    }

    public function testRetrieveAllPageContents()
    {
        $blocks = $this->blockModel->retrieveContents(array(1, 2), array(1, 2));
        $this->assertEquals(22, count($blocks));
    }

    public function testRetrieveSlotContents()
    {
        $blocks = $this->blockModel->retrieveContents(array(1, 2), array(1, 2), 'logo');
        $this->assertEquals(1, count($blocks));
        $this->assertEquals('logo', $blocks[0]->getSlotName());
    }

    public function testRetrieveContentsRepeatedAtSiteLevelBySlotName()
    {
        $blocks = $this->blockModel->retrieveContentsBySlotName('logo');
        $this->assertEquals(1, count($blocks));
    }

    public function testRetrieveContentsRepeatedAtLanguageLevelBySlotName()
    {
        $blocks = $this->blockModel->retrieveContentsBySlotName('nav_menu');
        $this->assertEquals(2, count($blocks));
    }

    public function testRetrieveContentsRepeatedAtPageLevelBySlotName()
    {
        $blocks = $this->blockModel->retrieveContentsBySlotName('right_sidebar_content');
        $this->assertEquals(4, count($blocks));
    }

    public function testRetrieveContentsByLanguageId()
    {
        $blocks = $this->blockModel->fromLanguageId(2);
        $this->assertEquals(31, count($blocks));
    }

    public function testRetrieveAllContentsByLanguageId()
    {
        $blocks = $this->blockModel->fromLanguageId(array(1, 2));
        $this->assertEquals(33, count($blocks));
    }

    public function testRetrieveContentsByPageId()
    {
        $blocks = $this->blockModel->fromPageId(2);
        $this->assertEquals(22, count($blocks));
    }

    public function testRetrieveAllContentsByPageId()
    {
        $blocks = $this->blockModel->fromPageId(array(1, 2));
        $this->assertEquals(42, count($blocks));
    }

    public function testRetrieveContentsByHtmlContent()
    {
        $blocks = $this->blockModel->fromHtmlContent('Business');
        $this->assertEquals(22, count($blocks));
    }

    public function testRetrieveContentsByStrictContent()
    {
        $blocks = $this->blockModel->fromHtmlContent('Progress Business Company');
        $this->assertEquals(2, count($blocks));
    }
}