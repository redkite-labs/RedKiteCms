<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AlRoleType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        //$builder->add('role'); 
        /*
        $builder->add('al_user_roles', 'model', array(
            'class' => 'AlphaLemon\AlphaLemonCmsBundle\Model\AlUserRole',
            'multiple' => true,
        ));*/
        
        $builder->add('al_user_roles', 'model', array(
            'class' => 'AlphaLemon\AlphaLemonCmsBundle\Model\AlUserRole',
            'multiple' => true,
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'AlphaLemon\AlphaLemonCmsBundle\Model\AlRole',
        );
    }

    public function getName()
    {
        return 'al_role';
    }
}
