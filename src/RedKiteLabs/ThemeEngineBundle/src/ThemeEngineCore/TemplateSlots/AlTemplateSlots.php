<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 */

namespace ThemeEngineCore\TemplateSlots;

/**
 * This is the base class where the template's slots must be defined 
 *
 * @author AlphaLemon
 */
abstract class AlTemplateSlots
{
    private $slots;
    private $defaultSlots = array('header' => 'page',
                                'content' => 'page',
                                'footer' => 'page',
                                'logo' => 'site',
                                'small_logo' => 'site',
                                'nav_menu' => 'language',
                                'nav_menu_1' => 'language',
                                'nav_menu_2' => 'language',
                                'nav_menu_3' => 'language',
                                'nav_menu_4' => 'language',
                                'left_sidebar' => 'page',
                                'middle_sidebar' => 'page',
                                'right_sidebar' => 'page',
                                'screenshots_box' => 'page',
                                'download_box' => 'page',
                                'social_box' => 'page',
                                'ads_box' => 'page',
                                'slogan_box' => 'page',
                                'search_box' => 'language',
                                'information' => 'page',
                                'license_box' => 'language',
                                'copyright_box' => 'language',
                                'friends_box' => 'language',
                                'rss_box' => 'page',
                                'sponsor_box' => 'site',
                                'stats_box' => 'site',
                                'alcopyright_box' => 'site',
        );

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadSlots();
    }

    /**
     * Implement this method to add / edit slots or leave it empty to inherit the predefined slots
     */
    public function configure(){ }

    /**
     * Return the slots
     * 
     * @return array
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * Returns the repeated content status for the required slot
     * 
     * @param   string   $slotName The slot name to retrieve
     * @return  string   The repeated slot status or null if a non existent slot is required
     */
    public function getRepeatedContentFromSlot($slotName)
    {
        if(!$this->checkSlotExists($slotName)) return null;
        
        return $this->slots[$slotName]->getRepeated();
    }

    /**
     * Returns the default text when a new content is added to the slot
     * 
     * @param   string   $slotName The slot name to retrieve
     * @return  string   The default text
     */
    public function getTextFromSlot($slotName)
    {
        if(!$this->checkSlotExists($slotName)) return null;
        
        return $this->slots[$slotName]->getDefaultText();
    }
    
    /**
     * Returns all the slots by repeated status
     * @return type 
     */
    public function toArray()
    {
        $slots = array();
        foreach($this->slots as $slot)
        {
            $slots[$slot->getRepeated()][] = $slot->getSlotName();
        }  
        
        return $slots;
    }
    
    /**
     * Checks if a slot exists
     * 
     * @param   string  $slotName   The slot name to check
     * @return  boolean 
     */
    private function checkSlotExists($slotName)
    {
        return (!array_key_exists($slotName, $this->slots)) ? false : true;
    }
    

    /**
     * Loads the slots
     */
    final private function loadSlots()
    {
        foreach($this->defaultSlots as $slotName => $repeatStatus)
        {   
            $this->slots[$slotName] = new AlSlot($slotName, array('repeated' => $repeatStatus));
        }

        $customSlots = $this->configure();
        if(is_array($customSlots)) $this->slots = \array_merge($this->slots, $customSlots);
    }
}