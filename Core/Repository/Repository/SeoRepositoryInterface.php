<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository;

/**
 * Defines the methods used to fetch seo page attributes records
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface SeoRepositoryInterface
{
    /**
     * Fetches a seo record using its primary key
     *
     * @param int       The primary key
     * @return object The fetched object
     */
    public function fromPK($id);

    /**
     * Fetches the seo record found by its page and language ids
     *
     * @param int       The id of the language
     * @param int       The id of the page
     * @return object The fetched object
     */
    public function fromPageAndLanguage($languageId, $pageId);

    /**
     * Fetches the seo record found by its permalink
     *
     * @param string    The permalink
     * @return object The fetched object
     */
    public function fromPermalink($permalink);

    /**
     * Fetches the seo records found by its page
     *
     * @param int       The id of the page
     * @return mixed A collection of objects
     */
    public function fromPageId($pageId);

    /**
     * Fetches the seo records found by its language id
     *
     * @param int       The id of the language
     * @return mixed A collection of objects
     */
    public function fromLanguageId($languageId);

    /**
     * Fetches the seo records found by its page with the languages objects
     *
     * @param int       The id of the page
     * @return mixed A collection of objects
     */
    public function fromPageIdWithLanguages($pageId);

    /**
     * Fetches the seo records found by its page and languages with the
     * pages and languages objects
     *
     * @return mixed A collection of objects
     */
    public function fetchSeoAttributesWithPagesAndLanguages();

    /**
     * Fetches the seo records found by a language name
     *
     * @param string The name of the language
     * @param boolean When true orders by permalink
     * @return mixed A collection of objects
     */
    public function fromLanguageName($languageName, $ordered = true);
}
