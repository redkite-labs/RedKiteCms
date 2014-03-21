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

namespace RedKiteLabs\ThemeEngineBundle\Core\Rendering\Compiler\ThemeEngineListenersCollection;

/**
 * Collects the theme engine registered listeners for the red_kite_labs_theme_engine.event_listener
 * tag
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlThemeEngineListenersCollection implements \Iterator, \Countable
{
    private $listeners = array();

    /**
     * Adds the listener id to the collections
     *
     * @param string $listenerId
     */
    public function addListenerId($listenerId)
    {
        // @codeCoverageIgnoreStart
        if (null !== $this->getListenerId($listenerId)) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $this->listeners[$listenerId] = $listenerId;
    }

    /**
     * Returns the listener id
     *
     * @param  string      $listenerId
     * @return null|string
     */
    public function getListenerId($listenerId)
    {
        return (array_key_exists($listenerId, $this->listeners)) ? $this->listeners[$listenerId] : null;
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
        return current($this->listeners);
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
        return key($this->listeners);
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
        return next($this->listeners);
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
        return reset($this->listeners);
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
        return (current($this->listeners) !== false);
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
        return count($this->listeners);
    }
}
