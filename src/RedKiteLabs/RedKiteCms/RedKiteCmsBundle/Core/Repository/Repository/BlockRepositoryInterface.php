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

use RedKiteLabs\RedKiteCmsBundle\Model\AlBlock;

/**
 * Defines the methods used to fetch block records
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface BlockRepositoryInterface
{
    /**
     * Fetches a block record using its primary key
     *
     * @param  int     $id The primary key
     * @return AlBlock The fetched object
     */
    public function fromPK($id);

    /**
     * Fetches the block records that belongs to the given language and page.
     * When the slotName param is given it is used as additional filter.
     *
     * @param  int                 $idLanguage The id of the language
     * @param  int                 $idPage     The id of the page
     * @param  string              $slotName   The slot name
     * @param  int                 $toDelete
     * @return \Iterator|AlBlock[] A collection of objects
     */
    public function retrieveContents($idLanguage, $idPage, $slotName = null, $toDelete = 0);

    /**
     * Fetches the block records that belongs the given language
     *
     * @param  int                 $languageId The id of the language
     * @return \Iterator|AlBlock[] A collection of objects
     */
    public function fromLanguageId($languageId);

    /**
     * Fetches the block records that belongs the given page
     *
     * @param  int                 $pageId The id of the page
     * @return \Iterator|AlBlock[] A collection of objects
     */
    public function fromPageId($pageId);

    /**
     * Fetches the block records from the given slot name, using the LIKE operator
     *
     * @param  string              $slotName The slot name
     * @param  int                 $toDelete
     * @return \Iterator|AlBlock[] A collection of objects
     */
    public function retrieveContentsBySlotName($slotName, $toDelete = 0);

    /**
     * Fetches the block records from using the Html Content
     *
     * @param  string              $search The search key
     * @return \Iterator|AlBlock[] A collection of objects
     */
    public function fromContent($search);

    /**
     * Fetches the block records from the class name
     *
     * @param  string              $className The class name to find
     * @param  string              $operation The operation to execute
     * @return \Iterator|AlBlock[] A collection of objects
     */
    public function fromType($className, $operation = 'find');

    /**
     * Deletes the blocks that belong the given language and page. When $remove argument
     * is true, blocks are removed from the database.
     *
     * @param string  $idLanguage
     * @param string  $idPage
     * @param boolean $remove
     */
    public function deleteBlocks($idLanguage, $idPage, $remove = false);
}
