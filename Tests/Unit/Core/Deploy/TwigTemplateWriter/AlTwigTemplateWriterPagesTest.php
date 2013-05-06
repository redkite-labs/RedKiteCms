<?php
/**
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

use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\TwigTemplateWriter\AlTwigTemplateWriterPages;
use org\bovigo\vfs\vfsStream;

/**
 * AlTwigTemplateWriterPagesTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTwigTemplateWriterPagesPagesTest extends BaseAlTwigTemplateWriter
{
    protected function setUp()
    {
        parent::setUp();
        
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->pageTree->expects($this->once())
            ->method('getContainer')
            ->will($this->returnValue($this->container));
    }

    public function testExtendsDirectiveHasBeenCreatedForTheCurrentPage()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets(null, null, null, array(), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $this->assertEquals("{% extends 'AcmeWebsiteBundle:AlphaLemon:en/base/home.html.twig' %}" . PHP_EOL, $twigTemplateWriter->getTemplateSection());
    }

    public function testJustTheMetaTagsTitleSectionIsCreated()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", null, null, array(), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $section = "\n{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block title %}A title{% endblock %}" . PHP_EOL;
        
        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testJustTheMetaDescriptionSectionIsCreated()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets(null, "A description", null, array(), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $section = "\n{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block description %}A description{% endblock %}" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testJustTheKeywordsSectionIsCreated()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets(null, null, "some,keywords", array(), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $section = "\n{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block keywords %}some,keywords{% endblock %}" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testAllMetatagsSectionsAreCreated()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $section = "\n{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block title %}A title{% endblock %}" . PHP_EOL;
        $section .= "{% block description %}A description{% endblock %}" . PHP_EOL;
        $section .= "{% block keywords %}some,keywords{% endblock %}" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testJustTheExternalStylesheetsSectionIsCreated()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array(), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block external_stylesheets %}" . PHP_EOL;
        $section .= "  {% stylesheets \"style1.css\" \"style2.css\" filter=\"?yui_css,cssrewrite\" %}" . PHP_EOL;
        $section .= "    <link href=\"{{ asset_url }}\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />" . PHP_EOL;
        $section .= "  {% endstylesheets %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testExternalJavascriptsWithoutYuiCompresssor()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array('javascript1.js', 'javascript2.js'), '', '');
        $this->setUpPageBlocks(null, false);
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block external_javascripts %}" . PHP_EOL;
        $section .= "  {% javascripts \"javascript1.js\" \"javascript2.js\" filter=\"\" %}" . PHP_EOL;
        $section .= "    <script src=\"{{ asset_url }}\"></script>" . PHP_EOL;
        $section .= "  {% endjavascripts %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testExternalStylesheetsWithoutYuiCompresssor()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array(), '', '');
        $this->setUpPageBlocks(null, false);
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block external_stylesheets %}" . PHP_EOL;
        $section .= "  {% stylesheets \"style1.css\" \"style2.css\" filter=\"?cssrewrite\" %}" . PHP_EOL;
        $section .= "    <link href=\"{{ asset_url }}\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />" . PHP_EOL;
        $section .= "  {% endstylesheets %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testExternalStylesheetsWithoutYuiCompresssortestJustTheExternalJavascriptsSectionIsCreated()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array('javascript1.js', 'javascript2.js'), '', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
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
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array(), 'some css code', '');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block internal_header_stylesheets %}" . PHP_EOL;
        $section .= "<style>some css code</style>" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testJustTheInternalJavascriptsSectionIsCreated()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array(), '', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();
        
        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block internal_header_javascripts %}" . PHP_EOL;
        $section .= "<script>$(document).ready(function(){some js code});</script>" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testImagesAreFixedForProduction()
    {
        $jsCode = 'doSomething({images:["/bundles/alphalemoncms/uploads/assets/media/screenshots/img01.png",],});';
        $expectedJsCode = 'doSomething({images:["/bundles/acmewebsite/media/screenshots/img01.png",],});';

        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array(), '', $jsCode);
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $imagesPath = array('backendPath' => "/bundles/alphalemoncms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");
        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer, $imagesPath);
        $twigTemplateWriter->generateTemplate();
        
        $jsCode = 'doSomething({
                                images:[
                 "/bundles/alphalemoncms/uploads/assets/media/screenshots/img01.png",
                 ],
            });';

        $section = "\n{#--------------  ASSETS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block internal_header_javascripts %}" . PHP_EOL;
        $section .= "<script>$(document).ready(function(){" . $expectedJsCode . "});</script>" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testAllAssetsSectionsAreCreated()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
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

    public function testWhenTheBlockManagerIsNotCreatedTheBlockIsIgnored()
    {
        $slots = array(
            'logo' => array('repeated' => 'page'),
        );
        $this->setUpTemplateSlots($slots);
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        
        $this->blockManagerFactory->expects($this->once())
            ->method('createBlockManager')
            ->will($this->returnValue(null));

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block logo %}" . PHP_EOL;
        $section .= "  {% if(slots.logo is not defined) %}" . PHP_EOL;
        $section .= "" . PHP_EOL;
        $section .= "<!-- BEGIN LOGO BLOCK -->" . PHP_EOL;
        $section .= "" . PHP_EOL;
        $section .= "<!-- END LOGO BLOCK -->" . PHP_EOL;
        $section .= "  {% endif %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= $this->addSomeLove();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testContentsSectionWithOneBlockHasBeenCreated()
    {
        $slots = array(
            'logo' => array('repeated' => 'page'),
        );
        $this->setUpTemplateSlots($slots);
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory($deployContent = 'Formatted content for deploying', 1);

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
        $section .= $this->addSomeLove();
        
        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testContentsSectionWithOneBlockAndImagesReplaceHaveBeenCreated()
    {
        $slots = array(
            'logo' => array('repeated' => 'page'),
        );
        $this->setUpTemplateSlots($slots);
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory('<img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/download.png">', 1);

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
        $section .= $this->addSomeLove();

        $this->urlManager->expects($this->any())
            ->method('getProductionRoute')
            ->will($this->returnValue(null));

        $imagesPath = array('backendPath' => "/bundles/alphalemoncms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");
        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer, $imagesPath);
        $twigTemplateWriter->generateTemplate();
        
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testContentsSectionWithOneBlockAndLinkNotReplaceBecauseNotRecognizedAsInternalRouteHaveBeenCreated()
    {
        $slots = array(
            'logo' => array('repeated' => 'page'),
        );
        $this->setUpTemplateSlots($slots);
        $this->urlManager->expects($this->any())
            ->method('getProductionRoute')
            ->will($this->returnValue(null));

        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory('<ul><li><a href="my-awesome-page">Fancy page</a></li></ul>', 1);

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
        $section .= $this->addSomeLove();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testContentsSectionWithOneBlockAndLinkReplaceHaveBeenCreated()
    {
        $slots = array(
            'logo' => array('repeated' => 'page'),
        );
        $this->setUpTemplateSlots($slots);
        $this->urlManager->expects($this->any())
            ->method('getProductionRoute')
            ->will($this->returnValue('_en_index'));

        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory('<ul><li><a href="my-awesome-page">Fancy page</a></li></ul>', 1);

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
        $section .= $this->addSomeLove();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testAnContentSectionIsWrittenWhenAnyBlockExistsForThatslotButThatSlotExistsInTheTemplate()
    {
        $slot = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot')
                ->disableOriginalConstructor()
                ->getMock();
        $slot->expects($this->once())
            ->method('getSlotName')
            ->will($this->returnValue('fake_slot'));
        
        $this->templateSlots->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue(array($slot)));

        $this->setUpTemplateSlots();
        $this->urlManager->expects($this->any())
            ->method('getProductionRoute')
            ->will($this->returnValue('_en_index'));

        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks(array());
        $this->setUpBlockManagerFactory('fake', 0);

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block fake_slot %}" . PHP_EOL;
        $section .= "  {% if(slots.fake_slot is not defined) %}" . PHP_EOL;
        $section .= "" . PHP_EOL;
        $section .= "<!-- BEGIN FAKE_SLOT BLOCK -->" . PHP_EOL;
        $section .= "" . PHP_EOL;
        $section .= "<!-- END FAKE_SLOT BLOCK -->" . PHP_EOL;
        $section .= "  {% endif %}" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        $section .= $this->addSomeLove();

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testContentsSectionWithMoreBlocksHaveBeenCreated()
    {
        $blocks = array(
            "logo" =>
                array(
                    $this->setUpBlock('logo')
                ),
            "nav-menu" =>
                array(
                    $this->setUpBlock('nav-menu'),
                    $this->setUpBlock('nav-menu')
                )
            );
        
        $slots = array(
            'logo' => array('repeated' => 'page'),
            'nav-menu' => array('repeated' => 'page'),
        );

        $this->setUpTemplateSlots($slots);
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks($blocks);

        $blockManager1 = $this->setUpBlockManager(null);
        $blockManager2 = $this->setUpBlockManager(null);
        $blockManager3 = $this->setUpBlockManager(null);
        
        $this->blockManagerFactory->expects($this->at(0))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager1));
        $this->blockManagerFactory->expects($this->at(1))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager2));
        $this->blockManagerFactory->expects($this->at(2))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager3));
        
        $this->viewRenderer
                ->expects($this->at(0))
                ->method('render')
                ->will($this->returnValue('<img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/download.png">'))
            ;
        $this->viewRenderer
                ->expects($this->at(1))
                ->method('render')
                ->will($this->returnValue('<div>A new content</div>'))
            ;
        $this->viewRenderer
                ->expects($this->at(2))
                ->method('render')
                ->will($this->returnValue('<div>Some other text <img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/image.png"></div>'))
            ;

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
        $section .= $this->addSomeLove();

        $imagesPath = array('backendPath' => "/bundles/alphalemoncms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");

        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer, $imagesPath);
        $twigTemplateWriter->generateTemplate();
        
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testGenerateFullTemplate()
    {
        $blocks = array(
            "logo" =>
                array(
                    $this->setUpBlock('logo')
                ),
            "nav-menu" =>
                array(
                    $this->setUpBlock('nav-menu'),
                    $this->setUpBlock('nav-menu'),
                    $this->setUpBlock('nav-menu')
                )
        );
        
        $slots = array(
            'logo' => array('repeated' => 'page'),
            'nav-menu' => array('repeated' => 'page'),
        );

        $this->urlManager->expects($this->any())
            ->method('getProductionRoute')
            ->will($this->returnValue('_en_index'));

        $this->setUpTemplateSlots($slots);
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks($blocks);
        
        $blockManager1 = $this->setUpBlockManager(null);
        $blockManager2 = $this->setUpBlockManager(null);
        $blockManager3 = $this->setUpBlockManager(null);
        $blockManager4 = $this->setUpBlockManager(null);
        
        $this->blockManagerFactory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->onConsecutiveCalls($blockManager1, $blockManager2, $blockManager3, $blockManager4));
        
        $this->blockManagerFactory->expects($this->at(0))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager1));
        $this->blockManagerFactory->expects($this->at(1))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager2));
        $this->blockManagerFactory->expects($this->at(2))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager3));
        $this->blockManagerFactory->expects($this->at(3))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager4));
        
        $this->viewRenderer
                ->expects($this->at(0))
                ->method('render')
                ->will($this->returnValue('<img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/download.png">'))
            ;
        $this->viewRenderer
                ->expects($this->at(1))
                ->method('render')
                ->will($this->returnValue('<div>A new content</div>'))
            ;
        $this->viewRenderer
                ->expects($this->at(2))
                ->method('render')
                ->will($this->returnValue('<div>Some other text <img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/image.png"></div>'))
            ;
        $this->viewRenderer
                ->expects($this->at(3))
                ->method('render')
                ->will($this->returnValue('<div>Lorem ipsum <ul><li><a href="my-awesome-page">Fancy page</a></li></ul></div>'))
            ;

        $section = "{% extends 'AcmeWebsiteBundle:AlphaLemon:en/base/home.html.twig' %}" . PHP_EOL;
        $section .= "\n{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block title %}A title{% endblock %}" . PHP_EOL;
        $section .= "{% block description %}A description{% endblock %}" . PHP_EOL;
        $section .= "{% block keywords %}some,keywords{% endblock %}" . PHP_EOL;
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
        $section .= $this->addSomeLove();

        $imagesPath = array('backendPath' => "/bundles/alphalemoncms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");
        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer, $imagesPath);
        $twigTemplateWriter->generateTemplate();
        
        $this->assertEquals($section, $twigTemplateWriter->getTwigTemplate());
    }

    public function testWriteFile()
    {
        $this->setUpTemplateSlots();
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $this->setUpBlockManagerFactory();

        $this->assertFalse($this->root->hasChild('en'));
        $twigTemplateWriter = new AlTwigTemplateWriterPages($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->deployBundle, $this->templatesFolder, $this->viewRenderer);
        $twigTemplateWriter->generateTemplate();
        
        $twigTemplateWriter->writeTemplate(vfsStream::url('root'));
        $this->assertTrue($this->root->hasChild('en'));
        $this->assertTrue($this->root->getChild('en')->hasChild('index.html.twig'));
    }
    
    protected function setUpPageBlocks(array $blocks = null, $yuiEnabled = true)
    {
        parent::setUpPageBlocks($blocks, $yuiEnabled);
        
        $this->container->expects($this->once())
            ->method('getParameter')
            ->with('alpha_lemon_cms.enable_yui_compressor')
            ->will($this->returnValue($yuiEnabled));
    }
}