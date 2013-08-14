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
 * For extra documentation and help please visit http://www.alphalemon.com
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
 * Bootstraps AlphaLemon CMS
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
        $this->pageTree = $this->container->get('alpha_lemon_cms.page_tree');
    }

    /**
     * Listen to onKernelRequest to check and configure AlphaLemon CMS
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
    }

    private function setUpRequiredFolders()
    {
        $folders = array();
        $basePath = $this->locate($this->container->getParameter('red_kite_labs_theme_engine.deploy_bundle') . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.assets_base_dir'));
        $folders[] = $basePath . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.media_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.js_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.css_dir');

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

        $language = $this->pageTree->getAlLanguage();
        $page = $this->pageTree->getAlPage();
        $languageId = (null !== $language) ? $language->getId() : null;
        $pageId = (null !== $page) ? $page->getId() : null;

        $slotsAligner = $this->container->get('alpha_lemon_cms.repeated_slots_aligner');
        $slotsAligner
            ->setLanguageId($languageId)
            ->setPageId($pageId)
            ->align($template->getTemplateName(), $template->getSlots());
    }
}
