<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Security\Proxy;

use Symfony\Component\Security\Core\User\UserInterface;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlUser as ModelUser;

class AlUser implements UserInterface
{
    /**
     * The model user
     *
     * @var \AlphaLemon\AlphaLemonCmsBundle\Model\AlUser
     */
    private $user;

    public function __construct(ModelUser $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return  array();//return $this->getUser()->getRoles();
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {
        return $this->getUser()->getPassword();
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt()
    {
        return $this->getUser()->getSalt();
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        return $this->getUser()->getUsername();
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function equals(UserInterface $user)                                                                                                          {
        return $this->getUser()->equals($user);
    }

    /**
     * @return \Acme\SecuredBundle\Model\User
     */
    protected function getUser()
    {
        return $this->user;
    }
}