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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener;

use Symfony\Component\HttpFoundation\Request;
use AlRequestCore\Listener\AlRequestListener;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlRoleQuery;

/**
 * Sets up AlphaLemon CMS
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlCmsSetupListener
{
    protected $container;
    
    /**
     * Contructor
     * 
     * @param ContainerInterface $container 
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
    * Sets up the required folder when doesn't exist and initialize the page tree object
    *
    * @param Event $event
    */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $kernel = $this->container->get('kernel');
        if(strpos($kernel->getEnvironment(), 'alcms') === false)
        {
            return;
        }
        
        if(AlToolkit::locateResource($this->container, $this->container->getParameter('alcms.deploy.xliff_skeleton'), true) === false)
        {
            throw new \Symfony\Component\Form\Exception\InvalidConfigurationException("The parameter xliff_skeleton is not configured well. Check your configuration file");
        }

        if(AlToolkit::locateResource($this->container, $this->container->getParameter('alcms.deploy.xml_skeleton'), true) === false)
        {
            throw new \Symfony\Component\Form\Exception\InvalidConfigurationException("The parameter xml_skeleton is not configured well. Check your configuration file");
        }

        if(AlToolkit::locateResource($this->container, $this->container->getParameter('alcms.assets.skeletons_folder'), true) === false)
        {
            throw new \Symfony\Component\Form\Exception\InvalidConfigurationException("The parameter skeletons_folder is not configured well. Check your configuration file");
        }
        
        $basePath = AlToolkit::locateResource($this->container, $this->container->getParameter('al.deploy_bundle_assets_base_dir'));
        $folders[] = $basePath . '/' . $this->container->getParameter('al.deploy_bundle_media_folder');
        $folders[] = $basePath . '/' . $this->container->getParameter('al.deploy_bundle_js_folder');
        $folders[] = $basePath . '/' . $this->container->getParameter('al.deploy_bundle_css_folder');
        
        $basePath = AlToolkit::locateResource($this->container,  '@AlphaLemonCmsBundle') . 'Resources/public/' . $this->container->getParameter('alcms.upload_assets_dir');   
        $folders[] = $basePath;
        $folders[] = $basePath . '/' . $this->container->getParameter('al.deploy_bundle_media_folder');
        $folders[] = $basePath . '/' . $this->container->getParameter('al.deploy_bundle_js_folder');
        $folders[] = $basePath . '/' . $this->container->getParameter('al.deploy_bundle_css_folder');
                
        $fs = new Filesystem();
        $fs->mkdir($folders);
        
        $pageTree = $this->container->get('al_page_tree');
        $pageTree->setup();
    }
}

