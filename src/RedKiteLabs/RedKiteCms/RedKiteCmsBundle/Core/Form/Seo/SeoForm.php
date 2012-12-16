<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\Seo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel;

/**
 * Defines the page attributes form
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class SeoForm extends AbstractType
{
    private $languageRepository;

    /**
     * Constructor
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel $languageRepository
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
        $builder->add('idPage', 'hidden');
        $builder->add('idLanguage', 'choice', array('choices' => ChoiceValues::getLanguages($this->languageRepository)));
        $builder->add('permalink', 'textarea');
        $builder->add('title', 'textarea');
        $builder->add('description', 'textarea');
        $builder->add('keywords', 'textarea');
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'AlphaLemon\AlphaLemonCmsBundle\Core\Form\Seo\Seo',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'seo_attributes';
    }
}
