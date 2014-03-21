<?php

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\om\BaseAlUser;
use Symfony\Component\Security\Core\User\UserInterface;

class AlUser extends BaseAlUser implements UserInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function getRoles()
    {
        return array($this->getAlRole()->getRole());
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
