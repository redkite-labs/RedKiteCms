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

/**
 * Description of AlAssetsPath
 *
 * @author alphalemon
 */
class AlAssetsPath {
    public static function getUploadFolder($container)
    {
        $request = $container->get('request');
        
        $baseUrl = dirname($request->getBaseUrl());
        $baseUrl = ($baseUrl != '/') ? $baseUrl . '/' : '';
        
        return $baseUrl . $container->getParameter('alpha_lemon_cms.upload_assets_dir');
    }
    
    public static function getAbsoluteUploadFolder($container)
    {
        $uploaderFolder = self::getUploadFolder($container);
        $uploaderFolder = (empty($uploaderFolder)) ? '/' : '/' . $uploaderFolder;
                
        return '/' . $container->getParameter('alpha_lemon_cms.web_folder') . $uploaderFolder;
    }
}