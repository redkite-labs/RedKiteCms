<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository;

/**
 * Defines the methods used to fetch page records
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface PageRepositoryInterface
{
    /**
     * Fetches a page record using its primary key
     *
     * @param int       The primary key
     * @return object The fetched object
     */
    public function fromPK($id);

    /**
     *  Fetches all the active pages
     *
     *  @return mixed A collection of objects
     */
    public function activePages();

    /**
     * Fetches a page record from its name
     *
     * @param string    The page name
     * @return object The fetched object
     */
    public function fromPageName($pageName);

    /**
     * Fetches one or all page record from the assigned template.
     *
     * When the $once argument is true just the first record is fetched, otherwise
     * all are fetched
     *
     * @param string    The page name
     * @return object The fetched object
     */
    public function fromTemplateName($templateName, $once = false);

    /**
     * Fetches the site home page
     *
     * @return object The fetched object
     */
    public function homePage();
}
