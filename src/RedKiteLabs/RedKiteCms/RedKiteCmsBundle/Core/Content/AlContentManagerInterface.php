<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content;

/**
 * The interface used to describe a Content Manager object.
 *
 * AlphaLemon CMS defines each entity releated to a web page as a Content, so blocks,
 * slots, pages, languages and templates are all Contents
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface  AlContentManagerInterface
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
