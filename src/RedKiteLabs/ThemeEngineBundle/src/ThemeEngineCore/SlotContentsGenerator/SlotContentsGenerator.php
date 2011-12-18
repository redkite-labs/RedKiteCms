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
 * 
 */

namespace ThemeEngineCore\SlotContentsGenerator; 
use Symfony\Component\Finder\Finder;

/**
 * SlotContentsGenerator generates the slotContents file for the given theme
 *
 * @author AlphaLemon
 */
class SlotContentsGenerator {
    
    protected $filePath;
    protected $themeName;
    protected $themesFolder;
    protected $contents = array();


    public function __construct($themeName, $themesFolder, $filePath) 
    {
        $this->filePath = $filePath;
        $this->themesFolder = $themesFolder;
        $this->themeName = $themeName;
    }
    
    public function generateSlotContentsFile()
    {
        $this->parseTemplateSlotClasses();
        $this->writeFile();
    }
    
    protected function parseTemplateSlotClasses()
    { 
        $finder = new Finder();
        $templates = $finder->depth(0)->files()->name('*Slots.php')->in(sprintf('%s/%s/src/Slots', $this->themesFolder, $this->themeName));   
        foreach($templates as $template)
        {
            $templateName = ucfirst(basename($template, '.php'));
            $templateSlotsClass = \sprintf('\Themes\%s\src\Slots\%s', $this->themeName, $templateName);
            $templateSlots = new $templateSlotsClass();
            foreach($templateSlots->toArray() as $repeatedStatus => $slotNames)
            {
                if($repeatedStatus != 'page')
                {
                    foreach($slotNames as $slotName)
                    {
                        if(!array_key_exists('slots', $this->contents) || !array_key_exists($slotName, $this->contents['slots'])) $this->contents['slots'][$slotName][] = $templateSlots->getTextFromSlot($slotName);
                    }
                }
                else
                {
                    foreach($slotNames as $slotName)
                    {
                        $this->contents[$templateName][$slotName][] = $templateSlots->getTextFromSlot($slotName);
                    }
                }
            }
        }
    }
    
    protected function generateFileContents()
    {
        $parsedContents = '';
        foreach($this->contents as $section => $slots)
        {
            $parsedContents .= str_replace("Slots", "", $section) . ":\n";
            foreach($slots as $slotName => $contents)
            {
                $parsedContents .= str_repeat(" ", 2) . $slotName . ":\n";
                foreach($contents as $key => $content)
                {
                    $slotContent = str_repeat(" ", 4) . $key . ": |\n"; 
                    $slotContent .= str_repeat(" ", 6) . preg_replace('/[\n\r\t]+/', '', $content) . "\n\n";
                    $parsedContents .= $slotContent;
                }
            }
        }
        
        return $parsedContents;
    }
    
    protected function writeFile()
    {
        if(!is_dir($this->filePath))
        {
            throw new \InvalidArgumentException(sprintf('The directory %s does not exist. Please check that the slot_contents_dir is properly configured', $slotContentsPath));
        }
        
        file_put_contents($this->filePath . '/slotContents.yml', $this->generateFileContents());
    }
}
