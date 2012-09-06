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

namespace AlphaLemon\AlphaLemonCmsBundle\Core;

use Symfony\Component\Finder\Finder;

final class CmsBundlesAutoloader
{
    public static function getBundles($loadInternalBundles = false)
    {
        $bundles = self::findBundles(__DIR__ . '/../../Themes', 'AlphaLemon\AlphaLemonCmsBundle\Themes');
        if($loadInternalBundles) $bundles = array_merge($bundles, self::findBundles(__DIR__ . '/../../Bundles', 'AlphaLemon\AlphaLemonCmsBundle\Bundles'));

        return $bundles;
    }

    private static function findBundles($pathToSeek, $nameSpace)
    {
        $bundles = array();
        $finder = new Finder();
        $internalByndles = $finder->directories()->depth(0)->directories()->in($pathToSeek);
        foreach ($internalByndles as $internalBundle) {
            $internalBundle = basename($internalBundle);
            $className = $nameSpace . "\\" . $internalBundle. "\\" . $internalBundle;
            $bundles[] = new $className();
        }

        return $bundles;
    }
}
