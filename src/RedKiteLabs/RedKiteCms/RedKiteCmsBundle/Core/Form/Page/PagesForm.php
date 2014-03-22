<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Page;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Base\BaseBlockType;
use Symfony\Component\Form\FormBuilderInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\ThemesCollection;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\ActiveThemeInterface;
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
     * @param ActiveThemeInterface $activeTheme
     * @param ThemesCollection     $themes
     */
    public function __construct(ActiveThemeInterface $activeTheme, ThemesCollection $themes)
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
            'data_class' => 'RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Page\Page',
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
