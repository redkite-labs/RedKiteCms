<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Listener\Cms;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAsset;

/**
 * Bootstraps RedKiteCms
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class CmsBootstrapListener
{
    private $container;
    private $kernel;
    private $pageTree;

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

    private function locate($asset, $message = null)
    {
        $asset = new AlAsset($this->kernel, $asset);
        $assetPath = $asset->getRealPath();

        return $assetPath;
    }

    private function setUpPageTree()
    {
        $this->pageTree->setUp();
    }

    private function checkTemplatesSlots()
    {
        $template = $this->pageTree->getTemplate();
        if (null === $template) {
            return;
        }

        $language = $this->pageTree->getAlLanguage();
        $page = $this->pageTree->getAlPage();
        $languageId = (null !== $language) ? $language->getId() : null;
        $pageId = (null !== $page) ? $page->getId() : null;

        $slotsAligner = $this->container->get('red_kite_cms.repeated_slots_aligner');
        $slotsAligner
            ->setLanguageId($languageId)
            ->setPageId($pageId)
            ->align($template->getTemplateName(), $template->getSlots());
    }
    
    private function setupBootstrapVersion()
    {
        $bootstrapVersion = $this->container->get('red_kite_cms.active_theme')->getThemeBootstrapVersion();
        $this->container->get('twig')->addGlobal('bootstrap_version', $bootstrapVersion);
    }
    
    private function setupConfiguration()
    {
        $configuration = $this->container->get('red_kite_cms.configuration');
        $this->container->get('twig')->addGlobal('cms_language', $configuration->read('language'));
    }
}
