<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme;

/**
 * Defined the methods the active theme manager object must defin
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface ActiveThemeInterface
{
    /**
     * Returns the theme bundle name active in the backend
     * @return null|\RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme
     */
    public function getActiveThemeBackend();

    /**
     * Returns the theme bundle name active in the frontend
     * @return null|\RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme
     */
    public function getActiveThemeFrontend();

    /**
     * Returns the theme bundle active in the backend
     * @return null|\RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme
     */
    public function getActiveThemeBackendBundle();

    /**
     * Returns the theme bundle active in the frontend
     * @return null|\RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme
     */
    public function getActiveThemeFrontendBundle();

    /**
     * Writes the active theme
     * @param string $themeName
     */
    public function writeActiveTheme($themeName);

    /**
     * Returns the bootstrap version used by the requested theme
     *
     * @param string $themeName
     */
    public function getThemeBootstrapVersion($themeName = null);
}
