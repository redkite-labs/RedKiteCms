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

use Symfony\Component\HttpFoundation\Request;
use AlRequestCore\Listener\AlRequestListener;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterException;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlRoleQuery;

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
        $this->pageTree = $this->container->get('al_page_tree');
    }

    /**
     * Listen to onKernelRequest to check and configure AlphaLemon CMS
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if(strpos($this->kernel->getEnvironment(), 'alcms') === false)
        {
            return;
        }

/*
echo "Q";
        $factory = $this->container->get('alphalemon_cms.block_manager_factory');
        $bm = $this->container->get('block_model');
        $block = $factory->createBlock($bm, 'Text');//print_r($block->get());exit;
*/
        //print_R($this->container->get('alphalemon_cms.block_manager_factory')->createBlock($this->container->get('block_model'), 'Text'));
        $this->checkConfigurationParameters();
        $this->setUpRequiredFolders();
        $this->setUpPageTree();
        $this->checkTemplatesSlots();
    }

    private function checkConfigurationParameters()
    {
        if(false === AlToolkit::locateResource($this->kernel, $this->container->getParameter('alcms.deploy.xliff_skeleton'), true))
        {
            throw new InvalidParameterException("The parameter xliff_skeleton is not well configured. Check your configuration file to solve the problem");
        }

        if(false === AlToolkit::locateResource($this->kernel, $this->container->getParameter('alcms.deploy.xml_skeleton'), true))
        {
            throw new InvalidParameterException("The parameter xml_skeleton is not well configured. Check your configuration file to solve the problem");
        }

        if(false === AlToolkit::locateResource($this->kernel, $this->container->getParameter('alcms.assets.skeletons_folder'), true))
        {
            throw new InvalidParameterException("The parameter skeletons_folder is not well configured. Check your configuration  file to solve the problem");
        }
    }

    private function setUpRequiredFolders()
    {
        $folders = array();
        $basePath = AlToolkit::locateResource($this->kernel, $this->container->getParameter('alphalemon_frontend.deploy_bundle')) . $this->container->getParameter('alphalemon_cms.deploy_bundle.assets_base_dir');
        $folders[] = $basePath . '/' . $this->container->getParameter('alphalemon_cms.deploy_bundle.media_folder');
        $folders[] = $basePath . '/' . $this->container->getParameter('alphalemon_cms.deploy_bundle.js_folder');
        $folders[] = $basePath . '/' . $this->container->getParameter('alphalemon_cms.deploy_bundle.css_folder');

        $basePath = AlToolkit::locateResource($this->kernel,  '@AlphaLemonCmsBundle') . 'Resources/public/' . $this->container->getParameter('alphalemon_cms.upload_assets_dir');
        $folders[] = $basePath;
        $folders[] = $basePath . '/' . $this->container->getParameter('alphalemon_cms.deploy_bundle.media_folder');
        $folders[] = $basePath . '/' . $this->container->getParameter('alphalemon_cms.deploy_bundle.js_folder');
        $folders[] = $basePath . '/' . $this->container->getParameter('alphalemon_cms.deploy_bundle.css_folder');

        $fs = new Filesystem();
        if(!$fs->mkdir($folders))
        {
            throw new \RuntimeException("An error has occoured during the creation of required folders");
        }
    }

    private function setUpPageTree()
    {
        $this->pageTree->setup();
    }

    private function checkTemplatesSlots()
    {
        $template = $this->pageTree->getTemplate();
        $slotsAligner = $this->container->get('repeated_slots_aligner');
        $slotsAligner->align($template->getThemeName(), $template->getTemplateName(), $template->getSlots());
    }
}

