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
 * Defines the methods used to fetch locked resources records
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface LockedResourceRepositoryInterface
{
    /**
     * Fetches a resource  record using its primary key
     *
     * @param string    The resource
     * @return object The fetched object
     */
    public function fromResourceName($resource);

    /**
     * Fetches a resource locked by an user
     *
     * @param int       The id ot the user
     * @param string    The resource
     * @return object The fetched object
     */
    public function fromResourceNameByUser($userId, $resource);

    /**
     * Deletes from the database the resource locked by an user
     *
     * @param   int     The id ot the user
     * @return int The affected records
     */
    public function freeUserResource($userId);

    /**
     * Deletes from the database a resource
     *
     * @param   string  The resource
     * @return int The affected records
     */
    public function freeLockedResource($resource);

    /**
     * Deletes from the database the resources older than the expired time
     *
     * @param   int     The timestamp
     * @return int The affected records
     */
    public function removeExpiredResources($expiredTime);

    /**
     * Fetches the active resources
     *
     * @param   int|null    The id ot the user or null for all the active resources
     * @return int The fetched resources
     */
    public function fetchResources($userId = null);
}
