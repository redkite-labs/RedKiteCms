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

namespace AlphaLemon\AlphaLemonCmsBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use AlphaLemon\AlphaLemonCmsBundle\Core\ImageThumbnailer\ImageThumbnailer;


/**
 * Adds the renderSlot function to Twig engine
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class ImageThumbnailExtension extends \Twig_Extension
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function thumbnail($image, $targetWidth = 100, $targetHeight = 100)
    {
        $imagePath = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alcms.web_folder_name') . $image;
        $thumbnailer = new \AlphaLemon\AlphaLemonCmsBundle\Core\ImageThumbnailer\ImageThumbnailer($imagePath, $targetWidth, $targetHeight);
        $thumbnailer->create();
        $size = getimagesize($thumbnailer->getThumbnailImage());
        
        return sprintf('<img src="%s" width="%s" height="%s" rel="%s" />', dirname($image) .  '/' . $thumbnailer->getThumbnailFolder() . '/' . basename($thumbnailer->getThumbnailImage()), $size[0], $size[1], $image);
        /*
        $thumbnailsFolder = '.thumbnails';
        $imagePath = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alcms.web_folder_name') . $image;
        
        $imagine = new Imagine();
        $targetPath = dirname($imagePath) . '/' . $thumbnailsFolder . '/';
        
        if(!is_dir($targetPath))
        {
            $filesystem = new Filesystem();
            $filesystem->mkdir($targetPath);
        }
        
        $targetImage = $targetPath . md5($imagePath) . '.jpg';
        $transformation = new Transformation();
        $transformation->thumbnail(new Box($targetWidth, $targetHeight));
        $transformation->apply($imagine->open($imagePath))
                ->save($targetImage);
        
        $size = getimagesize($targetImage);
        
        return sprintf('<img src="%s" width="" height="" />', dirname($image) .  '/' . $thumbnailsFolder . '/' . basename($targetImage), $size[0], $size[1]);
        */
    
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'thumbnail' => new \Twig_Function_Method($this, 'thumbnail', array(
                'is_safe' => array('html'),
            )),
        );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'images';
    }
}
