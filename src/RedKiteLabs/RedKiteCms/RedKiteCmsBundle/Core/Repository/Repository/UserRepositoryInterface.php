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

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\User;

/**
 * Defines the methods used to fetch user records
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface UserRepositoryInterface
{
    /**
     * Fetches an user record using its primary key
     *
     * @param  int    $id The primary key
     * @return User A user instance
     */
    public function fromPK($id);

    /**
     * Fetches an user record using its user name
     *
     * @param  string $userName The user name
     * @return User A user instance
     */
    public function fromUserName($userName);

    /**
     * Fetches the site's users
     *
     * @return \Iterator|User[] The fetched objects
     */
    public function activeUsers();

    /**
     * Fetches the users by a role
     *
     * @param  int                $roleId The role id
     * @return \Iterator|User[] The fetched objects
     */
    public function usersByRole($roleId);
}
