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
 * AlMediaBuilder
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlMediaBuilder
{
    protected $container = null;
    protected $media = null;
    protected $options = array();
    protected $src = "";
    protected $type = null;

    public function __construct($container, $media, $options = array())
    {
        $this->container = $container;
        $this->src = $media;
        $this->options = $options;

        $this->setMediaType($this->src);
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSrc()
    {
        return $this->src;
    }

    public function getMedia()
    {
        return $this->media;
    }

    public function createMedia()
    {
        if(null === $this->type || empty($this->type))
        {
            return null;
        }

        $className = '\AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Media\AlMedia' . $this->type;
        if (!class_exists($className)) 
        {
            throw new \RuntimeException($this->container->get('translator')->trans('You must implement a class named %className% to manage the media type %type%', array('%className%' => $className, '%type%' => $this->type)));
        }
        
        $this->media = new $className($this->container, $this->src, $this->options);
    }

    protected function setMediaType()
    {
        if(!$this->container->get('al_page_tree')->isCmsMode())
        {
            $file = \sprintf('%s/%s/%s', $this->container->getParameter('al.deploy_bundle_assets_base_dir'), $this->container->getParameter('al.deploy_bundle_media_folder'), $this->src);
            $file = AlToolkit::locateResource($this->container, $file);
        }
        else
        {
            $bundleFolder = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alcms.web_folder_name') . '/' . AlToolkit::retrieveBundleWebFolder($this->container, 'AlphaLemonCmsBundle');
            $file = $bundleFolder . '/' . $this->container->getParameter('alcms.upload_assets_dir') . '/' . $this->container->getParameter('al.deploy_bundle_media_folder') . '/' . $this->src;
            
        }
        $file = AlToolkit::normalizePath($file);
        
        if(is_file($file))
        {
            $type = AlToolkit::mimeContentType($file);
            $defaultType = $this->container->getParameter('al_media_type');
            $this->type = (array_key_exists($type, $defaultType)) ? $defaultType[$type] : "Generic";
        }
    }
}

