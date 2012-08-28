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

use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate;
use AlphaLemon\ThemeEngineBundle\Core\Exception\InvalidArgumentException;

/**
 * AlTheme represents a theme and it is a collection of AlTemplate objects
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlThemeCollectionBase implements \Iterator, \Countable
{
    /**
     * Normalizes the given key
     *
     * @param string $key
     * @return string
     */
    protected function normalizeKey($key)
    {
        return strtolower($key);
    }
}