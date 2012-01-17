<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 * 
 */

namespace AlphaLemon\ThemeEngineBundle\Tests\Functional\AlphaLemon\ThemeEngineBundle\Core\Autoloader;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Autoloader\ThemesAutoloader;


class AlThemesAutoloaderTest extends TestCase 
{    
    public function testThemeBundlesAutoloaded()
    {
        $autoloader = new ThemesAutoloader();
        $this->assertNotEquals(0, count($autoloader->getBundles()));
    }
}