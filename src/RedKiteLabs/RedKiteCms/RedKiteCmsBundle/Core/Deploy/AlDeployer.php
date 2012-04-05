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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Deploy;

use Symfony\Component\Filesystem\Filesystem;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\ThemeEngineBundle\Model\AlTheme;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageAttributeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\Finder\Finder;

use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;

/**
 * The base object that implements the methods to deploy the website from development (CMS) to production (the deploy bundle)
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
abstract class AlDeployer
{
    private $baseDeployBundle;
    private $baseDeployBundleAssetsFolder;
    private $baseCmsResourcesDir = 'Resources';
    private $baseTargetResourcesDir = 'Resources';
    private $baseDataDir = 'data';
    private $baseTranslationsDir = 'translations';
    private $twigAssetsDir = null;
    
    protected $pageTrees = array();
    protected $container = null;
    protected $resourcesFolder = null;
    protected $dataFolder = null;
    protected $translationsFolder = null;
    protected $basePages = array();
    protected $deployBundle = null;
    protected $cmsBundleFolder;
    protected $cmsUploadFolder;
    protected $deployBundleAssetsFolder;
                
    protected $assetsFolder = null;
    

    /**
     * Implements the method to save the page
     */
    abstract protected function save(AlPageTree $pageTree);
    
    public function  __construct(ContainerInterface $container)
    {
        $this->container = $container; 
        $this->baseDeployBundle = $this->container->getParameter('al.deploy_bundle');
        $this->baseDeployBundleAssetsFolder = $this->container->getParameter('al.deploy_bundle_assets_base_dir');
    }


    /**
    * Publish all the website's pages
    */
    public function deploy()
    {
        $this->setup();
        $this->checkFolders();
        $this->run();
    }
    
    public function deployBundle($v)
    {
        $this->baseDeployBundle = $v;
        
        return $this;
    }
    
    /**
     * 
     * @param type $v
     * @return AlDeployer 
     */
    public function cmsResourcesDir($v)
    {
        $this->baseCmsResourcesDir = $v;
        
        return $this;
    }
    
    public function targetBundleResourcesDir($v)
    {
        $this->baseTargetResourcesDir = $v;
        
        return $this;
    }
    
    public function dataDir($v)
    {
        $this->baseDataDir = $v;
        
        return $this;
    }
    
    public function translationsDir($v)
    {
        $this->baseTranslationsDir = $v;
        
        return $this;
    }
    
    public function twigAssetsFolder($v)
    {
        $this->twigAssetsDir = $v;
        
        return $this;
    }
    
    public function deployBundleAssetsFolder($v)
    {
        $this->baseDeployBundleAssetsFolder = $v;
        
        return $this;
    }
    
    /**
     * Checks if the publisher folders exist and creates them when required
     */
    protected function checkFolders()
    {
        $fileSystem = new Filesystem();

        if(!is_dir($this->resourcesFolder))
        {
            if(!$fileSystem->mkdir($this->resourcesFolder))
            {
                throw new \RuntimeException(sprintf('Cannot create the resources directory. Please check your permissions on %s folder.', $this->dataFolder));
            }
        }
        
        if(is_dir($this->dataFolder))
        {
            $fileSystem->remove($this->dataFolder);
        }

        if(!$fileSystem->mkdir($this->dataFolder))
        {
            throw new \RuntimeException(sprintf('Cannot create the publish directory at %s. Please check your permissions on %s folder.', $this->resourcesFolder, $this->dataFolder));
        }

        if(!$fileSystem->mkdir($this->translationsFolder))
        {
            throw new \RuntimeException(sprintf('Cannot create the publish directory at %s. Please check your permissions on %s folder.', $this->resourcesFolder, $this->translationsFolder));
        }
    }
    
    /**
     * Initializes the parameters to deploy the website
     */
    protected function setup()
    {    
        $this->cmsWebBundleFolder = AlToolkit::retrieveBundleWebFolder($this->container, 'AlphaLemonCmsBundle');
        $this->cmsBundleFolder = $this->container->getParameter('kernel.root_dir') . '/../web/' . $this->cmsWebBundleFolder;
        $this->deployBundle = $this->baseDeployBundle;
        if(false === $deployBundle = AlToolkit::locateResource($this->container, $this->deployBundle))
        {
            throw new \InvalidArgumentException(sprintf('The %s cannot be located. Check it is correctly enabled in your AppKernel class', $this->deployBundle));
        }
        
        $this->resourcesFolder = $deployBundle . $this->baseTargetResourcesDir; 
        $this->dataFolder = $this->resourcesFolder . "/" . $this->baseDataDir; 
        $this->translationsFolder = $this->resourcesFolder . "/" . $this->baseTranslationsDir;
        $this->assetsFolder = AlToolkit::retrieveBundleWebFolder($this->container, $this->deployBundle);     
        
        $this->cmsUploadFolder = $this->cmsBundleFolder . '/' . $this->container->getParameter('alcms.upload_assets_dir'); 
        $this->deployBundleAssetsFolder = $this->container->getParameter('kernel.root_dir') . '/../web/' . $this->assetsFolder; 
    }

    /**
     * Starts the website deployment
     */
    protected function run()
    {
        $this->setupPageTrees();
        $this->writeDictionaryFiles();
        $this->writeTwigAssetsFiles();
        $this->copyAssets();
        $this->generateRoutes($this->resourcesFolder . '/config');
        AlToolkit::executeCommand($this->container->get('kernel'), 'cache:clear');
    }
    
    protected function setImagesPathForProduction($content)
    {
        $assetsFolder = $this->assetsFolder;
        $cmsAssetsFolder = str_replace('/', '\/', $this->cmsWebBundleFolder . '/' . $this->container->getParameter('alcms.upload_assets_dir'));
        //echo preg_replace_callback('/(.*?)(' . $cmsAssetsFolder . ')/s', function($matches) use($assetsFolder){return $matches[1].$assetsFolder;}, $content)."<br>";
        //return preg_replace_callback('/(.*?)(' . $cmsAssetsFolder . ')/s', function($matches) use($assetsFolder){return $matches[1].$assetsFolder;}, $content);
        return preg_replace_callback('/(.*\<img.*?src=["|\']\/)(' . $cmsAssetsFolder . ')(.*?["|\'])/s', function($matches) use($assetsFolder){return $matches[1].$assetsFolder.$matches[3];}, $content);
    }
        
    /**
     * Sets up the PageTree objects from the saved languages and pages  
     */
    protected function setupPageTrees()
    {
        $theme = AlThemeQuery::create()->activeBackend()->findOne();
        $languages = AlLanguageQuery::create()->setContainer($this->container)->activeLanguages()->find();
        $pages = AlPageQuery::create()->setContainer($this->container)->activePages()->find(); 
        $idMainLanguage = AlLanguageQuery::create()->mainLanguage()->findOne()->getId();
        
        // Cycles all the website's languages
        foreach($languages as $language)
        {
            // Cycles all the website's pages
            foreach($pages as $page)
            {
                $pageTree = $this->createPageTree($language, $page);

                if($language->getId() == $idMainLanguage)
                {
                    $this->savePage($page, $language, $theme, clone($pageTree));
                }
                
                $this->pageTrees[] = $pageTree;
            }
        }
    }
    
    /**
     * Copies the assets from the development environment to the production
     */
    protected function copyAssets()
    {
        $fs = new Filesystem();
        $finder = new Finder();        
        $folders = $finder->directories()->depth(0)->in($this->cmsUploadFolder); 
        foreach($folders as $folder)
        {
            $targetFolder = $this->deployBundleAssetsFolder . '/' . basename($folder->getFileName());
            $fs->remove($targetFolder);
            $fs->mirror($folder , $targetFolder, null, array('override' => true)); 
        }        
    }

    /**
     * Generates a yml file with the routes defined by the website's pages, in the deploy bundle's resources dir 
     * 
     * @param string    $routesFilePath     The routing file path
     */
    protected function generateRoutes($routesFilePath)
    {
        if(!is_dir($routesFilePath))
        {
            throw new \InvalidArgumentException(sprintf("The directory %s does not exist. The routes cannot be generated", $routesFilePath));
        }
        
        // The schema pattern
        $schema = "# Route << %1\$s >> generated for language << %2\$s >> and page << %3\$s >>\n";
        $schema .= "_%4\$s:\n";
        $schema .= "  pattern: /%1\$s\n";
        $schema .= "  defaults: { _controller: $this->deployBundle:WebSite:show, _locale: %2\$s, page: %3\$s }";

        $homePage = "";
        $mainLanguage = "";
        $routes = array();
        $alPageAttributes = AlPageAttributeQuery::create('a')
                                ->joinWith('a.AlPage')
                                ->joinWith('a.AlLanguage')
                                ->filterByToDelete(0)
                                ->orderByPageId()
                                ->orderByLanguageId()
                                ->find();
        foreach($alPageAttributes as $alPageAttribute)
        {
            $permalink = $alPageAttribute->getPermalink();
            
            $pageName = $alPageAttribute->getAlPage()->getPageName();
            if($alPageAttribute->getAlPage()->getIsHome()) $homePage = $pageName;
            
            $language = $alPageAttribute->getAlLanguage()->getLanguage();
            if($alPageAttribute->getAlLanguage()->getMainLanguage()) $mainLanguage = $language;
            
            $routes[] = \sprintf($schema, $permalink, $language, $pageName, str_replace('-', '_', $language) . '_' . str_replace('-', '_', $pageName));
        }
        
        // Defines the main route
        $routes[] = \sprintf($schema, '', $mainLanguage, $homePage, 'home');

        \file_put_contents($routesFilePath . '/site_routing.yml', implode("\n\n", $routes));
    }

    /**
     * Writes the dictionary files from the pages' contents
     */
    protected function writeDictionaryFiles()
    {
        if(!empty($this->basePages))
        {
            foreach($this->pageTrees as $pageTree)
            {
                $skeletonContents = file_get_contents(AlToolkit::locateResource($this->container, $this->container->getParameter('alcms.deploy.xliff_skeleton'), true));
                            
                // Writes template section
                $id = 1;
                $xml = new \SimpleXMLElement($skeletonContents); 
                foreach($pageTree->getContents() as $slotName => $contents)
                {
                    $c = 0;
                    $baseContents = $this->basePages[$pageTree->getAlPage()->getId()]->getContents($slotName); 
                    foreach($contents as $content)
                    {
                        if(empty($baseContents[$c])) {
                            $unit = $xml->file->body->addChild('trans-unit');
                            $unit->addAttribute('id', $id); 
                            $unit->addChild('source', \urlencode($this->setImagesPathForProduction($baseContents[$c]['HtmlContent'])));
                            $unit->addChild('target', \urlencode($this->setImagesPathForProduction($content['HtmlContent'])));
                            $id++;
                            $c++;
                        }
                    }
                }

                $filename = sprintf('%s/%s.%s.xliff', $this->translationsFolder, $pageTree->getAlPage()->getPageName(), $pageTree->getAlLanguage()->getLanguage());
                if(\is_file($filename)) unlink($filename);
                $xml->asXML($filename);
            }
        }
    }

    /**
     * Writes a twig file that contains the assets for each page where any kind of assets is required.
     */
    protected function writeTwigAssetsFiles()
    {
        $fs = new Filesystem();
        $outputFolder = AlToolkit::locateResource($this->container, $this->deployBundle, true) . $this->container->getParameter('alcms.assets.output_folder');
        if(!is_dir($outputFolder))
        {
            $fs->mkdir($outputFolder);
        }

        $finder = new Finder();
        $files = $finder->files()->in($outputFolder); 
        
        
        $fs->remove($files);

        foreach($this->pageTrees as $pageTree)
        {
            $languageName = $pageTree->getAlLanguage()->getLanguage();
            $pageName = $pageTree->getAlPage()->getPageName(); 
            $themeName = preg_replace('/bundle$/', '', \strtolower($pageTree->getThemeName()));
            $templateName = \strtolower($pageTree->getTemplateName()); 
            
            // Writes the base stylesheets for the current template
            $templateStylesheets = sprintf('themes.%s_%s.stylesheets', $themeName, $templateName);
            $baseCssAssets = ($this->container->hasParameter($templateStylesheets)) ? $this->container->getParameter($templateStylesheets) : array();
            $outputFileName = sprintf('%s_stylesheets.html.twig', $templateName);
            if(!file_exists($outputFileName))
            {
                $this->writeTwigAssetsFile($outputFileName, 'stylesheets_skeleton', array($baseCssAssets), array('?yui_css', 'cssrewrite'));                
            }

            // Writes the base javascripts for the current template
            $templateJavascripts = sprintf('themes.%s_%s.javascripts', $themeName, $templateName);
            $baseJsAssets = ($this->container->hasParameter($templateJavascripts)) ? $this->container->getParameter($templateJavascripts) : array();
            $outputFileName = sprintf('%s_javascripts.html.twig', $templateName);
            if(!file_exists($outputFileName))
            {
                $this->writeTwigAssetsFile($outputFileName, 'javascripts_skeleton', array($baseJsAssets), array('?yui_js'));
            }
            
            // Writes the stylesheets template for the current language and page when needed
            $customAssets = $pageTree->getExternalStylesheets();
            if(!empty($customAssets))
            {
                $outputFileName = sprintf('%s_%s_stylesheets.html.twig', $languageName, $pageName);
                $this->writeTwigAssetsFile($outputFileName, 'stylesheets_skeleton', array($baseCssAssets, $customAssets), array('?yui_css', 'cssrewrite'));
            }
            
            // Writes the javascripts template for the current language and page when needed
            $customAssets = $pageTree->getExternalJavascripts(); 
            if(!empty($customAssets))
            {
                $outputFileName = sprintf('%s_%s_javascripts.html.twig', $languageName, $pageName);            
                $this->writeTwigAssetsFile($outputFileName, 'javascripts_skeleton', array($baseJsAssets, $customAssets), array('?yui_js'));
            }
        }
    }

    /**
     * Writes the twig assets file, using a twig skeleton model passed as reference
     * 
     * @param string    $outputFileName     The output file name
     * @param string    $skeleton           The skeleton file to use
     * @param array     $assets             The assets to write
     * @param array     $filters            The filters to use
     */
    protected function writeTwigAssetsFile($outputFileName, $skeleton, array $assets, array $filters)
    {
        $assetsBuilder = $this->container->get('al_assets_builder');
        $assetsBuilder->setOutputBundle($this->deployBundle);
        if(null !== $this->twigAssetsDir) $assetsBuilder->setOutputFolder($this->twigAssetsDir);
        
        foreach($assets as $asset)
        {
            $assetsBuilder->addAssets($asset);
        }
        $assetsBuilder->writeAssetFile($skeleton, $outputFileName, $filters);
        $assetsBuilder->cleanAssets();
    }

    /**
     * Create the XmlPage object for the given page and language
     * 
     * @param AlLanguage    $alLanguage     The AlLanguage object
     * @param AlPage        $alPage         The AlPage object
     * @param AlTheme       $alTheme        The AlTheme object
     * 
     * @return AlXmlPage 
     */
    protected function createPageTree(AlLanguage $alLanguage, AlPage $alPage)
    {
        $pageTree = new AlPageTree($this->container);
        $pageTree->setup($alLanguage, $alPage);
        
        return $pageTree;
    }

    /**
     * Saves the page
     * 
     * @param AlPage        $alPage         The AlPage object
     * @param AlLanguage    $alLanguage     The AlLanguage object
     * @param AlTheme       $alTheme        The AlTheme object
     * @param AlXmlPage     $pageTree        The pageTree to save (Optional)
     */
    protected function savePage(AlPage $alPage, AlLanguage $alLanguage, AlTheme $alTheme, AlPageTree $pageTree = null)
    {
        if(null === $pageTree)
        {
            $pageTree = $this->createPageTree($alLanguage, $alPage);
        }

        $pageTree->setThemeName($alTheme->getThemeName());
        $pageTree->setTemplateName($alPage->getTemplateName());

        $seoAttributes = array();
        $attributes = AlPageAttributeQuery::create()->setContainer($this->container)->fromPageAndLanguage($alPage->getId(), $alLanguage->getId())->findOne(); //fromPageIdWithLanguages($alPage->getId())->find();
        if(null !== $attributes)
        {
            $pageTree->setMetaTitle($attributes->getMetaTitle());
            $pageTree->setMetaDescription($attributes->getMetaDescription());
            $pageTree->setMetaKeywords($attributes->getMetaKeywords());
        }
        
        $this->save($pageTree);
        
        $this->basePages[$alPage->getId()] = $pageTree;        
    }
}
