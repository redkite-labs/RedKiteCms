<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Tests\Unit\Core\Form;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\LanguagesMenu\LanguagesMenuType;

/**
 * LanguagesMenuTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class LanguagesMenuTypeTest extends AlBaseType
{
    protected function configureFields()
    {
        return array(
            array(
                'name' => 'flags_directories',
                'type' => 'choice',
                'options' => array(
                    'choices' => array(
                        "40x30" => "40x30", 
                        "20x15" => "20x15",
                    ),
                    'label' => 'navigation_languages_menu_flags',
                    'data' => '20x15',
                    'empty_value' => 'navigation_languages_menu_flags_folder',
                ),
            ),
            array(
                'name' => 'en',
                'type' => 'country',
                'options' => array(
                    'empty_value' => 'Choose the country', 
                    'data' => "",
                ),
            ),
            array(
                'name' => 'it',
                'type' => 'country',
                'options' => array(
                    'empty_value' => 'Choose the country', 
                    'data' => "IT",
                ),
            ),
        );
    }
    
    protected function getForm()
    {
        $flagsDirectory = array(
            "40x30" => "40x30", 
            "20x15" => "20x15",
        );
        $languages = array(
            "en" => array(
                "country" => "",
                "url" => "/rkcms_dev.php/backend/homepage"
            ),
            "it" => array(
                "country" => "it",
                "url" => "/rkcms_dev.php/backend/homepage"
            ),
        );
        
        return new LanguagesMenuType($flagsDirectory, $languages, "20x15");
    }
}