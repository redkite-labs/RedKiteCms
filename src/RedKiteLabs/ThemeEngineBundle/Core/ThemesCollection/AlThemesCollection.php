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

namespace RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection;

use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme;

/**
 * Collects the website themes
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
     * Return the current element
     * 
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @codeCoverageIgnore
     */
    public function current()
    {
        return current($this->themes);
    }

    /**
     * Return the key of the current element
     * 
     * @link http://php.net/manual/en/iterator.key.php
     * @return scalar scalar on success, or <b>NULL</b> on failure.
     * @codeCoverageIgnore
     */
    public function key()
    {
        return key($this->themes);
    }

    /**
     * Move forward to next element
     * 
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @codeCoverageIgnore
     */
    public function next()
    {
        return next($this->themes);
    }

    /**
     * Rewind the Iterator to the first element
     * 
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @codeCoverageIgnore
     */
    public function rewind()
    {
        return reset($this->themes);
    }

    /**
     * Checks if current position is valid
     * 
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * @codeCoverageIgnore
     */
    public function valid()
    {
        return (current($this->themes) !== false);
    }

    /**
     * Count elements of an object
     * 
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * @codeCoverageIgnore
     */
    public function count()
    {
        return count($this->themes);
    }
}