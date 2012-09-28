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
 * Defines the methods used to fetch block records
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface BlockRepositoryInterface
{
    /**
     * Fetches a block record using its primary key
     *
     * @param int       The primary key
     * @return object The fetched object
     */
    public function fromPK($id);

    /**
     * Fetches the block records that belongs to the given language and page.
     * When the slotName param is given it is used as addictional filter.
     *
     * @param int       The id of the language
     * @param int       The id of the page
     * @param string    The slot name
     * @return object A collection of objects
     */
    public function retrieveContents($idLanguage, $idPage, $slotName = null);

    /**
     * Fetches the block records that belongs the given language
     *
     * @param int       The id of the language
     * @return object A collection of objects
     */
    public function fromLanguageId($languageId);

    /**
     * Fetches the block records that belongs the given page
     *
     * @param int       The id of the page
     * @return object A collection of objects
     */
    public function fromPageId($pageId);

    /**
     * Fetches the block records that belongs the given slot name
     *
     * @param string    The slot name
     * @return object A collection of objects
     */
    public function retrieveContentsBySlotName($slotName);

    /**
     * Fetches the block records from using the Html Content
     *
     * @param string    The search key
     * @return object A collection of objects
     */
    public function fromContent($search);

    /**
     * Fetches the block records from the class name
     *
     * @param string    The class name to find
     * @param string    The operation to execute
     * @return object A collection of objects
     */
    public function fromType($className, $operation = 'find');
}
