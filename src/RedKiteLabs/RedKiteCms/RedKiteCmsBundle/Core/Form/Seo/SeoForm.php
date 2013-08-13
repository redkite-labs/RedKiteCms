<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Form\Seo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel;

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
        $builder->add('idPage', 'hidden');
        $builder->add('idLanguage', 'choice', array('choices' => ChoiceValues::getLanguages($this->languageRepository)));
        $builder->add('permalink', 'textarea');
        $builder->add('title', 'textarea');
        $builder->add('description', 'textarea');
        $builder->add('keywords', 'textarea');
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
            )
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
            'data' => '0.5'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'RedKiteLabs\RedKiteCmsBundle\Core\Form\Seo\Seo',
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
