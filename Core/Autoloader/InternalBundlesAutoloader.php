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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Autoloader;

use AlphaLemon\ThemeEngineBundle\Core\Autoloader\Base\BundlesAutoloader;

/**
 * Autoload the internal block editors bundles 
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class InternalBundlesAutoloader extends BundlesAutoloader
{
    protected function  configure()
    {
        return  array('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles' => __DIR__ . '/../Bundles',
                      'AlphaLemon\Block' => __DIR__ . '/../../../../../../src/AlphaLemon/Block',);
    }
}