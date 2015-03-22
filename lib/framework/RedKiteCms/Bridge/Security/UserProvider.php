<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Bridge\Security;

use RedKiteCms\Configuration\SiteMatcher;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * This object is deputed to implement the RedKite CMS user provider
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Bridge\Security
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @type string
     */
    private $rootDir;
    /**
     * @type string
     */
    private $siteName;

    /**
     * Constructor
     *
     * @param $rootDir
     * @param $siteName
     */
    public function __construct($rootDir, $siteName)
    {
        $this->rootDir = $rootDir;
        $this->siteName = $siteName;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $json = FilesystemTools::readFile(sprintf('%s/app/data/%s/users/users.json', $this->rootDir, $this->siteName));
        $users = json_decode($json, true);

        if (array_key_exists($username, $users)) {
            $userData = $users[$username];

            return new User($username, $userData["password"], $userData["salt"], $userData["roles"]);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}