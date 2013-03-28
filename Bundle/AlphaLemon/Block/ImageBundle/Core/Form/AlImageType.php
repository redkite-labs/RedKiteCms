<?php

namespace AlphaLemon\Block\ImageBundle\Core\Form;

use AlphaLemon\AlphaLemonCmsBundle\Core\Form\JsonBlock\JsonBlockType;
use Symfony\Component\Form\FormBuilderInterface;

class AlImageType extends JsonBlockType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->add('src');
        $builder->add('data_src', 'hidden');
        $builder->add('title');
        $builder->add('alt');
    }
}
