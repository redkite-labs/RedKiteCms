<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\AssetsPath;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AlAssetsPath provides the paths for common assets folders
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * @codeCoverageIgnore
 */
class AlAssetsPath
{
    /**
     * Returns the upload folder path
     *
     * @param  \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @return string
     */
    public static function getUploadFolder(ContainerInterface $container)
    {
        $request = $container->get('request');

        $baseUrl = dirname($request->getBaseUrl());
        $baseUrl = substr($baseUrl, 1);
        if ( ! empty($baseUrl)) {
            $baseUrl .= '/';
        }

        return $baseUrl . $container->getParameter('alpha_lemon_cms.upload_assets_dir');
    }

    /**
     * Returns the upload folder absolute path
     *
     * @param  \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @return string
     */
    public static function getAbsoluteUploadFolder(ContainerInterface $container)
    {
        $uploaderFolder = self::getUploadFolder($container);
        $uploaderFolder = (empty($uploaderFolder)) ? '/' : '/' . $uploaderFolder;

        return $uploaderFolder;
    }
}
