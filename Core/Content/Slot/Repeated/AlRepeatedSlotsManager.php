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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\Finder\Finder;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;

/**
 * AlRepeatedSlotsManager is responsible to verify when a slot changes its repetition status and 
 * to update the contents to reflect it. This job is achieved saving the current status for each slot 
 * in an xml file, which is used when the comparison with the active slots status is made.
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlRepeatedSlotsManager extends AlContentManagerBase
{ 
    protected $activeThemeName;
    protected $themeSlotsFile;

    /**
     * Constructor 
     * @param ContainerInterface    $container
     * @param string                $activeThemeName  The active theme
     */
    public function __construct(ContainerInterface $container, $activeThemeName) 
    {
        parent::__construct($container);
        
        $this->activeThemeName = $activeThemeName; 
        $this->themeSlotsFile =  $container->getParameter('kernel.root_dir') . '/Resources/active_theme_slots.xml'; 
    }
    
    public function setActiveThemeSlotsFile($fileName)
    {
        $this->themeSlotsFile = $fileName;
    }


    /**
     * Compares the slots and updates the contents according the new status
     * 
     * @param   string  $templateName   The current template to check
     * @param   array   $savedSlots     The saved slots
     * 
     * @return  boolean or null when any update is made 
     */
    public function compareSlots($templateName, array $savedSlots, $doUpdate = false)
    {
        $activeSlots = $this->loadSavedSlots($templateName);
        if(null !== $activeSlots)
        {
            $currentSlots = $this->templateSlotsToArray($savedSlots);
            
            $diffCurrent = array_diff_assoc($currentSlots, $activeSlots); 
            if(empty($diffCurrent))
            {
                return null;
            }
            
            $diffActive = array_diff_assoc($activeSlots, $currentSlots);
            $changedSlots = array_intersect_key($diffCurrent, $diffActive);
            
            return $this->updateSlotStatus($changedSlots);
        }
        else 
        {
            // The xml file is made for the first time
            $this->saveSlots();               
            return true;
        }
    }
    
    /**
     * Updates the slot status for the given slots
     * 
     * @param   array   $changedSlots   The slots to update
     * @return  boolean
     */
    protected function updateSlotStatus(array $changedSlots)
    {
        try
        {
            $rollBack = false;
            $this->connection->beginTransaction();

            $pageTree = $this->container->get('al_page_tree');
            foreach($changedSlots as $slotName => $repeated)
            {
                $className = '\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterTo' . ucfirst(strtolower($repeated));
                $converter = new $className($this->container, new AlSlot($slotName), $pageTree->getAlPage(), $pageTree->getAlLanguage());
                $rollBack = !$converter->convert();
                if($rollBack) break;
            }

            if (!$rollBack)
            {
                $this->connection->commit();
                $this->saveSlots();
                
                return true;
            }
            else
            {
                $this->connection->rollBack();
                return false;
            }
        }
        catch(\Exception $e)
        {
            if(isset($this->connection) && $this->connection !== null) $this->connection->rollBack();
            throw $e;
        }
    }
    
    /**
     * Loads the saved slots from the xml file
     * 
     * @param   string  $templateName
     * @return  array 
     */
    protected function loadSavedSlots($templateName)
    {
        $templateName = strtolower($templateName);
        if(!is_file($this->themeSlotsFile))
        {
            return null;
        }
        
        $result = array();
        $xml = simplexml_load_file($this->themeSlotsFile);
        foreach($xml->templates->children() as $template)
        {
            if($template["name"] == $templateName)
            {
                foreach($template as $slot)
                {
                    $slotName = (string)$slot["name"];
                    $result[$slotName] = (string)$slot;
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Saves the active slots to the xml file
     */
    protected function saveSlots()
    {
        
        $slotClassesPath = AlToolkit::locateResource($this->container, $this->activeThemeName) . 'Core/Slots';
        $namespacePath = \sprintf('AlphaLemon\Theme\%s\Core\Slots', $this->activeThemeName); 
        
        $result = array();
        $finder = new Finder();
        $files = $finder->depth(0)->files()->in($slotClassesPath);
        foreach($files as $files)
        {
            $pathInfo = pathinfo($files);
            $className = $namespacePath . '\\' . $pathInfo["filename"];
            $slotManager = new $className($this->container);
            
            preg_match('/' . $this->activeThemeName . '(\w+)Slots/', $pathInfo["filename"], $match);
            $templateName = strtolower($match[1]);
            $result[$templateName] = $this->templateSlotsToArray($slotManager->getSlots());
        }
        
        $this->write($result); 
    }
    
    /**
     * Converts the slots to an array where the key is the slot name and the value is the repeated status
     * @param type $slots
     * @return type 
     */
    protected function templateSlotsToArray($slots)
    {
        $result = array();
        foreach($slots as $slotName => $slot)
        {
            $result[$slotName] = $slot->getRepeated();
        }
        
        return $result;
    }
    
    /**
     * Writes the xml file 
     * 
     * @param array     $themeSlots 
     */
    protected function write($themeSlots)
    {
        $skeletonContents = file_get_contents(AlToolkit::locateResource($this->container, '@AlphaLemonCmsBundle/Resources/data/xml/repeated-slots-skeleton.xml'));

        $xml = new \SimpleXMLElement($skeletonContents);
        foreach ($themeSlots as $className => $templateSlots)
        {
            $template = $xml->templates->addChild('template');
            $template->addAttribute('name', $className);
            foreach ($templateSlots as $name => $value)
            {
                $slot = $template->addChild('slot', $value);
                $slot->addAttribute('name', $name);
            }
        }
        
        $xml->asXML($this->themeSlotsFile);
    }
}