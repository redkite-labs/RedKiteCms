<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Form\Language;

use RedKiteLabs\RedKiteCmsBundle\Core\Form\Base\BaseBlockType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Implements the form to manage the website languages
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class LanguagesForm extends BaseBlockType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('language', 'language', array(
            'label' => 'languages_controller_label_language',
        ));
        $builder->add('isMain', 'checkbox', array(
            'label' => 'languages_controller_is_main_language',
            'attr' => array(
                'title' => 'languages_controller_is_main_language_explanation',
            ),      
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'data_class' => 'RedKiteLabs\RedKiteCmsBundle\Core\Form\Language\Language',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'languages';
    }
}
