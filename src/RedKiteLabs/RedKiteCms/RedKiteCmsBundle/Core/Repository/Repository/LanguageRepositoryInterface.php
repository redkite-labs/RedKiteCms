<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Language;

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
     * @param  int        $id The primary key
     * @return Language The fetched object
     */
    public function fromPK($id);

    /**
     * Fetches the main language record
     *
     * @return Language|null The fetched object
     */
    public function mainLanguage();

    /**
     * Fetches a language record from its name
     *
     * @param  string     $languageName The language name
     * @return Language The fetched object
     */
    public function fromLanguageName($languageName);

    /**
     *  Fetches all the active languages
     *
     *  @return \Iterator|Language[] A collection of objects
     */
    public function activeLanguages();

    /**
     * Fetches the first language record
     *
     * @return \Iterator|Language[] The fetched object
     */
    public function firstOne();
}
