<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 */

namespace AlphaLemon\ThemeEngineBundle\Core\TemplateSlots;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\ThemeEngineBundle\Core\Exception\InvalidFixtureConfigurationException;
use AlphaLemon\ThemeEngineBundle\Core\Exception\InvalidTemplateNameException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This is the base class where the template's slots must be defined 
 *
 * @author AlphaLemon
 */
abstract class AlTemplateSlots
{
    protected $container;
    private $slots = array();    
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
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
     * Loads the fixtures for the given template
     * 
     * @param string $themeName     The theme name
     * @param string $templateName  The template name
     * @return array, null 
     */
    protected function loadFixtures($themeName, $templateName)
    {
        $fixturesFolder = AlToolkit::locateResource($this->container, $themeName) . 'Resources/fixtures';
        $fileName = $templateName . '.yml';
        if(is_dir($fixturesFolder) && is_file($fixturesFolder . '/' . $fileName))
        {
            $locator = new FileLocator($fixturesFolder);
            $defaultContents = Yaml::parse($locator->locate($fileName)); 
            
            return $defaultContents;
        }
        
        return null;
    }
    
    /**
     * Creates the template's slots
     * 
     * @param string $themeName     The theme name
     * @param string $templateName  The template name
     * @return array 
     */
    protected function setupSlots($themeName, $templateName)
    {
        preg_match('/[^a-z]/', $templateName, $matches);
        if(!empty($matches))
        {
            throw new InvalidTemplateNameException(sprintf('A template name must be made only by lower-case letters. Any other character is not valid. Please check your %s theme class.', get_class($this)));
        }
        
        $baseSlots = $this->retrieveSlotsFromFixtureFile($themeName, 'base');
        $templateSlots = $this->retrieveSlotsFromFixtureFile($themeName, $templateName);
        $fixturedSlots = array_merge($baseSlots, $templateSlots);
        
        $slots = array();
        foreach($fixturedSlots as $key => $params)
        {
            if('~' === $params) $params = null;
            $slots[$key] = new AlSlot($key, $params);
        }
        
        return $slots;
    }
    
    /**
     * Parses the fixture file and returns the slots as array
     * 
     * @param string $themeName     The theme name
     * @param string $templateName  The template name
     * @return array
     */
    private function retrieveSlotsFromFixtureFile($themeName, $templateName)
    {
        $slots = array();
        $repeatedSlots = $this->loadFixtures($themeName, $templateName);
        if(null !== $repeatedSlots) { 
            if(!array_key_exists('slots', $repeatedSlots)) {
                throw new InvalidFixtureConfigurationException(sprintf('The fixture file that defines the template slots must start with slots. Check your %s.yml file', $fileName));
            }
            else
            {
                $slots = $repeatedSlots['slots'];
            }
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