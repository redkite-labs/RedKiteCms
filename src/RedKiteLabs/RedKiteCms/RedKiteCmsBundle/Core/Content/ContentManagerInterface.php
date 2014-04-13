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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content;

/**
 * The interface used to describe a Content Manager object.
 *
 * RedKiteCms defines each entity releated to a web page as a Content, so blocks,
 * slots, pages, languages and templates are all Contents
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface ContentManagerInterface
{
    /**
     * Returns the managed object
     *
     * @return object
     */
    public function get();

    /**
     * Sets the object to be managed
     *
     * @param   A BaseObject instance
     */
    public function set($propelObject = null);

    /**
     * Implements the base method to add or edit the managed object
     *
     * @param   A BaseObject instance
     * @return boolean
     */
    public function save(array $parameters);

    /**
     * Implements the base method to delete the managed object
     *
     * @return boolean
     */
    public function delete();
}
