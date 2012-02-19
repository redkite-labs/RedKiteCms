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
use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlDeployer;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\HttpKernel\Util\Filesystem;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;

class AlDeployerTest extends AlDeployer
{
    protected function save(AlPageTree $pageTree){}
    
    public function getDeployBundle()
    {
        return $this->deployBundle;
    }
    
    public function getResourcesFolder()
    {
        return $this->resourcesFolder;
    }
    
    public function getDataFolder()
    {
        return $this->dataFolder;
    }
    
    public function getTranslationsFolder()
    {
        return $this->translationsFolder;
    }
    
    public function getDeployBundleDir()
    {
        return $this->deployBundleAssetsFolder;
    }
    
    public function getPageTrees()
    {
        return $this->pageTrees;
    }
    
    public function setPageTrees($v)
    {
        $this->pageTrees = $v;
    }
    
    public function getBasePage()
    {
        return $this->basePages;
    }
    
    public function doSetup()
    {
        $this->setup();
    }
    
    public function testCheckFoldersMethod()
    {
        $this->checkFolders();
    }
    
    public function testCopyAssetsMethod()  
    {   
        $this->copyAssets();
    }
    
    public function createAssetFolders()
    {
        $this->deployBundleAssetsFolder = $this->resourcesFolder . '/public/assets';
        $fileSystem = new Filesystem();
        $fileSystem->mkdir(array($this->deployBundleAssetsFolder, $this->cmsUploadFolder, $this->cmsUploadFolder . '/media'));
        $fileSystem->touch(array($this->cmsUploadFolder.'/media/a.jpg', $this->cmsUploadFolder.'/media/b.jpg'));
    }
    
    public function testGenerateRoutes($path)  
    {   
        $this->generateRoutes($path);
    }
    
    public function testWriteDictionaryFiles()  
    {   
        $this->writeDictionaryFiles();
    }
    
    public function testCreatePageTree(AlLanguage $alLanguage, AlPage $alPage)  
    {   
        $this->pageTrees[] = $this->createPageTree($alLanguage, $alPage);
    }
    
    public function addBasePage(AlPageTree $pageTree)
    {
        $this->basePages[$pageTree->getAlPage()->getId()] = $pageTree;;
    }
    
    public function testSetupPageTrees()
    {
        $this->setupPageTrees();
    }
    
    public function testWriteTwigAssetsFiles()
    {
        $this->writeTwigAssetsFiles();
    }
}


class AlDeployTest extends TestCase 
{   
    private static $alDeployer;
    private static $alLanguageManager;
    private static $alPageManager;
    
    protected function setUp()
    {
        parent::setUp();
        
        self::$alDeployer = new AlDeployerTest($this->getContainer());
        self::$alDeployer->deployBundle('AlphaLemonCmsBundle')->cmsResourcesDir('ResourcesTest')->targetBundleResourcesDir('ResourcesTest')->doSetup();
    }
    
    public static function tearDownAfterClass()
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove(self::$alDeployer->getResourcesFolder());
    }
    
    public function testAlDeployerHasTheDefaultParams()
    {
        $alDeployer = new AlDeployerTest($this->getContainer());
        $alDeployer->doSetup();
        $this->assertEquals($this->getContainer()->getParameter('al.deploy_bundle'), $alDeployer->getDeployBundle());
        $resourcesFolder = AlToolkit ::locateResource($this->getContainer(), $this->getContainer()->getParameter('al.deploy_bundle')) . 'Resources';
        $this->assertEquals($resourcesFolder, $alDeployer->getResourcesFolder());
        $this->assertEquals($resourcesFolder . '/data', $alDeployer->getDataFolder());
        $this->assertEquals($resourcesFolder . '/translations', $alDeployer->getTranslationsFolder());
    }
    
    public function testDeployBundledHasBeenChanged()
    {
        self::$alDeployer->dataDir('dataTest')->translationsDir('transTest')->doSetup();
        $this->assertEquals('AlphaLemonCmsBundle', self::$alDeployer->getDeployBundle());
        $resourcesFolder = AlToolkit ::locateResource($this->getContainer(), 'AlphaLemonCmsBundle') . 'ResourcesTest';
        $this->assertEquals($resourcesFolder, self::$alDeployer->getResourcesFolder());
        $this->assertEquals($resourcesFolder . '/dataTest', self::$alDeployer->getDataFolder());
        $this->assertEquals($resourcesFolder . '/transTest', self::$alDeployer->getTranslationsFolder());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAnInvalidDeployBundleHasBeenGiven()
    {
        self::$alDeployer->deployBundle('FakeBundle')->doSetup();
    }
    
    public function testFailedCreatedDataDirIntoANotWritableFolder()
    {
        $fileSystem = new Filesystem();
        $fileSystem->mkdir(self::$alDeployer->getResourcesFolder(), 0644);
        try
        {
            self::$alDeployer->testCheckFoldersMethod();
            $this->pass();
        }
        catch(\RuntimeException $ex)
        {
        }
        $fileSystem->remove(self::$alDeployer->getResourcesFolder());
    }
    
    /**
     * @depends testFailedCreatedDataDirIntoANotWritableFolder
     */
    public function testDataAndTranslationsFoldersCreated()
    {
        self::$alDeployer->testCheckFoldersMethod();
        $this->assertFileExists(self::$alDeployer->getResourcesFolder());
        $this->assertFileExists(self::$alDeployer->getDataFolder());
        $this->assertFileExists(self::$alDeployer->getTranslationsFolder());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testCopyAssetsWhenResourcesFolderDoesNotExists()
    {
        $fileSystem = new Filesystem();
        self::$alDeployer->testCopyAssetsMethod();
    }
    
    public function testCopyAssets()
    {
        $fileSystem = new Filesystem();
        self::$alDeployer->createAssetFolders();
        self::$alDeployer->testCopyAssetsMethod();
        $this->assertFileExists(self::$alDeployer->getDeployBundleDir() . "/media/a.jpg");
        $this->assertFileExists(self::$alDeployer->getDeployBundleDir() . "/media/b.jpg");
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testRoutesAreNotGeneratedWhenAnInvaliPathIsGiven()
    {
        $fileSystem = new Filesystem();
        self::$alDeployer->testGenerateRoutes(self::$alDeployer->getResourcesFolder() . '/config');
    }
    
    public function testRoutesHaveBeenGenerated()
    {
        AlphaLemonDataPopulator::depopulate();
        
        self::$alLanguageManager = new AlLanguageManager(
            $this->getContainer()
        );
        $params = array('language' => 'en');
        self::$alLanguageManager->save($params);
        
        $alLanguageManager = new AlLanguageManager(
            $this->getContainer()
        );
        $params = array('language' => 'fr');
        $alLanguageManager->save($params);
        
        $container = $this->setupPageTree(self::$alLanguageManager->get()->getId())->getContainer(); 
        self::$alPageManager = new AlPageManager(
            $container
        );
        
        $theme = new \AlphaLemon\ThemeEngineBundle\Model\AlTheme();
        $theme->setThemeName('AlphaLemonThemeBundle');
        $theme->setActive(1);
        $theme->save();
        
        $params = array('pageName'      => 'index', 
                        'template'      => 'home',
                        'permalink'     => 'site-homepage',
                        'title'         => 'page title',
                        'description'   => 'page description',
                        'keywords'      => '');
        self::$alPageManager->save($params);
        
        $fileSystem = new Filesystem();
        $fileSystem->mkdir(self::$alDeployer->getResourcesFolder() . '/config');
        self::$alDeployer->testGenerateRoutes(self::$alDeployer->getResourcesFolder() . '/config');
        
        $routingFile = self::$alDeployer->getResourcesFolder() . "/config/site_routing.yml";
        $this->assertFileExists($routingFile);
        
        $routing = file_get_contents($routingFile);
        $this->assertRegExp('/_en_index:/', $routing);
        $this->assertRegExp('/pattern: \/site-homepage/', $routing);
        $this->assertRegExp('/defaults: { _controller: AlphaLemonWebSiteBundle:WebSite:show, _locale: en, page: index }/', $routing);
        $this->assertRegExp('/_home:/', $routing);
        $this->assertRegExp('/pattern: \//', $routing);
        $this->assertRegExp('/defaults: { _controller: AlphaLemonWebSiteBundle:WebSite:show, _locale: en, page: index }/', $routing);
    }
    
    /**
     * @depends testRoutesHaveBeenGenerated
     */
    public function testSetupPageTrees()
    {
        $this->assertEquals(0, count(self::$alDeployer->getPageTrees()));
        self::$alDeployer->testSetupPageTrees();
        $this->assertEquals(2, count(self::$alDeployer->getPageTrees()));
    }
    
    /**
     * @depends testSetupPageTrees
     */
    public function testWriteDictionaryFiles()
    {
        self::$alDeployer->testSetupPageTrees();        
        self::$alDeployer->testWriteDictionaryFiles();
        $this->assertEquals(1, count(self::$alDeployer->getBasePage()));
        $this->assertFileExists(self::$alDeployer->getTranslationsFolder() . "/index.en.xliff");
        $this->assertFileExists(self::$alDeployer->getTranslationsFolder() . "/index.fr.xliff");
        
        $routing = file_get_contents(self::$alDeployer->getTranslationsFolder() . "/index.en.xliff");
        $this->assertRegExp('/trans-unit id="1"/', $routing);
        $this->assertRegExp('/trans-unit id="26"/', $routing);
        
        $routing = file_get_contents(self::$alDeployer->getTranslationsFolder() . "/index.fr.xliff");
        $this->assertRegExp('/trans-unit id="1"/', $routing);
        $this->assertRegExp('/trans-unit id="26"/', $routing);
        
        return self::$alDeployer;
    }
    
    /**
     * @depends testWriteDictionaryFiles
     */
    public function testWriteBaseTemplateTwigAssetsFiles(AlDeployerTest $deployer)
    {
        $deployer->twigAssetsFolder('ResourcesTest/views/Assets');
        $deployer->testWriteTwigAssetsFiles();
        
        $this->assertFileExists($deployer->getResourcesFolder() . "/views/Assets/home_javascripts.html.twig");
        $this->assertFileExists($deployer->getResourcesFolder() . "/views/Assets/home_stylesheets.html.twig");
        
        $assetJsContents = file_get_contents($deployer->getResourcesFolder() . "/views/Assets/home_javascripts.html.twig");
        $this->assertEmpty($assetJsContents);
        
        $assetCssContents = file_get_contents($deployer->getResourcesFolder() . "/views/Assets/home_stylesheets.html.twig");
        $this->assertRegExp('/filter=\'\?yui_css,cssrewrite\'/', $assetCssContents);
        $this->assertRegExp('/bundles\/al2011theme\/css\/screen.css/', $assetCssContents);
        
        return $deployer;
    }
    
    /**
     * @depends testWriteDictionaryFiles
     */
    public function testAddingCustomAssetGeneratesNewTwigAssetFile(AlDeployerTest $deployer)
    {
        $pagesTrees = $deployer->getPageTrees();
        $pageTree = $pagesTrees[0];
        $pageTree->addJavascript('custom.js'); 
        $pageTree->addStylesheet('custom.css'); 
        $pagesTrees[0] = $pageTree;
        $deployer->setPageTrees($pagesTrees);
        
        $deployer->testWriteTwigAssetsFiles();
        $this->assertFileExists($deployer->getResourcesFolder() . "/views/Assets/en_index_javascripts.html.twig");
        $assetJsContents = file_get_contents($deployer->getResourcesFolder() . "/views/Assets/en_index_javascripts.html.twig");
        $this->assertRegExp('/filter=\'\?yui_js\'/', $assetJsContents);
        $this->assertRegExp('/custom.js/', $assetJsContents);
        
        $this->assertFileExists($deployer->getResourcesFolder() . "/views/Assets/en_index_stylesheets.html.twig");
        $assetCssContents = file_get_contents($deployer->getResourcesFolder() . "/views/Assets/en_index_stylesheets.html.twig");
        $this->assertRegExp('/custom.css/', $assetCssContents);
    }
}