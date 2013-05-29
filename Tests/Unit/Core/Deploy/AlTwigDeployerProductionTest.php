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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Deploy;

use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlTwigDeployerProduction;
use org\bovigo\vfs\vfsStream;

/**
 * AlTwigDeployerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTwigDeployerProductionTest extends AlTwigDeployer
{
    protected function setUp()
    {
        $this->templatesFolder = 'AlphaLemon';
        $this->siteRoutingFile = 'site_routing.yml';
        $this->assetsFolder = 'root\AcmeWebSiteBundle\Resources\public\\';
        
        parent::setUp();
    }
    
    protected function checkSiteMap($seo)
    {
        $sitemapFile = vfsStream::url('root\sitemap.xml');
        $this->assertFileExists($sitemapFile);
        $this->assertEquals($this->buildExpectedSitemap($seo), file_get_contents($sitemapFile));
    }
    
    protected function getDeployer()
    {
        return new AlTwigDeployerProduction($this->container);    
    }
    
    protected function initContainer()
    {
        parent::initContainer();
        
         $this->container->expects($this->at(9))
            ->method('getParameter')
            ->with('alpha_lemon_theme_engine.deploy.templates_folder')
            ->will($this->returnValue($this->templatesFolder));
        
        $this->container->expects($this->at(15))
            ->method('get')
            ->with('alpha_lemon_cms.url_manager')
            ->will($this->returnValue($this->urlManager));

        $this->container->expects($this->at(16))
            ->method('get')
            ->with('alpha_lemon_cms.block_manager_factory')
            ->will($this->returnValue($this->blockManagerFactory));

        $this->container->expects($this->at(17))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.views_dir')
            ->will($this->returnValue('Resources/views'));
            
        $this->container->expects($this->at(18))
            ->method('get')
            ->with('alpha_lemon_cms.themes_collection_wrapper')
            ->will($this->returnValue($this->themesCollectionWrapper));
            
        $this->containerAtSequenceAfterObjectCreation = 19;
    }
}
