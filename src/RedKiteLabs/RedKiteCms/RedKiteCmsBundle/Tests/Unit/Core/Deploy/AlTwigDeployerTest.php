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

use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlTwigDeployer;
use org\bovigo\vfs\vfsStream;

/**
 * AlDeployerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlTwigDeployerTest extends AlDeployerTest
{
    protected function setUp()
    {
        parent::setUp();
        
        $this->twigTemplateWriter = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter\TwigTemplateWriter')
                                        ->disableOriginalConstructor()
                                        ->getMock();
    }
    
    /**
     * @dataProvider deployProvider
     */
    public function testDeploy($pages, $basePages, $result, $sitemapGenerator = null, $dispatcher = null)
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
        
        $this->initPageTreeCollection($pages, $basePages);
        $this->initRoutingGenerator($result, $options);
        $this->initSitemapGenerator($sitemapGenerator, $options);
        $this->initDispatcher($dispatcher);
        $index = $this->save($pages, $options);
        if (null !== $index) {
            $this->save($basePages, $options, $index);
        }
        
        $this->verifyFoldersBeforeDeploy();
        
        $this->deployer = new AlTwigDeployer($this->twigTemplateWriter, $this->routingGenerator, $sitemapGenerator, $dispatcher);
        $this->assertEquals($result, $this->deployer->deploy($this->pageTreeCollection, $this->theme, $options));
        
        $this->verifyFoldersAfterDeploy('RedKiteCms');        
        $this->assertsHaveBeenCopied($result);
    }
    
    /**
     * @dataProvider deployProvider
     */
    public function testStageDeploy($pages, $basePages, $result, $sitemapGenerator = null, $dispatcher = null)
    {
        $deployBundlePath = vfsStream::url('root\AcmeWebSiteBundle');
        $options = array(
            "deployBundle" => 'AcmeWebSiteBundle',
            "configDir" => $deployBundlePath . '/Resources/config',
            "assetsDir" => $deployBundlePath  . '/Resources/public/stage',
            "viewsDir" => $deployBundlePath  . '/Resources/views',
            "deployDir" => $deployBundlePath  . '/Resources/views/RedKiteCmsStage',
            "uploadAssetsFullPath" => vfsStream::url('root\web\uploads\assets'),
            "uploadAssetsAbsolutePath" => '/uploads/assets',
            "deployController" => "WebSite",
            "webFolderPath" => vfsStream::url('root\web'),
            "websiteUrl" => "http://example.com",
        );
        
        $this->initPageTreeCollection($pages, $basePages);
        $this->initRoutingGenerator($result, $options);
        $this->initSitemapGenerator($sitemapGenerator, $options);
        $this->initDispatcher($dispatcher);
        $index = $this->save($pages, $options);
        if (null !== $index) {
            $this->save($basePages, $options, $index);
        }
        
        $this->verifyFoldersBeforeDeploy();
        
        $this->deployer = new \RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlTwigDeployer($this->twigTemplateWriter, $this->routingGenerator, $sitemapGenerator, $dispatcher);
        $this->assertEquals($result, $this->deployer->deploy($this->pageTreeCollection, $this->theme, $options));
        
        $this->verifyFoldersAfterDeploy('RedKiteCmsStage');        
        $this->assertsHaveBeenCopiedStage($result);
    }
    
    private function save($pages, $options, $index = 0)
    {
        if (null === $pages) {
            return null;
        }
        
        foreach($pages as $pageTree) {
            $result = $pageTree->fakeSave();        
            $this->initTwigTemplateWriter($pageTree, $options, $index, $result);
            
            $index += 2;
            if ( ! $result) {
                break;
            }
        }
        
        return $index;
    }
    
    protected function assertsHaveBeenCopiedStage($result)
    {
        if ( ! $result) {
            $this->assertFalse($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('stage')->hasChild('media'));
            $this->assertFalse($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('stage')->hasChild('js'));
            $this->assertFalse($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('stage')->hasChild('css'));
            
            return;
        }
        
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('stage')->hasChild('media'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('stage')->hasChild('js'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('stage')->hasChild('css'));
        $this->assertFileExists(vfsStream::url('root\AcmeWebSiteBundle\Resources\public\stage\media\image1.png'));
        $this->assertFileExists(vfsStream::url('root\AcmeWebSiteBundle\Resources\public\stage\media\image2.png'));
        $this->assertFileExists(vfsStream::url('root\AcmeWebSiteBundle\Resources\public\stage\js\code.js'));
        $this->assertFileExists(vfsStream::url('root\AcmeWebSiteBundle\Resources\public\stage\css\style.css'));
    }
    
    private function initTwigTemplateWriter($pageTree, $options, $index, $result)
    {
        $this->twigTemplateWriter->expects($this->at($index))
            ->method('generateTemplate')
            ->with($pageTree, $this->theme)
            ->will($this->returnSelf())
        ;
        
        $index++;
        
        $this->twigTemplateWriter->expects($this->at($index))
            ->method('writeTemplate')
            ->with($options["deployDir"])
            ->will($this->returnValue($result))
        ;
    }
}