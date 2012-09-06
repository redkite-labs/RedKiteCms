<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AlUserRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('al_roles', 'model', array(
            'class' => 'AlphaLemon\AlphaLemonCmsBundle\Model\AlRole',
            'multiple' => true,
        ));
        /*
        $builder->add('al_user_roles', 'collection', array(
            'type'          => new \AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security\AlRoleType(),
            'allow_add'     => true,
            'allow_delete'  => true,
            'by_reference'  => false,
        ));

        $builder->add('al_user', 'collection', array(
            'type'          => new \AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security\AlUserType(),
            'allow_add'     => true,
            'allow_delete'  => true,
            'by_reference'  => false
        ));
        $builder->add('al_role');*/
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'AlphaLemon\AlphaLemonCmsBundle\Model\AlUserRole',
        );
    }

    public function getName()
    {
        return 'al_user_role';
    }
}
