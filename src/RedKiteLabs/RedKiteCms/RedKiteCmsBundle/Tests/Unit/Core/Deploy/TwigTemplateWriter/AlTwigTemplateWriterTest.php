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
    private $router;

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
        
        $this->router = $this->getMock('\Symfony\Component\Routing\RouterInterface');

        $this->root = vfsStream::setup('root');
    }

    public function testExtendsDirectiveHasBeenCreatedForTheGivenTemplate()
    {
        $this->setUpMetatagsAndAssets(null, null, null, array(), array(), '', '');
        $this->setUpPageBlocks();
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);

        $this->assertEquals("{% extends 'FakeTheme:Theme:Home.html.twig' %}\n", $twigTemplateWriter->getTemplateSection());
    }

    public function testJustTheMetaTagsTitleSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets("A title", null, null, array(), array(), '', '');
        $this->setUpPageBlocks();
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);

        $section = "\n{#--------------  METATAGS SECTION  --------------#}\n";
        $section .= "{% block title %}\n";
        $section .= "A title\n";
        $section .= "{% endblock %}\n\n";

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testJustTheMetaDescriptionSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets(null, "A description", null, array(), array(), '', '');
        $this->setUpPageBlocks();
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);

        $section = "\n{#--------------  METATAGS SECTION  --------------#}\n";
        $section .= "{% block description %}\n";
        $section .= "A description\n";
        $section .= "{% endblock %}\n\n";

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testJustTheKeywordsSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets(null, null, "some,keywords", array(), array(), '', '');
        $this->setUpPageBlocks();
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);

        $section = "\n{#--------------  METATAGS SECTION  --------------#}\n";
        $section .= "{% block keywords %}\n";
        $section .= "some,keywords\n";
        $section .= "{% endblock %}\n\n";

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testAllMetatagsSectionsAreCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array(), '', '');
        $this->setUpPageBlocks();
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);

        $section = "\n{#--------------  METATAGS SECTION  --------------#}\n";
        $section .= "{% block title %}\n";
        $section .= "A title\n";
        $section .= "{% endblock %}\n\n";
        $section .= "{% block description %}\n";
        $section .= "A description\n";
        $section .= "{% endblock %}\n\n";
        $section .= "{% block keywords %}\n";
        $section .= "some,keywords\n";
        $section .= "{% endblock %}\n\n";

        $this->assertEquals($section, $twigTemplateWriter->getMetaTagsSection());
    }

    public function testJustTheExternalStylesheetsSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array(), '', '');
        $this->setUpPageBlocks();
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);

        $section = "\n{#--------------  ASSETS SECTION  --------------#}\n";
        $section .= "{% block external_stylesheets %}\n";
        $section .= "  {% stylesheets style1.css style2.css filter=\"?yui_css,cssrewrite\" %}\n";
        $section .= "    <link href=\"{{ asset_url }}\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n";
        $section .= "  {% endstylesheets %}\n";
        $section .= "{% endblock %}\n\n";

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testJustTheExternalJavascriptsSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array('javascript1.js', 'javascript2.js'), '', '');
        $this->setUpPageBlocks();
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);

        $section = "\n{#--------------  ASSETS SECTION  --------------#}\n";
        $section .= "{% block external_javascripts %}\n";
        $section .= "  {% javascripts javascript1.js javascript2.js filter=\"?yui_js\" %}\n";
        $section .= "    <script src=\"{{ asset_url }}\"></script>\n";
        $section .= "  {% endjavascripts %}\n";
        $section .= "{% endblock %}\n\n";

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testJustTheInternalStylesheetsSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array(), 'some css code', '');
        $this->setUpPageBlocks();
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);

        $section = "\n{#--------------  ASSETS SECTION  --------------#}\n";
        $section .= "{% block internal_header_stylesheets %}\n";
        $section .= "<style>some css code</style>\n";
        $section .= "{% endblock %}\n\n";

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }


    public function testJustTheInternalJavascriptsSectionIsCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array(), array(), '', 'some js code');
        $this->setUpPageBlocks();
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);

        $section = "\n{#--------------  ASSETS SECTION  --------------#}\n";
        $section .= "{% block internal_header_javascripts %}\n";
        $section .= "<script>$(document).ready(function(){some js code});</script>\n";
        $section .= "{% endblock %}\n\n";

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testAllAssetsSectionsAreCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);

        $section = "\n{#--------------  ASSETS SECTION  --------------#}\n";
        $section .= "{% block external_stylesheets %}\n";
        $section .= "  {% stylesheets style1.css style2.css filter=\"?yui_css,cssrewrite\" %}\n";
        $section .= "    <link href=\"{{ asset_url }}\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n";
        $section .= "  {% endstylesheets %}\n";
        $section .= "{% endblock %}\n\n";
        $section .= "{% block external_javascripts %}\n";
        $section .= "  {% javascripts javascript1.js javascript2.js filter=\"?yui_js\" %}\n";
        $section .= "    <script src=\"{{ asset_url }}\"></script>\n";
        $section .= "  {% endjavascripts %}\n";
        $section .= "{% endblock %}\n\n";
        $section .= "{% block internal_header_stylesheets %}\n";
        $section .= "<style>some css code</style>\n";
        $section .= "{% endblock %}\n\n";
        $section .= "{% block internal_header_javascripts %}\n";
        $section .= "<script>$(document).ready(function(){some js code});</script>\n";
        $section .= "{% endblock %}\n\n";

        $this->assertEquals($section, $twigTemplateWriter->getAssetsSection());
    }

    public function testContentsSectionWithOneBlockHaveBeenCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}\n";
        $section .= "{% block logo %}\n";
        $section .= "  {% if(slots.logo is not defined) %}\n";
        $section .= "    my content\n";
        $section .= "  {% else %}\n";
        $section .= "    {{ parent() }}\n";
        $section .= "  {% endif %}\n";
        $section .= "{% endblock %}\n\n";

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testContentsSectionWithOneBlockAndImagesReplaceHaveBeenCreated()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks(array("logo" => array($this->setUpBlock('<img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/download.png">'))));

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}\n";
        $section .= "{% block logo %}\n";
        $section .= "  {% if(slots.logo is not defined) %}\n";
        $section .= "    <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/download.png\">\n";
        $section .= "  {% else %}\n";
        $section .= "    {{ parent() }}\n";
        $section .= "  {% endif %}\n";
        $section .= "{% endblock %}\n\n";


        $imagesPath = array('backendPath' => "/bundles/alphalemoncms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router, $imagesPath);
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }
    
    
    public function testContentsSectionWithOneBlockAndLinkNotReplaceBecauseNotRecognizedAsInternalRouteHaveBeenCreated()
    {
        $this->router->expects($this->once())
            ->method('match')
            ->will($this->throwException(new \Symfony\Component\Routing\Exception\ResourceNotFoundException()));
        
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks(array("logo" => array($this->setUpBlock('<ul><li><a href="my-awesome-page">Fancy page</a></li></ul>'))));

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}\n";
        $section .= "{% block logo %}\n";
        $section .= "  {% if(slots.logo is not defined) %}\n";
        $section .= "    <ul><li><a href=\"my-awesome-page\">Fancy page</a></li></ul>\n";
        $section .= "  {% else %}\n";
        $section .= "    {{ parent() }}\n";
        $section .= "  {% endif %}\n";
        $section .= "{% endblock %}\n\n";

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }
    
    public function testContentsSectionWithOneBlockAndLinkReplaceHaveBeenCreated()
    {
        $this->router->expects($this->once())
            ->method('match')
            ->will($this->returnValue(array('_en_index')));
        
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks(array("logo" => array($this->setUpBlock('<ul><li><a href="my-awesome-page">Fancy page</a></li></ul>'))));

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}\n";
        $section .= "{% block logo %}\n";
        $section .= "  {% if(slots.logo is not defined) %}\n";
        $section .= "    <ul><li><a href=\"{{ path('_en_index') }}\">Fancy page</a></li></ul>\n";
        $section .= "  {% else %}\n";
        $section .= "    {{ parent() }}\n";
        $section .= "  {% endif %}\n";
        $section .= "{% endblock %}\n\n";

        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }
    
    public function testContentsSectionWithMoreBlocksHaveBeenCreated()
    {
        $blocks = array(
            "logo" =>
                array(
                    $this->setUpBlock('<img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/download.png">')
                ),
            "nav-menu" =>
                array(
                    $this->setUpBlock('<div>A new content</div>'),
                    $this->setUpBlock('<div>Some other text <img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/image.png"></div>')
                )
            );

        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks($blocks);

        $section = "\n{#--------------  CONTENTS SECTION  --------------#}\n";
        $section .= "{% block logo %}\n";
        $section .= "  {% if(slots.logo is not defined) %}\n";
        $section .= "    <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/download.png\">\n";
        $section .= "  {% else %}\n";
        $section .= "    {{ parent() }}\n";
        $section .= "  {% endif %}\n";
        $section .= "{% endblock %}\n";
        $section .= "\n";
        $section .= "{% block nav-menu %}\n";
        $section .= "  {% if(slots.nav-menu is not defined) %}\n";
        $section .= "    <div>A new content</div>\n";
        $section .= "    \n";
        $section .= "    <div>Some other text <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/image.png\"></div>\n";
        $section .= "  {% else %}\n";
        $section .= "    {{ parent() }}\n";
        $section .= "  {% endif %}\n";
        $section .= "{% endblock %}\n\n";

        $imagesPath = array('backendPath' => "/bundles/alphalemoncms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");


        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router, $imagesPath);
        $this->assertEquals($section, $twigTemplateWriter->getContentsSection());
    }

    public function testGenerateFullTemplate()
    {
        $blocks = array(
            "logo" =>
                array(
                    $this->setUpBlock('<img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/download.png">')
                ),
            "nav-menu" =>
                array(
                    $this->setUpBlock('<div>A new content</div>'),
                    $this->setUpBlock('<div>Some other text <img width="381" height="87" title="Download" alt="download.png" src="/bundles/alphalemoncms/uploads/assets/media/image.png"></div>'),
                    $this->setUpBlock('<div>Lorem ipsum <ul><li><a href="my-awesome-page">Fancy page</a></li></ul></div>')
                )
            );
        
        $this->router->expects($this->once())
            ->method('match')
            ->will($this->returnValue(array('_en_index')));

        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks($blocks);

        $section = "{% extends 'FakeTheme:Theme:Home.html.twig' %}\n";
        $section .= "\n{#--------------  METATAGS SECTION  --------------#}\n";
        $section .= "{% block title %}\n";
        $section .= "A title\n";
        $section .= "{% endblock %}\n\n";
        $section .= "{% block description %}\n";
        $section .= "A description\n";
        $section .= "{% endblock %}\n\n";
        $section .= "{% block keywords %}\n";
        $section .= "some,keywords\n";
        $section .= "{% endblock %}\n\n";
        $section .= "\n{#--------------  ASSETS SECTION  --------------#}\n";
        $section .= "{% block external_stylesheets %}\n";
        $section .= "  {% stylesheets style1.css style2.css filter=\"?yui_css,cssrewrite\" %}\n";
        $section .= "    <link href=\"{{ asset_url }}\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n";
        $section .= "  {% endstylesheets %}\n";
        $section .= "{% endblock %}\n\n";
        $section .= "{% block external_javascripts %}\n";
        $section .= "  {% javascripts javascript1.js javascript2.js filter=\"?yui_js\" %}\n";
        $section .= "    <script src=\"{{ asset_url }}\"></script>\n";
        $section .= "  {% endjavascripts %}\n";
        $section .= "{% endblock %}\n\n";
        $section .= "{% block internal_header_stylesheets %}\n";
        $section .= "<style>some css code</style>\n";
        $section .= "{% endblock %}\n\n";
        $section .= "{% block internal_header_javascripts %}\n";
        $section .= "<script>$(document).ready(function(){some js code});</script>\n";
        $section .= "{% endblock %}\n\n";
        $section .= "\n{#--------------  CONTENTS SECTION  --------------#}\n";
        $section .= "{% block logo %}\n";
        $section .= "  {% if(slots.logo is not defined) %}\n";
        $section .= "    <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/download.png\">\n";
        $section .= "  {% else %}\n";
        $section .= "    {{ parent() }}\n";
        $section .= "  {% endif %}\n";
        $section .= "{% endblock %}\n";
        $section .= "\n";
        $section .= "{% block nav-menu %}\n";
        $section .= "  {% if(slots.nav-menu is not defined) %}\n";
        $section .= "    <div>A new content</div>\n";
        $section .= "    \n";
        $section .= "    <div>Some other text <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/image.png\"></div>\n";
        $section .= "    \n";
        $section .= "    <div>Lorem ipsum <ul><li><a href=\"{{ path('_en_index') }}\">Fancy page</a></li></ul></div>\n";
        $section .= "  {% else %}\n";
        $section .= "    {{ parent() }}\n";
        $section .= "  {% endif %}\n";
        $section .= "{% endblock %}\n\n";

        $imagesPath = array('backendPath' => "/bundles/alphalemoncms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router, $imagesPath);
        $this->assertEquals($section, $twigTemplateWriter->getTwigTemplate());
    }

    public function testWriteFile()
    {
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks();

        $this->assertFalse($this->root->hasChild('en'));
        $twigTemplateWriter = new AlTwigTemplateWriter($this->pageTree, $this->router);
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


    private function setUpBlock($content)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
            ->method('getHtmlContent')
            ->will($this->returnValue($content));

        return $block;
    }
}
