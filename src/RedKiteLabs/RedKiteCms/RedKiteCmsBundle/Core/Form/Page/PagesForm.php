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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Form\Page;

use RedKiteLabs\RedKiteCmsBundle\Core\Form\Base\BaseBlockType;
use Symfony\Component\Form\FormBuilderInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection;
use RedKiteLabs\RedKiteCmsBundle\Core\ActiveTheme\AlActiveThemeInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Implements the form to manage the website pages
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class PagesForm extends BaseBlockType
{
    private $activeTheme;
    private $themes;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\ActiveTheme\AlActiveThemeInterface   $activeTheme
     * @param \RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection $themes
     */
    public function __construct(AlActiveThemeInterface $activeTheme, AlThemesCollection $themes)
    {
        $this->activeTheme = $activeTheme;
        $this->themes = $themes;
    }

    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pageName', null, array(
            'label' => 'pages_controller_label_page_name',
        ));
        $builder->add('template', 'choice', array(
            'choices' => ChoiceValues::getTemplates($this->activeTheme, $this->themes),
            'label' => 'pages_controller_label_template',
        ));
        $builder->add('isHome', 'checkbox', array(
            'label' => 'pages_controller_label_home_page',
            'attr' => array(
                'title' => 'pages_controller_home_page_explanation',
            ),
        ));
        $builder->add('isPublished', 'checkbox', array(
            'label' => 'pages_controller_label_published',
        ));
    }

    /**
     * Sets the default options for this type
     *
     * @param OptionsResolverInterface $resolver The resolver for the options
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class' => 'RedKiteLabs\RedKiteCmsBundle\Core\Form\Page\Page',
        ));
    }

    /**
     * Returns the name of this type
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'pages';
    }
}
