<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Security\Provider;

use Propel\PropelBundle\Security\User\ModelUserProvider;

class AlCmsAlUserProvider extends ModelUserProvider
{
    public function __construct()
    {
        parent::__construct('AlphaLemon\AlphaLemonCmsBundle\Model\AlUser', 'AlphaLemon\AlphaLemonCmsBundle\Core\Security\Proxy\AlUser');
    }
}