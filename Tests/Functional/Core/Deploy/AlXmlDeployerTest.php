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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Deploy;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\HttpKernel\Util\Filesystem;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlContentQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlXmlDeployer;

class AlXmlDeployerTest extends AlXmlDeployer
{
    public function getResourcesFolder()
    {
        return $this->resourcesFolder;
    }
    
    public function createAssetFolders()
    {
        $this->setup();
        $this->checkFolders();
        $fileSystem = new Filesystem();
        $fileSystem->mkdir(array($this->getResourcesFolder(), $this->getResourcesFolder() . '/config', $this->deployBundleAssetsFolder, $this->cmsUploadFolder, $this->cmsUploadFolder . '/media'));
        $fileSystem->touch(array($this->cmsUploadFolder.'/media/a.jpg', $this->cmsUploadFolder.'/media/b.jpg')); 
    }
}
    
class AlXmlDeployTest extends TestCase 
{   
    private static $alDeployer;
    private static $pageManager;
        
    public static function tearDownAfterClass()
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove(self::$alDeployer->getResourcesFolder());
    }
    
    public function testDeployment()
    {
        AlphaLemonDataPopulator::depopulate();
        
        $theme = new \AlphaLemon\ThemeEngineBundle\Model\AlTheme();
        $theme->setThemeName('ThemeBundle');
        $theme->setActive(1);
        $theme->save();
        
        $alLanguageManager = new AlLanguageManager(
            $this->getContainer()
        );
        $params = array('language' => 'en');
        $alLanguageManager->save($params);
        
        $container = $this->setupPageTree($alLanguageManager->get()->getId())->getContainer(); 
        $params = array('pageName'      => 'index', 
                        'template'      => 'home',
                        'permalink'     => 'site-homepage',
                        'title'         => 'page title',
                        'description'   => 'page description',
                        'keywords'      => 'home,page');
        self::$pageManager = $this->AddPage($container, $params);
        
        $content = AlContentQuery::create()->fromPageIdAndSlotName(self::$pageManager->get()->getId(), 'header')->fromLanguageId($alLanguageManager->get()->getId())->findOne();
        $content->setInternalStylesheet('fake');
        $content->setInternalJavascript('fake');
        $content->setExternalJavascript('fake');
        $content->save();        
        
        $params = array('pageName'      => 'internal', 
                        'template'      => 'home',
                        'permalink'     => 'site-internal',
                        'title'         => 'internal title',
                        'description'   => 'internal description',
                        'keywords'      => 'internal,page');
        $this->AddPage($container, $params);
        
        
        $params = array('pageName'      => 'internal1', 
                        'template'      => 'home',
                        'permalink'     => 'site-internal1',
                        'title'         => 'internal1 title',
                        'description'   => 'internal1 description',
                        'keywords'      => 'internal1,page');
        $this->AddPage($container, $params);
        
        self::$alDeployer = new AlXmlDeployerTest($this->getContainer());
        self::$alDeployer->deployBundle('AlphaLemonCmsBundle')->cmsResourcesDir('ResourcesTest')->targetBundleResourcesDir('ResourcesTest');
        self::$alDeployer->createAssetFolders();
        self::$alDeployer->twigAssetsFolder('ResourcesTest/public/views/Assets');
        self::$alDeployer->deploy();
        
        $routingFile = self::$alDeployer->getResourcesFolder() . '/config/site_routing.yml';
        $this->assertFileExists($routingFile);
        
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/public/media/a.jpg');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/public/media/b.jpg');
        
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/public/views/Assets/en_index_javascripts.html.twig');
        
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/data/index.xml');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/data/internal.xml');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/data/internal1.xml');
        
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/translations/index.en.xliff');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/translations/internal.en.xliff');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/translations/internal1.en.xliff');        
    }
    
    /**
     * @depends testDeployment
     */
    public function testRouting()
    {
        $routingFile = self::$alDeployer->getResourcesFolder() . '/config/site_routing.yml';
        $routing = file_get_contents($routingFile);
        $this->assertRegExp('/_en_index:/', $routing);
        $this->assertRegExp('/pattern: \/site-homepage/', $routing);
        $this->assertRegExp('/defaults: { _controller: AlphaLemonWebSiteBundle:WebSite:show, _locale: en, page: index }/', $routing);
        $this->assertRegExp('/_en_internal:/', $routing);
        $this->assertRegExp('/pattern: \/site-internal/', $routing);
        $this->assertRegExp('/defaults: { _controller: AlphaLemonWebSiteBundle:WebSite:show, _locale: en, page: internal }/', $routing);
        $this->assertRegExp('/_en_internal1:/', $routing);
        $this->assertRegExp('/pattern: \/site-internal1/', $routing);
        $this->assertRegExp('/defaults: { _controller: AlphaLemonWebSiteBundle:WebSite:show, _locale: en, page: internal1 }/', $routing);
        $this->assertRegExp('/_home:/', $routing);
        $this->assertRegExp('/pattern: \//', $routing);
        $this->assertRegExp('/defaults: { _controller: AlphaLemonWebSiteBundle:WebSite:show, _locale: en, page: index }/', $routing);
    }
    
    /**
     * @depends testDeployment
     */
    public function testData()
    {
        $dataFile = self::$alDeployer->getResourcesFolder() . '/data/index.xml';
        $data = file_get_contents($dataFile); 
        $this->assertRegExp('/\<en\>/', $data);
        $this->assertRegExp('/\<title\>page title\<\/title\>/', $data);
        $this->assertRegExp('/\<description>page description\<\/description>/', $data);
        $this->assertRegExp('/\<keywords>home,page\<\/keywords>/', $data);
        $this->assertRegExp('/\<internal_javascripts\>\<en\>try\{fake\}catch\(e\)\{alert\(/', $data);
        $this->assertRegExp('/\<internal_stylesheets\>\<en\>fake\<\/en\>\<\/internal_stylesheets\>/', $data);
        $this->assertRegExp('/\<slot name="header"\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/slot\>/', $data);
        
        $dataFile = self::$alDeployer->getResourcesFolder() . '/data/internal.xml';
        $data = file_get_contents($dataFile); 
        $this->assertRegExp('/\<en\>/', $data);
        $this->assertRegExp('/\<title\>internal title\<\/title\>/', $data);
        $this->assertRegExp('/\<description>internal description\<\/description>/', $data);
        $this->assertRegExp('/\<keywords>internal,page\<\/keywords>/', $data);
        $this->assertRegExp('/\<slot name="header"\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/slot\>/', $data);
        
        $dataFile = self::$alDeployer->getResourcesFolder() . '/data/internal1.xml';
        $data = file_get_contents($dataFile); 
        $this->assertRegExp('/\<en\>/', $data);
        $this->assertRegExp('/\<title\>internal1 title\<\/title\>/', $data);
        $this->assertRegExp('/\<description>internal1 description\<\/description>/', $data);
        $this->assertRegExp('/\<keywords>internal1,page\<\/keywords>/', $data);
        $this->assertRegExp('/\<slot name="header"\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/slot\>/', $data);
    }
    
    /**
     * @depends testDeployment
     */
    public function testDictionaries()
    {
        $dataFile = self::$alDeployer->getResourcesFolder() . '/translations/index.en.xliff';
        $data = file_get_contents($dataFile); 
        $this->assertRegExp('/\<trans-unit id="1"\>\<source\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/source\>\<target\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/target\>\<\/trans-unit\>/', $data);
        
        $dataFile = self::$alDeployer->getResourcesFolder() . '/translations/internal.en.xliff';
        $data = file_get_contents($dataFile); 
        $this->assertRegExp('/\<trans-unit id="1"\>\<source\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/source\>\<target\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/target\>\<\/trans-unit\>/', $data);
        
        $dataFile = self::$alDeployer->getResourcesFolder() . '/translations/internal1.en.xliff';
        $data = file_get_contents($dataFile); 
        $this->assertRegExp('/\<trans-unit id="1"\>\<source\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/source\>\<target\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/target\>\<\/trans-unit\>/', $data);
    }
    
    /**
     * @depends testDeployment
     */
    public function testTwigAssets()
    {
        $twigFile = self::$alDeployer->getResourcesFolder() . '/public/views/Assets/en_index_javascripts.html.twig';
        $data = file_get_contents($twigFile); 
        $this->assertRegExp('/bundles\/alphalemonwebsite\/js\/fake/', $data);        
    }
    
    /**
     * @depends testDeployment
     */
    public function testDeployANewLanguage()
    {
        
        $alLanguageManager = new AlLanguageManager(
            $this->getContainer()
        );
        $params = array('language' => 'fr');
        $alLanguageManager->save($params);
        
        
        $content = AlContentQuery::create()->fromPageIdAndSlotName(self::$pageManager->get()->getId(), 'header')->fromLanguageId($alLanguageManager->get()->getId())->findOne();
        $content->setHtmlContent('Translation test');
        $content->save(); 
        
        self::$alDeployer->deploy();
        
        $routingFile = self::$alDeployer->getResourcesFolder() . '/config/site_routing.yml';
        $this->assertFileExists($routingFile);
        
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/public/media/a.jpg');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/public/media/b.jpg');
        
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/public/views/Assets/en_index_javascripts.html.twig');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/public/views/Assets/fr_index_javascripts.html.twig');
        
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/data/index.xml');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/data/internal.xml');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/data/internal1.xml');
        
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/translations/index.en.xliff');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/translations/internal.en.xliff');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/translations/internal1.en.xliff');    
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/translations/index.fr.xliff');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/translations/internal.fr.xliff');
        $this->assertFileExists(self::$alDeployer->getResourcesFolder() . '/translations/internal1.fr.xliff');     
    }
    
    /**
     * @depends testDeployment
     */
    public function testDictionariesNewLanguage()
    {
        $dataFile = self::$alDeployer->getResourcesFolder() . '/translations/index.fr.xliff';
        $data = file_get_contents($dataFile); 
        $this->assertRegExp('/\<trans-unit id="1"\>\<source\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/source\>\<target\>Translation\+test\<\/target\>\<\/trans-unit\>/', $data);
        
        $dataFile = self::$alDeployer->getResourcesFolder() . '/translations/internal.fr.xliff';
        $data = file_get_contents($dataFile); 
        $this->assertRegExp('/\<trans-unit id="1"\>\<source\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/source\>\<target\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/target\>\<\/trans-unit\>/', $data);
        
        $dataFile = self::$alDeployer->getResourcesFolder() . '/translations/internal1.fr.xliff';
        $data = file_get_contents($dataFile); 
        $this->assertRegExp('/\<trans-unit id="1"\>\<source\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/source\>\<target\>This\+is\+the\+default\+text\+for\+a\+new\+text\+content\<\/target\>\<\/trans-unit\>/', $data);
    }
    
    /**
     * @depends testDeployment
     */
    public function testTwigAssetsWithTwoLanguages()
    {
        $twigFile = self::$alDeployer->getResourcesFolder() . '/public/views/Assets/en_index_javascripts.html.twig';
        $data = file_get_contents($twigFile); 
        $this->assertRegExp('/bundles\/alphalemonwebsite\/js\/fake/', $data);        
        
        $twigFile = self::$alDeployer->getResourcesFolder() . '/public/views/Assets/fr_index_javascripts.html.twig';
        $data = file_get_contents($twigFile); 
        $this->assertRegExp('/bundles\/alphalemonwebsite\/js\/fake/', $data);        
    }
    
    private function AddPage($container, $params)
    {
        $alPageManager = new AlPageManager(
            $container
        );        
        $alPageManager->save($params);
        
        return $alPageManager;
    }
}