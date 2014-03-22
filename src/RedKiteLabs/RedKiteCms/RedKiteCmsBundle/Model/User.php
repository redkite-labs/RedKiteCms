<?php

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\om\BaseUser;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends BaseUser implements UserInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function getRoles()
    {
        return array($this->getRole()->getRole());
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
    }
}
