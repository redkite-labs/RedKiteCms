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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository;

/**
 * Defines the methods used to fetch language records
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface LanguageRepositoryInterface
{
    /**
     * Fetches a language record using its primary key
     *
     * @param int       The primary key
     * @return object The fetched object
     */
    public function fromPK($id);

    /**
     * Fetches the main language record
     *
     * @return object The fetched object
     */
    public function mainLanguage();

    /**
     * Fetches a language record from its name
     *
     * @param string    The language name
     * @return object The fetched object
     */
    public function fromLanguageName($languageName);

    /**
     *  Fetches all the active languages
     *
     *  @return mixed A collection of objects
     */
    public function activeLanguages();

    /**
     * Fetches the first language record
     *
     * @return object The fetched object
     */
    public function firstOne();
}
