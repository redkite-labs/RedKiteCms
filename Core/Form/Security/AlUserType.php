<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AlUserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('username');
        $builder->add('password');
        $builder->add('email');  
        
        $builder->add('al_user_roles', 'collection', array(
            'type'          => new \AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security\AlUserRoleType(),
            'allow_add'     => true,
            'allow_delete'  => true,
            'by_reference'  => false,
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'AlphaLemon\AlphaLemonCmsBundle\Model\AlUser',
        );
    }

    public function getName()
    {
        return 'al_user';
    }
}
