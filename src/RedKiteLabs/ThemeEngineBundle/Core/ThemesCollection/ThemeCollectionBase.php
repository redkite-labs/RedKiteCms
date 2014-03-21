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

/**
 * Theme represents a theme and it is a collection of Template objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class ThemeCollectionBase implements \Iterator, \Countable
{
    /**
     * Normalizes the given key
     *
     * @param  string $key
     * @return string
     */
    protected function normalizeKey($key)
    {
        return strtolower(trim($key));
    }
}
