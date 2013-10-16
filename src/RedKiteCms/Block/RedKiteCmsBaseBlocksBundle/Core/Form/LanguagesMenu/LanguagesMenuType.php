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

use RedKiteLabs\RedKiteCmsBundle\Core\Form\JsonBlock\JsonBlockType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the languages menu editor form
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class LanguagesMenuType extends JsonBlockType
{
    protected $flagsDirectories;
    protected $languages;
    protected $flagsDirectory;
    
    public function __construct($flagsDirectories, $languages, $flagsDirectory)
    {
        $this->flagsDirectories = $flagsDirectories;
        $this->languages = $languages;
        $this->flagsDirectory = $flagsDirectory;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('flags_directories', 'choice', array(
            'choices' => $this->flagsDirectories, 
            'label' => 'Flags',
            'data' => $this->flagsDirectory,
            'empty_value' => 'Choose flags folder', 
        ));
        
        foreach($this->languages as $language => $values) {
            $country = strtoupper(basename($values["country"], '.png'));
            $builder->add($language, 'country', array(
                'empty_value' => 'Choose the country', 
                'data' => $country
            ));
        }
        
        $builder->add('save', 'submit', array('attr' => array('class' => 'al_editor_save btn btn-primary')));
    }
}