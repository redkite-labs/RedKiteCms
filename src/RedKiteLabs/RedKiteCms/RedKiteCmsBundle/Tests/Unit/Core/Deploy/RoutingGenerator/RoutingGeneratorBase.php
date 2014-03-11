<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Deploy\RoutingGenerator;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * RoutingGeneratorTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class RoutingGeneratorBase extends TestCase
{
    protected $expectedRouting;
    
    abstract protected function getRoutingGenerator($pageTreeCollection);
    abstract protected function getExpectedFilename();
    abstract protected function getPrefix();
    
    /**
     * @dataProvider pageTreeCollectionProvider
     */
    public function testRoutingGenerator($pages, $seoAttributes, $seoHomeAttributes = null)
    {     
        $pageTreeCollection = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\AlPageTreeCollection')
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $pageTreeCollection->expects($this->once())
            ->method('getPages')
            ->will($this->returnValue($pages))
        ;
        
        $root = vfsStream::setup('root');
        $routingPath = vfsStream::url('root');
        $routingGenerator = $this->getRoutingGenerator($pageTreeCollection);
        $routingGenerator->generateRouting('AcmeWebSiteBundle', 'WebSite')->writeRouting($routingPath);
        $this->assertEquals($this->buildExpectedRoutes($seoAttributes, $seoHomeAttributes), file_get_contents($routingPath . "/" . $this->getExpectedFilename()));
    }
    
    protected function buildExpectedRoutes($seo, $seoHomeAttributes)
    {
        $prefix = $this->getPrefix();
        $action = ($prefix == "") ? "show" : "stage";
        
        $routes = array();
        foreach($seo as $seoAttributes) {
            $language = $seoAttributes["language"];
            $page = $seoAttributes["page"];
            $permalink = ( ! $seoAttributes["homepage"]) ? $seoAttributes["permalink"] : "" ;
            $siteRouting = "# Route << %s >> generated for language << %s >> and page << %s >>\n";
            $siteRouting .= "%s_%s_%s:\n";
            $siteRouting .= "  pattern: /%s\n";
            $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:%s, _locale: %s, page: %s }\n";

            $routes[] = sprintf($siteRouting, $permalink, $language, $page, $prefix, $language, str_replace('-', '_', $page), $permalink, $action, $language, $page);
        }
        
        if (null === $seoHomeAttributes) {
            $seoHomeAttributes = array(
                "language" => "en",
                "page" => "index",
            );
        }
        
        $siteRouting = "# Route <<  >> generated for language << %1\$s >> and page << %2\$s >>\n";
        $siteRouting .= "%3\$s_home:\n";
        $siteRouting .= "  pattern: /\n";
        $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:%4\$s, _locale: %1\$s, page: %2\$s }";
        
        $routes[] = sprintf($siteRouting, $seoHomeAttributes["language"], $seoHomeAttributes["page"], $prefix, $action);
        
        return (implode("\n", $routes));
    }
    
    public function pageTreeCollectionProvider()
    {
        return array(
            array(
                array(
                    $this->createPageTree($this->createLanguage('en', 1), $this->createPage('index', 1), $this->createSeo('an-awesome-permalink')),
                ),
                array(
                    $this->createSeoAttributes('an-awesome-permalink', 'en', 'index', true),
                ),
            ),
            array(
                array(
                    $this->createPageTree($this->createLanguage('en', 1), $this->createPage('index', 1), $this->createSeo('an-awesome-permalink')),                    
                    $this->createPageTree($this->createLanguage('en'), $this->createPage('internal'), $this->createSeo('another-awesome-permalink')),
                ),
                array(
                    $this->createSeoAttributes('an-awesome-permalink', 'en', 'index', true),
                    $this->createSeoAttributes('another-awesome-permalink', 'en', 'internal', false),
                ),
            ),
            array(
                array(
                    $this->createPageTree($this->createLanguage('en', 1), $this->createPage('index', 1), $this->createSeo('an-awesome-permalink')),                
                    $this->createPageTree($this->createLanguage('en'), $this->createPage('internal'), $this->createSeo('another-awesome-permalink')),
                    $this->createPageTree($this->createLanguage('it'), $this->createPage('index'), $this->createSeo('it-an-awesome-permalink')),                  
                    $this->createPageTree($this->createLanguage('it'), $this->createPage('internal'), $this->createSeo('it-another-awesome-permalink')),
                ),
                array(
                    $this->createSeoAttributes('an-awesome-permalink', 'en', 'index', true),
                    $this->createSeoAttributes('another-awesome-permalink', 'en', 'internal', false),
                    $this->createSeoAttributes('it-an-awesome-permalink', 'it', 'index', false),
                    $this->createSeoAttributes('it-another-awesome-permalink', 'it', 'internal', false),
                ),
            ),
            array(
                array(
                    $this->createPageTree($this->createLanguage('en'), $this->createPage('index'), $this->createSeo('an-awesome-permalink')),                
                    $this->createPageTree($this->createLanguage('en', 0), $this->createPage('internal', 0), $this->createSeo('another-awesome-permalink')),
                    $this->createPageTree($this->createLanguage('it'), $this->createPage('index'), $this->createSeo('it-an-awesome-permalink')),                
                    $this->createPageTree($this->createLanguage('it', 1), $this->createPage('internal', 1), $this->createSeo('it-another-awesome-permalink')),
                ),
                array(
                    $this->createSeoAttributes('an-awesome-permalink', 'en', 'index', false),
                    $this->createSeoAttributes('another-awesome-permalink', 'en', 'internal', false),
                    $this->createSeoAttributes('it-an-awesome-permalink', 'it', 'index', false),
                    $this->createSeoAttributes('it-another-awesome-permalink', 'it', 'internal', true),
                ),
                array(
                    "language" => "it",
                    "page" => "internal",
                ),
            ),
        );
    }

    protected function createPageTree($language, $page, $seo)
    {
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getAlLanguage', 'getAlPage', 'getAlSeo'))
                                ->getMock();
        
        $pageTree->expects($this->once())
            ->method('getAlLanguage')
            ->will($this->returnValue($language))
        ;
        
        $pageTree->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($page))
        ;
        
        $pageTree->expects($this->once())
            ->method('getAlSeo')
            ->will($this->returnValue($seo))
        ;
        
        return $pageTree;
    }
    
    protected function createPage($pageName, $isHome = 0)
    {
        $page = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlPage');

        $page->expects($this->once())
            ->method('getPageName')
            ->will($this->returnValue($pageName))
        ;
        
        $page->expects($this->once())
            ->method('getIsHome')
            ->will($this->returnValue($isHome))
        ;
        
        return $page;
    }
    
    protected function createLanguage($languageName, $isMain = 0)
    {
        $language = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage');

        $language->expects($this->once())
            ->method('getLanguageName')
            ->will($this->returnValue($languageName))
        ;
        
        $language->expects($this->once())
            ->method('getMainLanguage')
            ->will($this->returnValue($isMain))
        ;

        return $language;
    }
    
    protected function createSeo($permalink)
    {
        $seo = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlSeo');

        $seo->expects($this->once())
            ->method('getPermalink')
            ->will($this->returnValue($permalink))
        ;

        return $seo;
    }
    
    protected function createSeoAttributes($permalink, $language, $page, $homepage)
    {
        return array(
            'permalink' => $permalink,
            'language' => $language,
            'page' => $page,
            'homepage' => $homepage,
        );
    }
}