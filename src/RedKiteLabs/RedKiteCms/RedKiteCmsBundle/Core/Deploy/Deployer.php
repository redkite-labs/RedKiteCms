<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Deploy;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\RoutingGenerator\RoutingGeneratorInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\SitemapGenerator\SitemapGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\PageTreeCollection;

/**
 * Deployer is the base object deputated to deploy the website from development to
 * production.
 *
 * Website is deployed inside the deploy bundle.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class Deployer implements DeployerInterface
{
    /** @var RoutingGeneratorInterface */
    protected $routingGenerator;
    /** @var SitemapGeneratorInterface  */
    protected $sitemapGenerator;
    /** @var null|EventDispatcherInterface  */
    protected $dispatcher = null;
    /** @var Filesystem */
    protected $fileSystem;
    /** @var null|PageTreeCollection  */
    protected $pageTreeCollection = null;

    /**
     * Save the page from an PageTree object
     *
     * @param  PageTree $pageTree
     * @param  Theme    $theme
     * @param  array      $options
     * @return boolean
     *
     * @api
     */
    abstract protected function save(PageTree $pageTree, Theme $theme, array $options);

    /**
     * Constructor
     *
     * @param RoutingGeneratorInterface     $routingGenerator
     * @param SitemapGeneratorInterface     $sitemapGenerator
     * @param EventDispatcherInterface|null $dispatcher
     *
     * @api
     */
    public function __construct(RoutingGeneratorInterface $routingGenerator, SitemapGeneratorInterface $sitemapGenerator = null, EventDispatcherInterface $dispatcher = null)
    {
        $this->routingGenerator = $routingGenerator;
        $this->sitemapGenerator = $sitemapGenerator;
        $this->dispatcher = $dispatcher;
        $this->fileSystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function deploy(PageTreeCollection $pageTreeCollection, Theme $theme, array $options)
    {
        $this->dispatch(Deploy\DeployEvents::BEFORE_DEPLOY, new Deploy\BeforeDeployEvent($this));

        $this->pageTreeCollection = $pageTreeCollection;
        $deployFolder = $options["deployDir"];
        $this->fileSystem->remove($deployFolder);
        $this->checkTargetFolders($options);

        $this->pageTreeCollection->fill();
        if ( ! $this->savePages($theme, $options)) {
            return false;
        }

        $this->copyAssets($options);
        $this->routingGenerator
            ->generateRouting($options["deployBundle"], $options["deployController"])
            ->writeRouting($options["configDir"])
        ;

        if (null !== $this->sitemapGenerator) {
            $this->sitemapGenerator->writeSiteMap($options["webFolderPath"], $options["websiteUrl"]);
        }

        $this->dispatch(Deploy\DeployEvents::AFTER_DEPLOY, new Deploy\AfterDeployEvent($this));

        return true;
    }

    /**
     * Checks if the mandatory folders for the pubblication exist and creates them
     * when required
     *
     * @param array $options An array of options
     */
    protected function checkTargetFolders(array $options)
    {
        $this->fileSystem->mkdir($options["assetsDir"]);
        $this->fileSystem->mkdir($options["configDir"]);
        $this->fileSystem->mkdir($options["deployDir"]);
    }

    /**
     * Saves the pages instantiating an PageTreeCollection object
     *
     * @param  \RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme $theme
     * @param  array                                             $options An array of options
     * @return boolean
     */
    protected function savePages(Theme $theme, array $options)
    {
        $pages = $this->pageTreeCollection->getPages();
        $basePages = $this->pageTreeCollection->getBasePages();

        $options["type"] = "Pages";
        if ( ! $this->doSavePages($pages, $theme, $options)) {
            return false;
        }

        $options["type"] = "Base";
        if ( ! $this->doSavePages($basePages, $theme, $options)) {
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
     *
     * @param array $options An array of options
     */
    protected function copyAssets(array $options)
    {
        $finder = new Finder();
        $folders = $finder->directories()->depth(0)->in($options["uploadAssetsFullPath"]);
        foreach ($folders as $folder) {
            $targetFolder = $options["assetsDir"] . '/' . basename($folder->getFileName());
            $this->fileSystem->remove($targetFolder);
            $this->fileSystem->mirror($folder , $targetFolder, null, array('override' => true));
        }
    }

    private function doSavePages($pages, Theme $theme, array $options)
    {
        foreach ($pages as $pageTree) {
            if ( ! $this->save($pageTree, $theme, $options)) {
                return false;
            }
        }

        return true;
    }

    private function dispatch($eventName, $event)
    {
        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch($eventName, $event);
        }
    }
}
