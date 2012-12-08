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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Deploy;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Deploy;

/**
 * The object deputated to deploy the website from development, AlphaLemon CMS, to production, 
 * the deploy bundle.
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
abstract class AlDeployer implements AlDeployerInterface
{
    protected $container = null;
    protected $kernel = null;
    protected $deployBundle = null;
    protected $deployBundleAsset = null;
    protected $configDir = null;
    protected $assetsDir = null;
    protected $factoryRepository;
    protected $fileSystem = null;
    protected $deployController = null;
    protected $deployFolder = null;
    
    private $pageTreeCollection = null;

    /**
     * Save the page from an AlPageTree object
     *
     * @param  AlPageTree $pageTree
     * @return boolean
     * 
     * @api
     */
    abstract protected function save(AlPageTree $pageTree);

    /**
     * Constructor
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * 
     * @api
     */
    public function  __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->kernel = $this->container->get('kernel');
        $this->factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $this->seoRepository = $this->factoryRepository->createRepository('Seo');
        $this->deployBundle = $this->container->getParameter('alpha_lemon_theme_engine.deploy_bundle');
        $this->deployBundleAsset = new AlAsset($this->kernel, $this->deployBundle);

        $this->configDir = $this->deployBundleAsset->getRealPath() . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.config_dir');
        $this->assetsDir = $this->deployBundleAsset->getRealPath()  . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.assets_base_dir');

        $this->uploadAssetsFullPath = $this->container->getParameter('alpha_lemon_cms.upload_assets_full_path');
        $this->uploadAssetsAbsolutePath = $this->container->getParameter('alpha_lemon_cms.upload_assets_absolute_path');
        
        $this->deployController = $this->container->getParameter('alpha_lemon_cms.deploy_bundle.controller');
        $this->deployFolder = $this->container->getParameter('alpha_lemon_theme_engine.deploy.templates_folder');
        $this->fileSystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function deploy()
    {
        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch(Deploy\DeployEvents::BEFORE_LOCAL_DEPLOY, new Deploy\BeforeDeployEvent($this));
        
        $this->fileSystem->remove($this->deployFolder);
        $this->checkTargetFolders();
        $this->copyAssets();
        $result = ($this->generateRoutes() && $this->savePages()) ? true :false;
        
        $dispatcher->dispatch(Deploy\DeployEvents::AFTER_LOCAL_DEPLOY, new Deploy\AfterDeployEvent($this));
        
        return $result;
    }
    
    /**
     * Returns the real path of the deploy bundle
     * 
     * @return string
     * 
     * @api
     */
    public function getDeployBundleRealPath()
    {
        return $this->deployBundleAsset->getRealPath();
    }
    
    /**
     * Sets the pagetree collection
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlPageTreeCollection $value
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlDeployer
     * 
     * @api
     */
    public function setPageTreeCollection(AlPageTreeCollection $value)
    {
        $this->pageTreeCollection = $value;
        
        return $this;
    }
    
    /**
     * Fetches the current page tree collection
     * 
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlPageTreeCollection
     * 
     * @api
     */
    public function getPageTreeCollection()
    {
        return $this->pageTreeCollection;
    }

    /**
     * Checks if the publisher folders exist and creates them when required
     */
    protected function checkTargetFolders()
    {
        $this->fileSystem->mkdir($this->configDir);
        $this->fileSystem->mkdir($this->assetsDir);
        $this->fileSystem->mkdir($this->deployFolder);
    }

    /**
     * Saves the pages instantiating an AlPageTreeCollection object
     *
     * @return boolean
     */
    protected function savePages()
    {
        if (null === $this->pageTreeCollection) {
            $this->pageTreeCollection = new AlPageTreeCollection($this->container, $this->factoryRepository);
        }
        
        foreach ($this->pageTreeCollection as $pageTree) {
            if ( ! $this->save($pageTree)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Copies the assets from the development environment to the production one
     *
     * The source folder is the alphalemoncms's bundles web folder, to be sure to copy
     * everything when user is working with assets folders hardlinked, while the
     * target folder is the deploy bundle's Resources/public folder to be sure to
     * copy the assets under the sorce assets folder.
     */
    protected function copyAssets()
    {
        $finder = new Finder();
        $folders = $finder->directories()->depth(0)->in($this->uploadAssetsFullPath);
        foreach ($folders as $folder) {
            $targetFolder = $this->assetsDir . '/' . basename($folder->getFileName());
            $this->fileSystem->remove($targetFolder);
            $this->fileSystem->mirror($folder , $targetFolder, null, array('override' => true));
        }
    }

    /**
     * Generates a yml file with the routes defined by the website's pages, in the deploy bundle's Resources folder
     *
     * @return boolean
     */
    protected function generateRoutes()
    {
        // Defines the  schema pattern
        $schema = "# Route << %1\$s >> generated for language << %2\$s >> and page << %3\$s >>\n";
        $schema .= "_%4\$s:\n";
        $schema .= "  pattern: /%1\$s\n";
        $schema .= "  defaults: { _controller: $this->deployBundle:$this->deployController:show, _locale: %2\$s, page: %3\$s }";

        $homePage = "";
        $mainLanguage = "";
        $routes = array();
        $seoAttributes = $this->seoRepository->fetchSeoAttributesWithPagesAndLanguages();
        foreach ($seoAttributes as $seoAttribute) {
            
            $alPage = $seoAttribute->getAlPage();            
            if ( ! $alPage->getIsPublished()) {
                continue;
            }
            
            $pageName = $alPage->getPageName();
            if ($seoAttribute->getAlPage()->getIsHome()) {
                $homePage = $pageName;
            }

            $language = $seoAttribute->getAlLanguage()->getLanguageName();
            if ($seoAttribute->getAlLanguage()->getMainLanguage()) {
                $mainLanguage = $language;
            }

            // Generate only a route for the home page
            $permalink = ($homePage != $pageName || $mainLanguage != $language) ? $seoAttribute->getPermalink() : "";
            $routes[] = \sprintf($schema, $permalink, $language, $pageName, str_replace('-', '_', $language) . '_' . str_replace('-', '_', $pageName));
        }
        // Defines the main route
        $routes[] = \sprintf($schema, '', $mainLanguage, $homePage, 'home');

        return @file_put_contents($this->configDir . '/site_routing.yml', implode("\n\n", $routes));
    }
}