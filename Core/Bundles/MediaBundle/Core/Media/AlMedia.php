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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Media;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

/**
 * AlMedia
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
abstract class AlMedia
{
    protected $skeleton = null;
    protected $src;
    protected $realSrcPath;
    protected $absoluteSrcPath;
    protected $options;
    protected $container;

    public function __construct($container, $src, $options = array())
    {
        if(null === $this->skeleton)
        {
            throw new \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(sprintf('%s class must implement the protected variable skeleton', \get_class($this)));
        }
        
        $this->container = $container;
        $this->src = $src;
        
        if(!$this->container->get('al_page_tree')->isCmsMode())
        {
            $file = \sprintf('%s/%s/%s', $this->container->getParameter('al.deploy_bundle_assets_base_dir'), $this->container->getParameter('al.deploy_bundle_media_folder'), $this->src);
            $bundleDir = AlToolkit::retrieveBundleWebFolder($this->container, $this->container->getParameter('al.deploy_bundle'));
            $this->absoluteSrcPath = sprintf('/%s/%s/%s', $bundleDir, $this->container->getParameter('al.deploy_bundle_media_folder'), $this->src);
        }
        else
        {
            $file = '@AlphaLemonCmsBundle/Resources/public/' . $this->container->getParameter('alcms.upload_assets_dir') . '/' . $this->container->getParameter('al.deploy_bundle_media_folder') . '/' . $this->src;
            $bundleDir = AlToolkit::retrieveBundleWebFolder($this->container, 'AlphaLemonCmsBundle');
            $this->absoluteSrcPath = sprintf('/%s/%s/%s', $bundleDir, $this->container->getParameter('alcms.upload_assets_dir') . '/' . $this->container->getParameter('al.deploy_bundle_media_folder'), $this->src);
        }
        
        $this->realSrcPath = AlToolkit::normalizePath($this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alcms.web_folder_name') . '/' . $this->absoluteSrcPath);

        $this->options = $options;
    }

    abstract public function render();
}