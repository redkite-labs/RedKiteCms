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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Deploy;

use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlDeployer;
use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme;
use org\bovigo\vfs\vfsStream;

class AlDeployerTester extends AlDeployer
{
    public function save(AlPageTree $pageTree, AlTheme $theme, array $options)
    {
        return $pageTree->fakeSave();
    }
}

/**
 * AlDeployerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlDeployerTest extends TestCase
{
    protected $dispatcher;
    protected $routingGenerator;
    protected $sitemapGenerator;
    protected $deployBundlePath;


    protected function setUp()
    {
        parent::setUp();
        
        $this->routingGenerator = $this->getMock("RedKiteLabs\RedKiteCmsBundle\Core\Deploy\RoutingGenerator\RoutingGeneratorInterface");
        $this->sitemapGenerator = $this->getMock("RedKiteLabs\RedKiteCmsBundle\Core\Deploy\SitemapGenerator\SitemapGeneratorInterface");
        $this->pageTreeCollection = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\AlPageTreeCollection')
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $this->theme = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme')
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $this->sitemapGenerator = $this->getMock("RedKiteLabs\RedKiteCmsBundle\Core\Deploy\SitemapGenerator\SitemapGeneratorInterface");
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $folders = array('app' => array(),
                         'web' => array('uploads'
                                    => array('assets'
                                        => array('media' => array('image1.png' => '', 'image2.png' => ''),
                                                'js' => array('code.js' => ''),
                                                'css' => array('style.css' => ''),
                                                )
                                            )
                                        ),
                         'AcmeWebSiteBundle' => array('Resources' => array()),
                         'RedKiteCmsBundle' => array(),
                        );
        $this->root = vfsStream::setup('root', null, $folders);
        //print_r(vfsStream::inspect(new \org\bovigo\vfs\visitor\vfsStreamStructureVisitor())->getStructure());exit;
        
        
    }
    
    /**
     * @dataProvider deployProvider
     */
    public function testDeploy($pages, $basePages, $result, $hasSitemapGenerator = false, $hasDispatcher = false)
    {
        $deployBundlePath = vfsStream::url('root\AcmeWebSiteBundle');
        $options = array(
            "deployBundle" => 'AcmeWebSiteBundle',
            "configDir" => $deployBundlePath . '/Resources/config',
            "assetsDir" => $deployBundlePath  . '/Resources/public',
            "viewsDir" => $deployBundlePath  . '/Resources/views',
            "deployDir" => $deployBundlePath  . '/Resources/views/RedKiteCms',
            "uploadAssetsFullPath" => vfsStream::url('root\web\uploads\assets'),
            "uploadAssetsAbsolutePath" => '/uploads/assets',
            "deployController" => "WebSite",
            "webFolderPath" => vfsStream::url('root\web'),
            "websiteUrl" => "http://example.com",
        );
        
        $sitemapGenerator = null;
        if ($hasSitemapGenerator) {
            $sitemapGenerator = $this->sitemapGenerator;
        }
        
        $dispatcher = null;
        if ($hasDispatcher) {
            $dispatcher = $this->dispatcher;
        }
        
        $this->initPageTreeCollection($pages, $basePages);
        $this->initRoutingGenerator($result, $options);
        $this->initSitemapGenerator($sitemapGenerator, $options);
        $this->initDispatcher($dispatcher);
        
        $this->verifyFoldersBeforeDeploy();
        
        $this->deployer = new AlDeployerTester($this->routingGenerator, $sitemapGenerator, $dispatcher);
        $this->assertEquals($result, $this->deployer->deploy($this->pageTreeCollection, $this->theme, $options));
        
        $this->verifyFoldersAfterDeploy('RedKiteCms');        
        $this->assertsHaveBeenCopied($result);
    }
    
    public function deployProvider()
    {
        return array(
            array(
                array(
                    $this->initPageTree(false),
                ),
                null,
                false,
            ),
            array(
                array(
                    $this->initPageTree(),
                    $this->initPageTree(false),
                ),
                null,
                false,
            ),
            array(
                array(
                    $this->initPageTree(),
                ),
                array(
                    $this->initPageTree(false),
                ),
                false,
            ),
            array(
                array(
                    $this->initPageTree(),
                ),
                array(
                    $this->initPageTree(),
                    $this->initPageTree(false),
                ),
                false,
            ),
            array(
                array(
                    $this->initPageTree(),
                ),
                array(
                    $this->initPageTree(),
                ),
                true,
                true,                
                true,
            ),
            array(
                array(
                    $this->initPageTree(),
                    $this->initPageTree(),
                ),
                array(
                    $this->initPageTree(),
                    $this->initPageTree(),
                ),
                true,
                true,      
                true,
            ),
        );
    }
    
    protected function initPageTreeCollection($pages, $basePages)
    {
        $this->pageTreeCollection->expects($this->once())
            ->method('fill')
        ;
        
        $this->pageTreeCollection->expects($this->once())
            ->method('getPages')
            ->will($this->returnValue($pages))
        ;
        
        if (null !== $basePages) {
            $this->pageTreeCollection->expects($this->once())
                ->method('getBasePages')
                ->will($this->returnValue($basePages))
            ;
        }
    }

    protected function initRoutingGenerator($result, $options)
    {
        if ( ! $result) {
            $this->routingGenerator->expects($this->never())
                ->method('generateRouting')
            ;
            
            return;
        }
        
        $this->routingGenerator->expects($this->once())
            ->method('generateRouting')
            ->with($options["deployBundle"], $options["deployController"])
            ->will($this->returnSelf())
        ;

        $this->routingGenerator->expects($this->once())
            ->method('writeRouting')
            ->with($options["configDir"])
        ;
    }

    protected function initPageTree($result = true)
    {
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree')
                                ->disableOriginalConstructor()
                                ->setMethods(array('fakeSave'))
                                ->getMock();
        
        $pageTree->expects($this->once())
            ->method('fakeSave')
            ->will($this->returnValue($result))
        ;
        
        return $pageTree;
    }
    
    protected function assertsHaveBeenCopied($result)
    {
        if ( ! $result) {
            $this->assertFalse($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('media'));
            $this->assertFalse($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('js'));
            $this->assertFalse($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('css'));
            
            return;
        }
        
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('media'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('js'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('css'));
        $this->assertFileExists(vfsStream::url('root\AcmeWebSiteBundle\Resources\public\media\image1.png'));
        $this->assertFileExists(vfsStream::url('root\AcmeWebSiteBundle\Resources\public\media\image2.png'));
        $this->assertFileExists(vfsStream::url('root\AcmeWebSiteBundle\Resources\public\js\code.js'));
        $this->assertFileExists(vfsStream::url('root\AcmeWebSiteBundle\Resources\public\css\style.css'));
    }
    
    protected function initDispatcher($dispatcher)
    {
        if (null === $dispatcher) {
            $this->dispatcher->expects($this->never())
                ->method('dispatch')
            ;
            
            return;
        }
        
        $this->dispatcher->expects($this->at(0))
            ->method('dispatch')
            ->with('deploy.before_deploy')
        ;
        
        $this->dispatcher->expects($this->at(1))
            ->method('dispatch')
            ->with('deploy.after_deploy')
        ;
    }
    
    protected function initSitemapGenerator($sitemapGenerator, $options)
    {
        if (null === $sitemapGenerator) {
            $this->sitemapGenerator->expects($this->never())
                ->method('writeSiteMap')
            ;
            
            return;
        }
        
        $this->sitemapGenerator->expects($this->once())
            ->method('writeSiteMap')
            ->with($options["webFolderPath"], $options["websiteUrl"])
        ;
    }     
    
    protected function verifyFoldersBeforeDeploy()
    {
        $this->assertFalse($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('config'));
        $this->assertFalse($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('public'));
        $this->assertFalse($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('views'));
    }     
    
    protected function verifyFoldersAfterDeploy($templatesFolder)
    {
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('config'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('public'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('views'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->hasChild($templatesFolder));
    }
}