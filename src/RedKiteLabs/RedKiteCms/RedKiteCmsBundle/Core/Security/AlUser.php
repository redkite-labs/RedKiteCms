<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Security;

use Symfony\Component\Security\Core\User\UserInterface;

//use AlphaLemon\AlphaLemonCmsBundle\Model\AlUser as ModelUser;

class AlUser extends BaseUser implements UserInterface
{
    /**
     * The model user
     *
     * @var \AlphaLemon\AlphaLemonCmsBundle\Model\AlUser
     */
    /*
    private $user;

    public function __construct(ModelUser $user = null)
    {
        $this->user = $user;
    }*/

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return array($this->getUser()->getAlRole()->getRole());
    }
    
    /**
     * {@inheritDoc}
     */
    public function eraseCredentials()
    {
    }
}
