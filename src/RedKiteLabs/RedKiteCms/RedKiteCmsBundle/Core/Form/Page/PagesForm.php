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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\Page;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

/**
 * Defines the pages form
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class PagesForm extends AbstractType
{
    private $_container;

    public function __construct($container)
    {
        $this->_container = $container;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $themesDir = AlToolkit::locateResource($this->_container, '@AlphaLemonThemeEngineBundle') . $this->_container->getParameter('althemes.base_dir');
        $builder->add('pageName');
        $builder->add('template', 'choice', array('choices' => ChoiceValues::getTemplates($themesDir)));
        $builder->add('isHome', 'checkbox');
        $builder->add('isPublished', 'checkbox');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'AlphaLemon\AlphaLemonCmsBundle\Core\Form\Page\Page',
        );
    }
    
    public function getName()
    {
        return 'pages';
    }
}