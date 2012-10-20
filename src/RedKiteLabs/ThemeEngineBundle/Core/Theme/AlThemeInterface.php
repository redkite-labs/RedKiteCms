<?php
/*
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

use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate;

/**
 * AlThemeInterface
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlThemeInterface
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
     * @param \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate  The template to set
     * @return null | \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate
     */
    public function setTemplate($key, AlTemplate $template);

    /**
     * Returns the template from its name
     *
     * @param string  The name of the theme to retrieve
     * @return null | \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate
     */
    public function getTemplate($name);
}