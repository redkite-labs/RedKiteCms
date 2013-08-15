<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Deploy;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAsset;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Deploy;
use RedKiteLabs\RedKiteCmsBundle\Core\AssetsPath\AlAssetsPath;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper;

/**
 * The object deputated to deploy the website from development, AlphaLemon CMS, to production,
 * the deploy bundle.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
    protected $viewsRenderer = null;
    protected $dispatcher = null;
    protected $credits = null;
    protected $activeTheme = null;
    protected $themesCollectionWrapper = null;
    private $webFolderPath = null;
    private $pageTreeCollection = null;

    /**
     * Save the page from an AlPageTree object
     *
     * @param  AlPageTree $pageTree
     * @return boolean
     *
     * @api
     */
    abstract protected function save(AlPageTree $pageTree, $type);

    /**
     * Returns the folder where the template files must be written
     *
     * @return string
     *
     * @api
     */
    abstract protected function getTemplatesFolder();

    /**
     * Returns a prefix for routes
     *
     * @return string
     *
     * @api
     */
    abstract protected function getRoutesPrefix();

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
        $this->factoryRepository = $this->container->get('red_kite_cms.factory_repository');
        $this->deployBundle = $this->container->getParameter('red_kite_labs_theme_engine.deploy_bundle');
        $this->deployBundleAsset = new AlAsset($this->kernel, $this->deployBundle);

        $this->configDir = $this->deployBundleAsset->getRealPath() . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.config_dir');
        $this->assetsDir = $this->deployBundleAsset->getRealPath()  . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.assets_base_dir');

        $this->uploadAssetsFullPath = $this->container->getParameter('red_kite_cms.upload_assets_full_path');
        $this->uploadAssetsAbsolutePath = AlAssetsPath::getAbsoluteUploadFolder($this->container);

        $this->deployController = $this->container->getParameter('red_kite_cms.deploy_bundle.controller');
        $this->deployFolder = $this->getTemplatesFolder();
        $this->viewsRenderer = $this->container->get('red_kite_cms.view_renderer');
        $this->webFolderPath = $this->container->getParameter('red_kite_cms.web_folder_full_path');
        $this->dispatcher = $this->container->get('event_dispatcher');
        $this->credits = ($this->container->getParameter('red_kite_cms.love') == 'no') ? false : true;
        $this->activeTheme = $this->container->get('red_kite_labs_theme_engine.active_theme');
        $this->themesCollectionWrapper = $this->container->get('red_kite_cms.themes_collection_wrapper');
        
        $this->fileSystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function deploy()
    {
        $this->dispatcher->dispatch(Deploy\DeployEvents::BEFORE_DEPLOY, new Deploy\BeforeDeployEvent($this));

        $this->fileSystem->remove($this->deployFolder);
        $this->checkTargetFolders();
        $this->copyAssets();

        if (null === $this->pageTreeCollection) {
            $this->pageTreeCollection = new AlPageTreeCollection($this->container, $this->factoryRepository);
        }
        $result = ($this->savePages() && $this->generateRoutes()) ? true : false;

        if ($this->getRoutesPrefix() == "") {
            $this->generateSitemap();
        }

        $this->dispatcher->dispatch(Deploy\DeployEvents::AFTER_DEPLOY, new Deploy\AfterDeployEvent($this));

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
     * @param  \RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlPageTreeCollection $value
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlDeployer
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
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlPageTreeCollection
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
    }

    /**
     * Saves the pages instantiating an AlPageTreeCollection object
     *
     * @return boolean
     */
    protected function savePages()
    {
        if ( ! $this->doSavePages()) {
            return false;
        }

        if ( ! $this->doSaveBasePages()) {
            return false;
        }

        return true;
    }

    /**
     * Copies the assets from the development environment to the production one
     *
     * The source folder is the redkitecms's bundles web folder, to be sure to copy
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
        $prefix = $this->getRoutesPrefix();

        $controllerPrefix =  'show';
        $environmentPrefix =  '';
        if ( ! empty($prefix)) {
            $controllerPrefix =  $prefix;
            $environmentPrefix =  '_' . $prefix;
        }

        // Defines the  schema pattern
        $schema = "# Route << %1\$s >> generated for language << %2\$s >> and page << %3\$s >>\n";
        $schema .= "%6\$s_%4\$s:\n";
        $schema .= "  pattern: /%1\$s\n";
        $schema .= "  defaults: { _controller: $this->deployBundle:$this->deployController:%5\$s, _locale: %2\$s, page: %3\$s }";

        $homePage = "";
        $mainLanguage = "";
        $routes = array();
        foreach ($this->pageTreeCollection as $pageTree) {
            $alPage = $pageTree->getAlPage();

            // By default the AlPageTreeCollection excluded unpublished pages, but
            // another custom collection could not implements this control. For this
            // reason we'll check the page's published status here
            if ( ! $alPage->getIsPublished()) {
                // @codeCoverageIgnoreStart
                continue;
                // @codeCoverageIgnoreEnd
            }

            $pageName = $alPage->getPageName();
            if ($alPage->getIsHome()) {
                $homePage = $pageName;
            }

            $alLanguage = $pageTree->getAlLanguage();
            $language = $alLanguage->getLanguageName();
            if ($alLanguage->getMainLanguage()) {
                $mainLanguage = $language;
            }

            // Generate only a route for the home page $seoAttribute->getPermalink()
            $seo = $pageTree->getAlSeo();
            $permalink = ($homePage != $pageName || $mainLanguage != $language) ? $seo->getPermalink() : "";
            $routes[] = \sprintf($schema, $permalink, $language, $pageName, str_replace('-', '_', $language) . '_' . str_replace('-', '_', $pageName), $controllerPrefix, $environmentPrefix);
        }
        // Defines the main route
        $routes[] = \sprintf($schema, '', $mainLanguage, $homePage, 'home', $controllerPrefix, $prefix);

        return @file_put_contents(sprintf('%s/site_routing%s.yml', $this->configDir, $environmentPrefix), implode("\n\n", $routes));
    }

    protected function generateSiteMap()
    {
        $sitemap = array();
        foreach ($this->pageTreeCollection as $pageTree) {

            // By default the AlPageTreeCollection excluded unpublished pages, but
            // another custom collection could not implements this control. For this
            // reason we'll check the page's published status here
            if ( ! $pageTree->getAlPage()->getIsPublished()) {
                // @codeCoverageIgnoreStart
                continue;
                // @codeCoverageIgnoreEnd
            }

            $seo = $pageTree->getAlSeo();
            $sitemap[] = sprintf("<url>\n\t<loc>%s</loc>\n\t<changefreq>%s</changefreq>\n\t<priority>%s</priority>\n</url>", "http://alphalemon.com/" . $seo->getPermalink(), $seo->getSitemapChangefreq(), $seo->getSitemapPriority());
        }

        return @file_put_contents($this->webFolderPath . '/sitemap.xml', sprintf("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n%s\n</urlset>" , implode("\n", $sitemap)));
    }

    private function doSavePages()
    {
        foreach ($this->pageTreeCollection as $pageTree) {
            if ( ! $this->save($pageTree, 'Pages')) {
                // @codeCoverageIgnoreStart
                return false;
                // @codeCoverageIgnoreEnd
            }
        }

        return true;
    }

    private function doSaveBasePages()
    {
        $languageRepository = $this->factoryRepository->createRepository('Language');
        $languages = $languageRepository->activeLanguages();
        $blockRepository = $this->factoryRepository->createRepository('Block');
        
        $themeName = $this->activeTheme->getActiveTheme();
        //$this->themesCollectionWrapper = $this->container->get('red_kite_cms.themes_collection_wrapper');
        $templateManager = $this->themesCollectionWrapper->getTemplateManager();
        $templates = $this->themesCollectionWrapper->getTheme($themeName)->getTemplates();

        foreach ($languages as $language) {
            $blocks = $blockRepository->retrieveContents(array(1, $language->getId()), 1);
            foreach ($templates as $template) {
                $pageBlocks = new AlPageBlocks($this->factoryRepository);
                $pageBlocks->setAlBlocks($blocks);

                $templateManager = clone($templateManager);
                $templateManager
                    ->setTemplate($template)
                    ->setPageBlocks($pageBlocks)
                    ->refresh();
                $themesCollectionWrapper = new AlThemesCollectionWrapper(
                    $this->themesCollectionWrapper->getThemesCollection(),
                    $templateManager
                );
                $themesCollectionWrapper->assignTemplate($themeName, $template->getTemplateName());

                $pageTree = new AlPageTree(
                    $this->container,
                    $this->factoryRepository,
                    $themesCollectionWrapper
                );

                $pageTree
                    ->setAlLanguage($language)
                    ->setPageBlocks($pageBlocks)
                ;
                if ( ! $this->save($pageTree, 'Base')) {
                    // @codeCoverageIgnoreStart
                    return false;
                    // @codeCoverageIgnoreEnd
                }
            }
        }

        return true;
    }
}
