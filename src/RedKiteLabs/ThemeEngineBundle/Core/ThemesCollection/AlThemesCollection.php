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

namespace AlphaLemon\ThemeEngineBundle\Core\ThemesCollection;

use AlphaLemon\ThemeEngineBundle\Core\Theme\AlTheme;

/**
 * Collects the website themes
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlThemesCollection extends AlThemeCollectionBase
{
    private $themes = array();

    /**
     * Adds a theme to the collections
     *
     * @param AlTheme $theme
     */
    public function addTheme(AlTheme $theme)
    {
        $themeName = $this->normalizeKey($theme->getThemeName());

        $this->themes[$themeName] = $theme;
    }

    /**
     * Returns the template from its name
     *
     * @param string $name
     * @return AlTheme
     */
    public function getTheme($name)
    {
        $key = $this->normalizeKey($name);

        return (array_key_exists($key, $this->themes)) ? $this->themes[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->themes);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->themes);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->themes);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return reset($this->themes);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return (current($this->themes) !== false);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->themes);
    }
}