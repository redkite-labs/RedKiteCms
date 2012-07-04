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
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlPageQuery;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlBlockQuery;
use AlphaLemon\ThemeEngineBundle\Core\Repository\AlThemeQuery;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\Finder\Finder;

use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterException;

/**
 * The object deputated to deploy the website from development (CMS) to production (the deploy bundle)
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlDeployer
{
    protected $container = null;
    protected $kernel = null;
    protected $deployBundle = null;
    protected $deployBundleAsset = null;
    protected $alphaLemonCmsBundleAsset = null;
    protected $configDir = null;
    protected $assetsDir = null;

    /**
     * Save the page from an AlPageTree object
     *
     * @param AlPageTree $pageTree
     * @return boolean
     */
    abstract protected function save(AlPageTree $pageTree);

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @throws InvalidParameterException
     */
    public function  __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->kernel = $this->container->get('kernel');
        $this->seoModel = $this->container->get('seo_model');
        $this->deployBundle = $this->container->getParameter('alphalemon_frontend.deploy_bundle');
        $this->deployBundleAsset = new AlAsset($this->kernel, $this->deployBundle);
        if(null === $this->deployBundleAsset->getWebFolderRealPath())
        {
            throw new InvalidParameterException(sprintf('The %s cannot be located. Check it is correctly enabled in your AppKernel class', $this->deployBundle));
        }
        $this->alphaLemonCmsBundleAsset = new AlAsset($this->kernel, 'AlphaLemonCmsBundle');

        $this->configDir = $this->deployBundleAsset->getRealPath() . '/' . $this->container->getParameter('alphalemon_cms.deploy_bundle.config_dir');
        $this->assetsDir = $this->deployBundleAsset->getRealPath()  . '/' . $this->container->getParameter('alphalemon_cms.deploy_bundle.assets_base_dir');

        $this->uploadAssetsDir = $this->container->getParameter('alphalemon_cms.upload_assets_dir');
        $this->cmsWebFolder = $this->container->getParameter('alphalemon_cms.web_folder');
    }

    /**
     * Deploys all the website's pages
     */
    public function deploy()
    {
        $this->checkTargetFolders();
        $this->copyAssets();
        return ($this->generateRoutes() && $this->savePages()) ? true :false;
    }

    /**
     * Checks if the publisher folders exist and creates them when required
     */
    protected function checkTargetFolders()
    {
        $this->checkFolder($this->configDir);
        $this->checkFolder($this->assetsDir);
    }

    /**
     * Checks that the given folder exists and creates it when it doesn't
     *
     * @param string $folder
     * @throws \RuntimeException
     */
    protected function checkFolder($folder)
    {
        $fileSystem = new Filesystem();

        if(!is_dir($folder))
        {
            if(!$fileSystem->mkdir($folder))
            {
                throw new \RuntimeException(sprintf('The %s directory cannot be created. Please check your permissions on that folder.', $folder));
            }
        }
    }

    /**
     * Saves the pages instantiating an AlPageTreeCollection object
     *
     * @return boolean
     */
    protected function savePages()
    {
        $pageTreeCollection = new AlPageTreeCollection($this->container);
        foreach ($pageTreeCollection as $pageTree) {
            if (!$this->save($pageTree))
            {
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
        $sourceDir = $this->alphaLemonCmsBundleAsset->getWebFolderRealPath($this->cmsWebFolder)  . '/' . $this->uploadAssetsDir;

        $fs = new Filesystem();
        $finder = new Finder();
        $folders = $finder->directories()->depth(0)->in($sourceDir);
        foreach($folders as $folder)
        {
            $targetFolder = $this->assetsDir . '/' . basename($folder->getFileName());
            $fs->remove($targetFolder);
            $fs->mirror($folder , $targetFolder, null, array('override' => true));
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
        $schema .= "  defaults: { _controller: $this->deployBundle:WebSite:show, _locale: %2\$s, page: %3\$s }";

        $homePage = "";
        $mainLanguage = "";
        $routes = array();
        $seoAttributes = $this->seoModel->fetchSeoAttributesWithPagesAndLanguages();
        foreach($seoAttributes as $seoAttribute)
        {
            $permalink = $seoAttribute->getPermalink();

            $pageName = $seoAttribute->getAlPage()->getPageName();
            if($seoAttribute->getAlPage()->getIsHome()) $homePage = $pageName;

            $language = $seoAttribute->getAlLanguage()->getLanguage();
            if($seoAttribute->getAlLanguage()->getMainLanguage()) $mainLanguage = $language;

            $routes[] = \sprintf($schema, $permalink, $language, $pageName, str_replace('-', '_', $language) . '_' . str_replace('-', '_', $pageName));
        }
        // Defines the main route
        $routes[] = \sprintf($schema, '', $mainLanguage, $homePage, 'home');

        return @file_put_contents($this->configDir . '/site_routing.yml', implode("\n\n", $routes));
    }
}
