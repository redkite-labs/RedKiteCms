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

use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlTwigDeployerStage;
use org\bovigo\vfs\vfsStream;

/**
 * AlTwigDeployerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTwigDeployerStageTest extends AlTwigDeployer
{
    protected function setUp()
    {
        $this->templatesFolder = 'AlphaLemonStage';
        $this->siteRoutingFile = 'site_routing_stage.yml';
        
        parent::setUp();
    }
    
    protected function checkSiteMap($seo)
    {
        $sitemapFile = vfsStream::url('root\sitemap.xml');
        $this->assertFileNotExists($sitemapFile);
    }
    
    protected function getDeployer()
    {
        return new AlTwigDeployerStage($this->container);    
    }
    
    protected function initContainer()
    {
        parent::initContainer();
        
        $this->container->expects($this->at(10))
            ->method('getParameter')
            ->with('alpha_lemon_theme_engine.deploy.stage_templates_folder')
            ->will($this->returnValue($this->templatesFolder));
         
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
            ->with('alpha_lemon_cms.url_manager_stage')
            ->will($this->returnValue($this->urlManager));
                
        $this->container->expects($this->at(19))
            ->method('get')
            ->with('alpha_lemon_cms.themes_collection_wrapper')
            ->will($this->returnValue($this->themesCollectionWrapper));
        
        $this->containerAtSequenceAfterObjectCreation = 20;
    }
    
    protected function buildExpectedRoutes($seo)
    {
        $routes = array();
        foreach($seo as $seoAttributes) {
            $language = $seoAttributes["language"];
            $page = $seoAttributes["page"];
            $permalink = ( ! $seoAttributes["homepage"]) ? $seoAttributes["permalink"] : "" ;
            $siteRouting = "# Route << %s >> generated for language << %s >> and page << %s >>\n";
            $siteRouting .= "_stage_%s_%s:\n";
            $siteRouting .= "  pattern: /%s\n";
            $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:stage, _locale: %s, page: %s }\n";

            $routes[] = sprintf($siteRouting, $permalink, $language, $page, $language, str_replace('-', '_', $page), $permalink, $language, $page);
        }
        
        $siteRouting = "# Route <<  >> generated for language << en >> and page << index >>\n";
        $siteRouting .= "stage_home:\n";
        $siteRouting .= "  pattern: /\n";
        $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:stage, _locale: en, page: index }";
        
        $routes[] = $siteRouting;
        
        return (implode("\n", $routes));
    }
}