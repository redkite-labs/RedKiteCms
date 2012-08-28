<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Deploy\TwigTemplateWriter;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\TwigTemplateWriter\AlTwigTemplateWriter;
use org\bovigo\vfs\vfsStream;

/**
 * AlTwigTemplateWriterTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTwigTemplateWriterTest extends TestCase
{
    private $pageTree;
    private $urlManager;

    protected function setUp()
    {
        parent::setUp();

        $this->pageTree = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageTree->expects($this->once())
            ->method('getTemplate')
            ->will($this->returnValue($this->setUpTemplate()));

        $this->pageTree->expects($this->any())
            ->method('getAlPage')
            ->will($this->returnValue($this->setUpPage('index')));

        $this->pageTree->expects($this->any())
            ->method('getAlLanguage')
            ->will($this->returnValue($this->setUpLanguage('en')));

        $this->urlManager = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface');
        $this->urlManager->expects($this->any())
            ->method('fromUrl')
            ->will($this->returnSelf());

        $this->blockManagerFactory = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');

        $this->root = vfsStream::setup('root');
    }

    public function testExtendsDirectiveHasBeenCreatedForTheGivenTemplate()
    {
        $this->setUpMetatagsAndAssets(null, null, null, array(), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);

        $this->assertEquals("{% extends 'FakeTheme:Theme:Home.html.twig' %}" . PHP_EOL, $twigTemplateWriter->getTemplateSection());
    }

    public function testJustTheMetaTagsTitleSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets("A title", null, null, array(), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);

        $section = "\n{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block title %}" . PHP_EOL;
        $section .= "A title" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testJustTheMetaDescriptionSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets(null, "A description", null, array(), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);

        $section = "\n{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block description %}" . PHP_EOL;
        $section .= "A description" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testJustTheKeywordsSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets(null, null, "some,keywords", array(), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);

        $section = "\n{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block keywords %}" . PHP_EOL;
        $section .= "some,keywords" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testAllMetatagsSectionsAreCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);

        $section = "\n{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block title %}" . PHP_EOL;
        $section .= "A title" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "{% block description %}" . PHP_EOL;
        $section .= "A description" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "{% block keywords %}" . PHP_EOL;
        $section .= "some,keywords" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testJustTheExternalStylesheetsSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);

        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block external_stylesheets %}" . PHP_EOL;
        $section .= "  {% stylesheets \"style1.css\" \"style2.css\" filter=\"?yui_css,cssrewrite\" %}" . PHP_EOL;
        $section .= "    <link href=\"{{ asset_url }}\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />" . PHP_EOL;
        $section .= "  {% endstylesheets %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testJustTheExternalJavascriptsSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array('javascript1.js', 'javascript2.js'), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);

        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block external_javascripts %}" . PHP_EOL;
        $section .= "  {% javascripts \"javascript1.js\" \"javascript2.js\" filter=\"?yui_js\" %}" . PHP_EOL;
        $section .= "    <script src=\"{{ asset_url }}\"></script>" . PHP_EOL;
        $section .= "  {% endjavascripts %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testJustTheInternalStylesheetsSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array(), 'some css code', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);

        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block internal_header_stylesheets %}" . PHP_EOL;
        $section .= "<style>some css code</style>" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }


    public function testJustTheInternalJavascriptsSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array(), '', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);

        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block internal_header_javascripts %}" . PHP_EOL;
        $section .= "<script>$(document).ready(function(){some js code});</script>" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testAllAssetsSectionsAreCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);

        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block external_stylesheets %}" . PHP_EOL;
        $section .= "  {% stylesheets \"style1.css\" \"style2.css\" filter=\"?yui_css,cssrewrite\" %}" . PHP_EOL;
        $section .= "    <link href=\"{{ asset_url }}\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />" . PHP_EOL;
        $section .= "  {% endstylesheets %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "{% block external_javascripts %}" . PHP_EOL;
        $section .= "  {% javascripts \"javascript1.js\" \"javascript2.js\" filter=\"?yui_js\" %}" . PHP_EOL;
        $section .= "    <script src=\"{{ asset_url }}\"></script>" . PHP_EOL;
        $section .= "  {% endjavascripts %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "{% block internal_header_stylesheets %}" . PHP_EOL;
        $section .= "<style>some css code</style>" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "{% block internal_header_javascripts %}" . PHP_EOL;
        $section .= "<script>$(document).ready(function(){some js code});</script>" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testContentsSectionWithOneBlockHaveBeenCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block logo %}" . PHP_EOL;
        $section .= "  {% if(slots.logo is not defined) %}" . PHP_EOL;
        $section .= "    <!-- BEGIN LOGO BLOCK -->" . PHP_EOL;
        $section .= "    Formatted content for deploying" . PHP_EOL;
        $section .= "    <!-- END LOGO BLOCK -->" . PHP_EOL;
        $section .= "  {% else %}" . PHP_EOL;
        $section .= "    {{ parent() }}" . PHP_EOL;
        $section .= "  {% endif %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);//echo nl2br($twigTemplateWriter->getContentsSection());exit;
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testContentsSectionWithOneBlockAndImagesReplaceHaveBeenCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory('<img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/download.png">');

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block logo %}" . PHP_EOL;
        $section .= "  {% if(slots.logo is not defined) %}" . PHP_EOL;
        $section .= "    <!-- BEGIN LOGO BLOCK -->" . PHP_EOL;
        $section .= "    <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/download.png\">" . PHP_EOL;
        $section .= "    <!-- END LOGO BLOCK -->" . PHP_EOL;
        $section .= "  {% else %}" . PHP_EOL;
        $section .= "    {{ parent() }}" . PHP_EOL;
        $section .= "  {% endif %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->urlManager->expects($this->any())
            ->method('getProductionRoute')
            ->will($this->returnValue(null));

        $imagesPath = array('backendPath' => "/bundles/alphalemoncms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager, $imagesPath);
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }


    public function testContentsSectionWithOneBlockAndLinkNotReplaceBecauseNotRecognizedAsInternalRouteHaveBeenCreated()
    {
        $this->urlManager->expects($this->any())
            ->method('getProductionRoute')
            ->will($this->returnValue(null));

        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory('<ul><li><a href="my-awesome-page">Fancy page</a></li></ul>');

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block logo %}" . PHP_EOL;
        $section .= "  {% if(slots.logo is not defined) %}" . PHP_EOL;
        $section .= "    <!-- BEGIN LOGO BLOCK -->" . PHP_EOL;
        $section .= "    <ul><li><a href=\"my-awesome-page\">Fancy page</a></li></ul>" . PHP_EOL;
        $section .= "    <!-- END LOGO BLOCK -->" . PHP_EOL;
        $section .= "  {% else %}" . PHP_EOL;
        $section .= "    {{ parent() }}" . PHP_EOL;
        $section .= "  {% endif %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testContentsSectionWithOneBlockAndLinkReplaceHaveBeenCreated()
    {
        $this->urlManager->expects($this->any())
            ->method('getProductionRoute')
            ->will($this->returnValue('_en_index'));

        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory('<ul><li><a href="my-awesome-page">Fancy page</a></li></ul>');

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block logo %}" . PHP_EOL;
        $section .= "  {% if(slots.logo is not defined) %}" . PHP_EOL;
        $section .= "    <!-- BEGIN LOGO BLOCK -->" . PHP_EOL;
        $section .= "    <ul><li><a href=\"{{ path('_en_index') }}\">Fancy page</a></li></ul>" . PHP_EOL;
        $section .= "    <!-- END LOGO BLOCK -->" . PHP_EOL;
        $section .= "  {% else %}" . PHP_EOL;
        $section .= "    {{ parent() }}" . PHP_EOL;
        $section .= "  {% endif %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testContentsSectionWithMoreBlocksHaveBeenCreated()
    {
        $blocks = array(
            "logo" =>
                array(
                    $this->setUpBlock()
                ),
            "nav-menu" =>
                array(
                    $this->setUpBlock(),
                    $this->setUpBlock()
                )
            );

        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks($blocks);

        $blockManager1 = $this->setUpBlockManager('<img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/download.png">');
        $blockManager2 = $this->setUpBlockManager('<div>A new content</div>');
        $blockManager3 = $this->setUpBlockManager('<div>Some other text <img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/image.png"></div>');
        $this->blockManagerFactory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->onConsecutiveCalls($blockManager1, $blockManager2, $blockManager3));

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block logo %}" . PHP_EOL;
        $section .= "  {% if(slots.logo is not defined) %}" . PHP_EOL;
        $section .= "    <!-- BEGIN LOGO BLOCK -->" . PHP_EOL;
        $section .= "    <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/download.png\">" . PHP_EOL;
        $section .= "    <!-- END LOGO BLOCK -->" . PHP_EOL;
        $section .= "  {% else %}" . PHP_EOL;
        $section .= "    {{ parent() }}" . PHP_EOL;
        $section .= "  {% endif %}" . PHP_EOL;
        $section .= "{% endblock %}" . PHP_EOL;
        $section .= "" . PHP_EOL;
        $section .= "{% block nav-menu %}" . PHP_EOL;
        $section .= "  {% if(slots.nav-menu is not defined) %}" . PHP_EOL;
        $section .= "    <!-- BEGIN NAV-MENU BLOCK -->" . PHP_EOL;
        $section .= "    <div>A new content</div>" . PHP_EOL;
        $section .= "    <div>Some other text <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/image.png\"></div>" . PHP_EOL;
        $section .= "    <!-- END NAV-MENU BLOCK -->" . PHP_EOL;
        $section .= "  {% else %}" . PHP_EOL;
        $section .= "    {{ parent() }}" . PHP_EOL;
        $section .= "  {% endif %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $imagesPath = array('backendPath' => "/bundles/alphalemoncms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager, $imagesPath);
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testGenerateFullTemplate()
    {
        $blocks = array(
            "logo" =>
                array(
                    $this->setUpBlock()
                ),
            "nav-menu" =>
                array(
                    $this->setUpBlock(),
                    $this->setUpBlock(),
                    $this->setUpBlock()
                )
            );

        $this->urlManager->expects($this->any())
            ->method('getProductionRoute')
            ->will($this->returnValue('_en_index'));

        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks($blocks);
        $blockManager1 = $this->setUpBlockManager('<img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/download.png">');
        $blockManager2 = $this->setUpBlockManager('<div>A new content</div>');
        $blockManager3 = $this->setUpBlockManager('<div>Some other text <img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/image.png"></div>');
        $blockManager4 = $this->setUpBlockManager('<div>Lorem ipsum <ul><li><a href="my-awesome-page">Fancy page</a></li></ul></div>');
        $this->blockManagerFactory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->onConsecutiveCalls($blockManager1, $blockManager2, $blockManager3, $blockManager4));

        $section = "{% extends 'FakeTheme:Theme:Home.html.twig' %}" . PHP_EOL;
        $section .= "\n{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block title %}" . PHP_EOL;
        $section .= "A title" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "{% block description %}" . PHP_EOL;
        $section .= "A description" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "{% block keywords %}" . PHP_EOL;
        $section .= "some,keywords" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block external_stylesheets %}" . PHP_EOL;
        $section .= "  {% stylesheets \"style1.css\" \"style2.css\" filter=\"?yui_css,cssrewrite\" %}" . PHP_EOL;
        $section .= "    <link href=\"{{ asset_url }}\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />" . PHP_EOL;
        $section .= "  {% endstylesheets %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "{% block external_javascripts %}" . PHP_EOL;
        $section .= "  {% javascripts \"javascript1.js\" \"javascript2.js\" filter=\"?yui_js\" %}" . PHP_EOL;
        $section .= "    <script src=\"{{ asset_url }}\"></script>" . PHP_EOL;
        $section .= "  {% endjavascripts %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "{% block internal_header_stylesheets %}" . PHP_EOL;
        $section .= "<style>some css code</style>" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "{% block internal_header_javascripts %}" . PHP_EOL;
        $section .= "<script>$(document).ready(function(){some js code});</script>" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= "\n{#--------------  CONTENTS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block logo %}" . PHP_EOL;
        $section .= "  {% if(slots.logo is not defined) %}" . PHP_EOL;
        $section .= "    <!-- BEGIN LOGO BLOCK -->" . PHP_EOL;
        $section .= "    <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/download.png\">" . PHP_EOL;
        $section .= "    <!-- END LOGO BLOCK -->" . PHP_EOL;
        $section .= "  {% else %}" . PHP_EOL;
        $section .= "    {{ parent() }}" . PHP_EOL;
        $section .= "  {% endif %}" . PHP_EOL;
        $section .= "{% endblock %}" . PHP_EOL;
        $section .= "" . PHP_EOL;
        $section .= "{% block nav-menu %}" . PHP_EOL;
        $section .= "  {% if(slots.nav-menu is not defined) %}" . PHP_EOL;
        $section .= "    <!-- BEGIN NAV-MENU BLOCK -->" . PHP_EOL;
        $section .= "    <div>A new content</div>" . PHP_EOL;
        $section .= "    <div>Some other text <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/image.png\"></div>" . PHP_EOL;
        $section .= "    <div>Lorem ipsum <ul><li><a href=\"{{ path('_en_index') }}\">Fancy page</a></li></ul></div>" . PHP_EOL;
        $section .= "    <!-- END NAV-MENU BLOCK -->" . PHP_EOL;
        $section .= "  {% else %}" . PHP_EOL;
        $section .= "    {{ parent() }}" . PHP_EOL;
        $section .= "  {% endif %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $imagesPath = array('backendPath' => "/bundles/alphalemoncms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager, $imagesPath);
        $this->assertEquals($section, $twigTemplateWriter->getTwigTemplate());
    }

    public function testWriteFile()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $this->assertFalse($this->root->hasChild('en'));
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->blockManagerFactory, $this->urlManager);
        $twigTemplateWriter->writeTemplate(vfsStream::url('root'));
        $this->assertTrue($this->root->hasChild('en'));
        $this->assertTrue($this->root->getChild('en')->hasChild('index.html.twig'));
    }

    private function setUpPage($pageName)
    {
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->any())
            ->method('getPageName')
            ->will($this->returnValue($pageName));

        return $page;
    }

    private function setUpLanguage($languageName)
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getLanguage')
            ->will($this->returnValue($languageName));

        return $language;
    }

    private function setUpPageBlocks(array $blocks = null)
    {
        if(null === $blocks) $blocks = array("logo" => array($this->setUpBlock('my content')));

        $pageBlocks = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $pageBlocks->expects($this->any())
            ->method('getBlocks')
            ->will($this->returnValue($blocks));

        $this->pageTree->expects($this->once())
            ->method('getPageBlocks')
            ->will($this->returnValue($pageBlocks));
    }

    private function setUpMetatagsAndAssets($title, $description, $keywords, $externalStylesheets, $externalJavascripts, $internalStylesheets, $internalJavascripts)
    {
        $this->pageTree->expects($this->any())
                ->method('__call')
                ->will($this->onConsecutiveCalls($title, $description, $keywords, $externalStylesheets, $externalJavascripts, $internalStylesheets, $internalJavascripts));
    }

    private function setUpTemplate()
    {
        $template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $template->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('FakeTheme'));

        $template->expects($this->once())
            ->method('getTemplateName')
            ->will($this->returnValue('Home'));

        $template->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue(array('logo' => array('repeated' => 'site'), 'nav-menu' => array('repeated' => 'language'))));

        return $template;
    }


    private function setUpBlock()
    {
        return $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
    }

    private function setUpBlockManager($deployContent = 'Formatted content for deploying')
    {
        $blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $blockManager->expects($this->once())
            ->method('getHtml')
            ->will($this->returnValue($deployContent));

        return $blockManager;
    }

    private function setUpBlockManagerFactory($deployContent = 'Formatted content for deploying', $callingTimes = 1)
    {
        $blockManager = $this->setUpBlockManager($deployContent);

        $this->blockManagerFactory->expects($this->exactly($callingTimes))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager));
    }
}
