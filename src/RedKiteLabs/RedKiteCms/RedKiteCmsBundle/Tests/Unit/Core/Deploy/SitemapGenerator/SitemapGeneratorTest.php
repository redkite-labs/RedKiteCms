<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Deploy\SitemapGenerator;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\SitemapGenerator\SitemapGenerator;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * SitemapGeneratorTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class SitemapGeneratorTest extends TestCase
{
    
    /**
     * @dataProvider pageTreeCollectionProvider
     */
    public function testSitemapGenerator($pages, $expectedUrls)
    {     
        $pageTreeCollection = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\PageTreeCollection')
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $pageTreeCollection->expects($this->once())
            ->method('getPages')
            ->will($this->returnValue($pages))
        ;
        
        $templating = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $templating->expects($this->once())
            ->method('render')
            ->with('RedKiteCmsBundle:Sitemap:sitemap.html.twig', array('urls' => $expectedUrls))
        ;
        
        $root = vfsStream::setup('root');
        
        $sitemapGenerator = new SitemapGenerator($pageTreeCollection, $templating);
        $sitemapGenerator->writeSiteMap(vfsStream::url('root'), 'http://example.com/');
        $this->assertFileExists(vfsStream::url('root/sitemap.xml'));
    }
    
    public function pageTreeCollectionProvider()
    {
        return array(
            array(
                array(
                    $this->createPageTree($this->createLanguage(), $this->createPage(), $this->createSeo('an-awesome-permalink', 'monthly', 1)),
                ),
                array(
                    array(
                        'href' => 'http://example.com/an-awesome-permalink',
                        'frequency' => 'monthly', 
                        'priority' => 1
                    ),
                ),
            ),
            array(
                array(
                    $this->createPageTree($this->createLanguage(), $this->createPage(), $this->createSeo('an-awesome-page', 'monthly', 1)),                    
                    $this->createPageTree($this->createLanguage(), $this->createPage(), $this->createSeo('another-awesome-page', 'daily', 0.5)),
                ),
                array(
                    array(
                        'href' => 'http://example.com/an-awesome-page',
                        'frequency' => 'monthly', 
                        'priority' => 1,
                    ),
                    array(
                        'href' => 'http://example.com/another-awesome-page',
                        'frequency' => 'daily', 
                        'priority' => 0.5,
                    ),
                ),
            ),
            array(
                array(
                    $this->createPageTree($this->createLanguage(), $this->createPage(), $this->createSeo('an-awesome-page', 'monthly', 1)),                    
                    $this->createPageTree($this->createLanguage(), $this->createPage(0, false), $this->createSeo('a-page-not-published', 'daily', 0.5)),
                ),
                array(
                    array(
                        'href' => 'http://example.com/an-awesome-page',
                        'frequency' => 'monthly', 
                        'priority' => 1,
                    ),
                ),
            ),
            array(
                array(
                    $this->createPageTree($this->createLanguage(), $this->createPage(), $this->createSeo('an-awesome-page', 'monthly', 1)),                    
                    $this->createPageTree($this->createLanguage(), $this->createPage(), $this->createSeo('another-awesome-page', 'daily', 0.5)),                   
                    $this->createPageTree($this->createLanguage(), $this->createPage(0, false), $this->createSeo('a-page-not-published', 'daily', 0.5)),
                ),
                array(
                    array(
                        'href' => 'http://example.com/an-awesome-page',
                        'frequency' => 'monthly', 
                        'priority' => 1,
                    ),
                    array(
                        'href' => 'http://example.com/another-awesome-page',
                        'frequency' => 'daily', 
                        'priority' => 0.5,
                    ),
                ),
            ),
            array(
                array(
                    $this->createPageTree($this->createLanguage(), $this->createPage(), $this->createSeo('an-awesome-page', 'monthly', 1)),                    
                    $this->createPageTree($this->createLanguage(), $this->createPage(), $this->createSeo('another-awesome-page', 'daily', 0.5)),                   
                    $this->createPageTree($this->createLanguage(1), $this->createPage(1), $this->createSeo(null, 'daily', 0.5)),
                ),
                array(
                    array(
                        'href' => 'http://example.com/an-awesome-page',
                        'frequency' => 'monthly', 
                        'priority' => 1,
                    ),
                    array(
                        'href' => 'http://example.com/another-awesome-page',
                        'frequency' => 'daily', 
                        'priority' => 0.5,
                    ),
                    array(
                        'href' => 'http://example.com/',
                        'frequency' => 'daily', 
                        'priority' => 0.5,
                    ),
                ),
            ),
        );
    }

    protected function createPageTree($language, $page, $seo)
    {
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getLanguage', 'getPage', 'getSeo'))
                                ->getMock();
        
        $pageTree->expects($this->once())
            ->method('getLanguage')
            ->will($this->returnValue($language))
        ;
        
        $pageTree->expects($this->once())
            ->method('getPage')
            ->will($this->returnValue($page))
        ;
        
        $pageTree->expects($this->once())
            ->method('getSeo')
            ->will($this->returnValue($seo))
        ;
        
        return $pageTree;
    }
    
    protected function createPage($isHome = 0, $isPublished = true)
    {
        $page = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page');

        $page->expects($this->once())
            ->method('getIsHome')
            ->will($this->returnValue($isHome))
        ;
        
        $page->expects($this->once())
            ->method('getIsPublished')
            ->will($this->returnValue($isPublished))
        ;
        
        return $page;
    }
    
    protected function createLanguage($isMain = 0)
    {
        $language = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Language');

        $language->expects($this->once())
            ->method('getMainLanguage')
            ->will($this->returnValue($isMain))
        ;

        return $language;
    }
    
    protected function createSeo($permalink, $frequency, $priority)
    {
        $seo = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Seo');

        if (null === $permalink) {
            $seo->expects($this->never())
                ->method('getPermalink')
            ;
        } else  {
            $seo->expects($this->once())
                ->method('getPermalink')
                ->will($this->returnValue($permalink))
            ;
        }
        
        $seo->expects($this->once())
            ->method('getSitemapChangefreq')
            ->will($this->returnValue($frequency))
        ;
        
        $seo->expects($this->once())
            ->method('getSitemapPriority')
            ->will($this->returnValue($priority))
        ;

        return $seo;
    }
}