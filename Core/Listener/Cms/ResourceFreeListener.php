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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Cms;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;

/**
 * Bootstraps AlphaLemon CMS
 *
 * @author alphalemon <webmaster@alphalemon.com>
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
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->kernel = $container->get('kernel');
        $this->pageTree = $this->container->get('alpha_lemon_cms.page_tree');
    }

    /**
     * Listen to onKernelRequest to check and configure AlphaLemon CMS
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (strpos($this->kernel->getEnvironment(), 'alcms') === false) {
            return;
        }

        $this->setUpRequiredFolders();
        $this->setUpPageTree();
        $this->checkTemplatesSlots();
    }

    private function setUpRequiredFolders()
    {
        $folders = array();
        $basePath = $this->locate($this->container->getParameter('alpha_lemon_theme_engine.deploy_bundle') . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.assets_base_dir'));
        $folders[] = $basePath . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.media_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.js_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.css_dir');

        //$basePath = $this->locate('@AlphaLemonCmsBundle/Resources/public/' . $this->container->getParameter('alpha_lemon_cms.upload_assets_dir'));
        $basePath = $this->container->getParameter('alpha_lemon_cms.upload_assets_full_path');
        $folders[] = $basePath;
        $folders[] = $basePath . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.media_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.js_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.css_dir');

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

        $slotsAligner = $this->container->get('alpha_lemon_cms.repeated_slots_aligner');
        $slotsAligner->align($template->getThemeName(), $template->getTemplateName(), $template->getSlots());
    }
}
