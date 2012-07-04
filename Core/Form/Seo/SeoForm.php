<?php
/*
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
use Symfony\Component\Form\FormBuilder;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel;

/**
 * Defines the page attributes form
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class SeoForm extends AbstractType
{
    private $languageRepository;

    public function __construct(AlLanguageRepositoryPropel $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('idPage', 'hidden');
        $builder->add('idLanguage', 'choice', array('choices' => ChoiceValues::getLanguages($this->languageRepository)));
        $builder->add('permalink');
        $builder->add('title');
        $builder->add('description', 'textarea'); //, array('row' => 10, 'col' => 5)
        $builder->add('keywords', 'textarea');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'AlphaLemon\AlphaLemonCmsBundle\Core\Form\Seo\Seo',
        );
    }

    public function getName()
    {
        return 'seo';
    }
}