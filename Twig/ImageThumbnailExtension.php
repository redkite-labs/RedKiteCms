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
use AlphaLemon\AlphaLemonCmsBundle\Core\ImageThumbnailer\AlImageThumbnailer;

/**
 * Adds the renderSlot function to Twig engine
 *
 * @author alphalemon <webmaster@alphalemon.com>
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
        $imagePath = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alpha_lemon_cms.web_folder') . $image;
        if (is_file($imagePath)) {
            $thumbnailer = new AlImageThumbnailer();
            $thumbnailer->create($imagePath, $targetWidth, $targetHeight);
            $size = getimagesize($thumbnailer->getThumbnailImage());

            return sprintf('<img src="%s" width="%s" height="%s" rel="%s" />', dirname($image) .  '/' . $thumbnailer->getThumbnailFolder() . '/' . basename($thumbnailer->getThumbnailImage()), $size[0], $size[1], $image);
        }
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
    public function getName()
    {
        return 'images';
    }
}
