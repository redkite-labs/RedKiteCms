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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Form\Page;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlActiveTheme;

/**
 * Implements the form to manage the website pages
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class PagesForm extends AbstractType
{
    private $activeTheme;
    private $themes;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlActiveTheme                 $activeTheme
     * @param \RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection $themes
     */
    public function __construct(AlActiveTheme $activeTheme, AlThemesCollection $themes)
    {
        $this->activeTheme = $activeTheme;
        $this->themes = $themes;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pageName');
        $builder->add('template', 'choice', array('choices' => ChoiceValues::getTemplates($this->activeTheme, $this->themes)));
        $builder->add('isHome', 'checkbox');
        $builder->add('isPublished', 'checkbox');
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'RedKiteLabs\RedKiteCmsBundle\Core\Form\Page\Page',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pages';
    }
}
