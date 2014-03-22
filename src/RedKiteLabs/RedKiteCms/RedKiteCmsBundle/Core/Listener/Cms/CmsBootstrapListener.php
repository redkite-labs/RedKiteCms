<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Listener\Cms;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\Asset;

/**
 * Bootstraps RedKiteCms
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class CmsBootstrapListener
{
    /** @var ContainerInterface */
    private $container;
    /** @var \Symfony\Component\HttpKernel\KernelInterface */
    private $kernel;
    /** @var \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree */
    private $pageTree;
    /** @var \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\ActiveThemeInterface */
    private $activeTheme;

    /**
     * Contructor
     *
     * @param ContainerInterface $container
     *
     * @api
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->kernel = $container->get('kernel');
        $this->pageTree = $this->container->get('red_kite_cms.page_tree');
        $this->activeTheme = $this->container->get('red_kite_cms.active_theme');
        $this->theme = $this->activeTheme->getActiveTheme();
    }

    /**
     * Listen to onKernelRequest to check and configure RedKiteCms
     *
     * @param GetResponseEvent $event
     *
     * @api
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->setUpRequiredFolders();
        $this->setUpPageTree();
        $this->checkTemplatesSlots();
        $this->setupBootstrapVersion();
        $this->setupConfiguration();
    }

    private function setUpRequiredFolders()
    {
        $folders = array();
        $basePath = $this->locate($this->container->getParameter('red_kite_labs_theme_engine.deploy_bundle') . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.assets_base_dir'));
        $folders[] = $basePath . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.media_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.js_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.css_dir');

        $basePath = $this->container->getParameter('red_kite_cms.upload_assets_full_path');
        $folders[] = $basePath;
        $folders[] = $basePath . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.media_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.js_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.css_dir');

        $fs = new Filesystem();
        $fs->mkdir($folders);
    }

    private function locate($asset)
    {
        $asset = new Asset($this->kernel, $asset);
        $assetPath = $asset->getRealPath();

        return $assetPath;
    }

    private function setUpPageTree()
    {
        $dataManager = $this->container->get('red_kite_cms.data_manager');
        $dataManager->fromRequest($this->container->get('request'));

        $this->pageTree
            ->setDataManager($dataManager)
            ->setUp(
                $this->theme,
                $this->container->get('red_kite_cms.template_manager'),
                $this->container->get('red_kite_cms.page_blocks')
            )
        ;
    }

    private function checkTemplatesSlots()
    {
        $template = $this->pageTree->getTemplate();
        if (null === $template) {
            return;
        }

        $language = $this->pageTree->getLanguage();
        $page = $this->pageTree->getPage();
        $languageId = (null !== $language) ? $language->getId() : null;
        $pageId = (null !== $page) ? $page->getId() : null;

        $slotsAligner = $this->container->get('red_kite_cms.repeated_slots_aligner');
        $slotsAligner
            ->setLanguageId($languageId)
            ->setPageId($pageId)
            ->align($template, $this->theme->getThemeSlots()->getSlots());
    }

    private function setupBootstrapVersion()
    {
        $bootstrapVersion = $this->activeTheme->getThemeBootstrapVersion();
        $this->container->get('twig')->addGlobal('bootstrap_version', $bootstrapVersion);
    }

    private function setupConfiguration()
    {
        $configuration = $this->container->get('red_kite_cms.configuration');
        $this->container->get('twig')->addGlobal('cms_language', $configuration->read('language'));
    }
}
