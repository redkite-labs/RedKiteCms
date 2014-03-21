<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
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
 */

namespace RedKiteLabs\ThemeEngineBundle\Core\Theme;

use RedKiteLabs\ThemeEngineBundle\Core\Template\Template;

/**
 * ThemeInterface
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface ThemeInterface
{
    /**
     * Returns the current theme name
     *
     * @return string
     */
    public function getThemeName();

    /**
     *  Returns the templates added to the theme
     *
     *  @return array
     */
    public function getTemplates();

    /**
     * Checks if the given template exists for the current theme
     *
     * @param string  The name of the theme to retrieve
     * @return Boolean
     */
    public function hasTemplate($name);

    /**
     * Sets the template for the given key
     *
     * @param string  The name of the template to set
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Template\Template  The template to set
     * @return null | \RedKiteLabs\ThemeEngineBundle\Core\Template\Template
     */
    public function setTemplate($key, Template $template);

    /**
     * Returns the template from its name
     *
     * @param string  The name of the theme to retrieve
     * @return null | \RedKiteLabs\ThemeEngineBundle\Core\Template\Template
     */
    public function getTemplate($name);
}
