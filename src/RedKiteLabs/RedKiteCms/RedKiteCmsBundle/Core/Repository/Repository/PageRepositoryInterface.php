<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository;

use RedKiteLabs\RedKiteCmsBundle\Model\AlPage;

/**
 * Defines the methods used to fetch page records
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface PageRepositoryInterface
{
    /**
     * Fetches a page record using its primary key
     *
     * @param  int         $id The primary key
     * @return AlPage|null A page instance
     */
    public function fromPK($id);

    /**
     *  Fetches all the active pages
     *
     *  @return \Iterator|AlPage[] A collection of pages
     */
    public function activePages();

    /**
     * Fetches a page record from its name
     *
     * @param  string      $pageName The page name
     * @return AlPage|null A page instance
     */
    public function fromPageName($pageName);

    /**
     * Fetches one or all page record from the assigned template.
     *
     * When the $once argument is true just the first record is fetched, otherwise
     * all are fetched
     *
     * @param  string             $templateName The page name
     * @param  boolean            $once         Indicated to only return the first object
     * @return \Iterator|AlPage[] A collection of pages or a page instance
     */
    public function fromTemplateName($templateName, $once = false);

    /**
     * Fetches all the templates used by the current theme.
     *
     * @return \Iterator|string[] A collection of template names
     */
    public function templatesInUse();

    /**
     * Fetches the site home page
     *
     * @return AlPage|null The fetched object
     */
    public function homePage();
}
