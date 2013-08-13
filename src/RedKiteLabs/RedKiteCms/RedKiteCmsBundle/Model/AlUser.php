<?php

namespace RedKiteLabs\RedKiteCmsBundle\Model;

use RedKiteLabs\RedKiteCmsBundle\Model\om\BaseAlUser;
use Symfony\Component\Security\Core\User\UserInterface;

class AlUser extends BaseAlUser implements UserInterface
{
    /**
     * {@inheritDoc}
     * 
     * @codeCoverageIgnore
     */
    public function getRoles()
    {
        return array($this->getAlRole()->getRole());
    }
    
    /**
     * {@inheritDoc}
     * 
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
    }
}
