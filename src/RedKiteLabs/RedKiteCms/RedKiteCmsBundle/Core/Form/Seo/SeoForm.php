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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Form\Seo;

use Symfony\Component\Form\FormBuilderInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Base\BaseBlockType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Defines the page attributes form
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class SeoForm extends BaseBlockType
{
    private $languageRepository;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel $languageRepository
     */
    public function __construct(AlLanguageRepositoryPropel $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('idPage', 'hidden', array('label' => 'pages_controller_label_permalink'));
        $builder->add('idLanguage', 'choice', array('choices' => ChoiceValues::getLanguages($this->languageRepository)));
        $builder->add('permalink', 'textarea', array(
            'label' => 'pages_controller_label_permalink',
            'attr' => array(
                'rows' => '3', 
            ),));
        $builder->add('title', 'textarea', array(
            'label' => 'pages_controller_label_meta_title',
            'attr' => array(
                'title' => 'pages_controller_meta_title_explanation',
                'rows' => '3', 
            ),
        ));
        $builder->add('description', 'textarea', array(
            'label' => 'pages_controller_label_meta_description',
            'attr' => array(
                'title' => 'pages_controller_meta_description_explanation',
                'rows' => '3', 
            ),
        ));
        $builder->add('keywords', 'textarea', array(
            'label' => 'pages_controller_label_meta_keywords',
            'attr' => array(
                'title' => 'pages_controller_meta_keywords_explanation',
                'rows' => '2', 
            ),
        ));
        $builder->add('sitemapChangeFreq', 'choice', array(
            'choices' => array(
                '' => '-',
                'always' => 'always',
                'hourly' => 'hourly',
                'daily' => 'daily',
                'weekly' => 'weekly',
                'monthly' => 'monthly',
                'yearly' => 'yearly',
                'never' => 'never',
            ),
            'label' => 'pages_controller_label_change frequency',
        ));
        $builder->add('sitemapPriority', 'choice', array(
            'choices' => array(
                '0.0' => '0.0',
                '0.1' => '0.1',
                '0.2' => '0.2',
                '0.3' => '0.3',
                '0.4' => '0.4',
                '0.5' => '0.5',
                '0.6' => '0.6',
                '0.7' => '0.7',
                '0.8' => '0.8',
                '0.9' => '0.9',
                '1.0' => '1.0',
            ),
            'data' => '0.5',
            'label' => 'pages_controller_label_priority',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'data_class' => 'RedKiteLabs\RedKiteCmsBundle\Core\Form\Seo\Seo',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'seo_attributes';
    }
}
