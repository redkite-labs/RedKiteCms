<?php
/*
 * This file is part of the BusinessDropCapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\LanguagesMenu;

use Symfony\Component\Form\FormBuilderInterface;
use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\Base\BaseType;

/**
 * Defines the languages menu editor form
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class LanguagesMenuType extends BaseType
{
    protected $flagsDirectories;
    protected $languages;
    protected $currentFlagsDirectory;

    /**
     * Constructor
     *
     * @param array $flagsDirectories
     * @param array $languages
     * @param type  $currentFlagsDirectory
     */
    public function __construct(array $flagsDirectories, array $languages, $currentFlagsDirectory)
    {
        $this->flagsDirectories = $flagsDirectories;
        $this->languages = $languages;
        $this->currentFlagsDirectory = $currentFlagsDirectory;
    }

    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('flags_directories', 'choice', array(
            'choices' => $this->flagsDirectories,
            'label' => 'navigation_languages_menu_flags',
            'data' => $this->currentFlagsDirectory,
            'empty_value' => 'navigation_languages_menu_flags_folder',
        ));

        foreach ($this->languages as $language => $values) {
            $country = strtoupper(basename($values["country"], '.png'));
            $builder->add($language, 'country', array(
                'empty_value' => 'Choose the country',
                'data' => $country
            ));
        }

        parent::buildForm($builder, $options);
    }
}
