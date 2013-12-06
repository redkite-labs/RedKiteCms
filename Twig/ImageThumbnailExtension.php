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

namespace RedKiteLabs\RedKiteCmsBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Adds the renderSlot function to Twig engine
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ImageThumbnailExtension extends \Twig_Extension
{
    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Generates a thumbnail of the given image
     *
     * @param  string $image
     * @param  int    $targetWidth
     * @param  int    $targetHeight
     * @return string
     */
    public function thumbnail($image, $targetWidth = 100, $targetHeight = 100)
    {
        $imagePath = $this->container->getParameter('red_kite_cms.web_folder_full_path') . $image;
        if (is_file($imagePath)) {
            $thumbnailer = $this->container->get('red_kite_cms.images_thumbnailer');
            $thumbnailer->create($imagePath, $targetWidth, $targetHeight);

            return sprintf('<img src="%s" width="%s" height="%s" rel="%s" />', dirname($image) .  '/' . $thumbnailer->getThumbnailFolder() . '/' . $thumbnailer->getThumbnailImageName(), $thumbnailer->getThumbnailWidth(), $thumbnailer->getThumbnailHeight(), $image);
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
