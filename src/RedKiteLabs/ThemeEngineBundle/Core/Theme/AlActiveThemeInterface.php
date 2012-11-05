<?php
/**
 * This file is part of the AlphaLemonPageTreeBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace AlphaLemon\ThemeEngineBundle\Core\Theme;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defined the methods the active theme manager object must defin
 * 
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlActiveThemeInterface
{
    /**
     * Returns the active theme
     * @return null|string
     */
    public function getActiveTheme();

    /**
     * Writes the active theme
     * @param string $themeName
     */
    public function writeActiveTheme($themeName);
}