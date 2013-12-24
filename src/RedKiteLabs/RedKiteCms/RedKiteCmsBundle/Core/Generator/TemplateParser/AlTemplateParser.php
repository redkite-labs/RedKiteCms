<?php

namespace RedKiteLabs\RedKiteCmsBundle\Core\Generator\TemplateParser;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * AlTemplateParser parses the twig templates from a given folder to look for
 * the information that defines the slot's attributes
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlTemplateParser
{
    private $templatesDir;
    private $kernelDir;
    private $themeName;
    private $ymlParser;

    /**
     * Constructor
     *
     * @param string $templatesDir
     */
    public function __construct($templateLocator, $nameParser, $templatesDir, $kernelDir, $themeName)
    {
        
        $this->templateLocator = $templateLocator;
        $this->nameParser = $nameParser;
        
        $this->templatesDir = $templatesDir;
        $this->kernelDir = $kernelDir;
        $this->themeName = $themeName;
        $this->ymlParser = new Yaml();
    }

    /**
     * Parses the templates folder and returns the retrieved information
     * as array
     *
     * @return array
     */
    public function parse()
    {
        $directories = $this->initDirectories();
        
        $slotsDirectory = dirname($this->templatesDir) . '/Slots';
        $slots = $this->parseSlots($slotsDirectory);
        $finder = new Finder();
        $templateFiles = $finder->files('*.twig')->in($directories);

        $templates = array();
        //$slots = $this->findTemplateSlots($templateFiles);print_r($slots);exit;
        foreach ($templateFiles as $template) {
            $template = (string) $template;
            $templateName = basename($template);
            $templateContents = file_get_contents($template);
            $templateSlots = $this->parseBlocks($templateContents, array_keys($slots));
            //$templateSlots = $this->fetchSlots($templateContents);
            //$slots = array_merge($templateSlots, $slots);
            if (strpos($template, $this->kernelDir) === false && dirname($template) == $this->templatesDir) {
                $templates[] = array(
                    'name' => $templateName,
                    'slots' => $templateSlots,
                );
            }
            
            /*
            $currentSlots = array();
            do {
                $currentTemplateSlots = $slots[$currentTemplateName]["slots"];
                if (null === $currentTemplateSlots) {
                    $currentTemplateSlots = array();
                }
                $currentSlots = array_merge($currentSlots, $currentTemplateSlots, $slotBlocks);
                $currentTemplateName = $slots[$currentTemplateName]["extends"];

            } while ($currentTemplateName != null);

            if (strpos($template, $this->kernelDir) === false && dirname($template) == $this->templatesDir && ! (empty($currentSlots))) {
                $templates[$templateName]['slots'] = $currentSlots;
            }*/
        }
        
        return array(
            "templates" => $templates,
            "slots" => $slots,
        );
    }
    
    protected function parseBlocks($templateContents, $slots)
    {
        preg_match_all('/\{\{ block\([\'"]([^\)]+)[\'"]\)/s', $templateContents, $matches);
        if ( ! array_key_exists(1, $matches)) {
            return array();
        }
        
        // Ignore blocks not included in found slots
        $templateSlots = array();
        foreach($matches[1] as $slotName) {
            if ( ! in_array($slotName, $slots)) { 
                continue;
            }
            
            $templateSlots[] = $slotName;
        }
        
        return $templateSlots;
    }
    
    protected function parseSlots($slotsDirectory)
    {
        $finder = new Finder();
        $templateFiles = $finder->files('*.twig')->in($slotsDirectory);
        
        $slots = array();        
        foreach ($templateFiles as $template) {
            $template = (string) $template;
            $templateContents = file_get_contents($template);        
            $slots = array_merge($slots, $this->fetchSlots($templateContents), $this->parseTemplateForSlots($templateContents));
        }
        
        return $slots;
    }
    
    private function parseTemplateForSlots($templateContents)
    {
        $slots = array();
            
        preg_match_all('/use["\'\s]+([^"\']+)["\']+?/s', $templateContents, $matches);
        if ( ! array_key_exists(1, $matches)) {
            return array();
        }

        foreach($matches[1] as $file) {
            $template = $this->templateLocator->locate($this->nameParser->parse($file));
            $templateContents = file_get_contents($template);
            $slots = array_merge($slots, $this->fetchSlots($templateContents), $this->parseTemplateForSlots($templateContents));
        }
        
        return $slots;
    }


    protected function initDirectories()
    {
        $directories = array(
            $this->templatesDir,
        );

        $globalResourcesFolder = $this->kernelDir . '/Resources/views/' . $this->themeName;
        if (is_dir($globalResourcesFolder)) {
            $directories[] = $globalResourcesFolder;
        }
        
        return $directories;
    }
    
    protected function findTemplateSlots($templateFiles)
    {
        $slots = array();        
        foreach ($templateFiles as $template) {
            $template = (string) $template;
            $templateName = basename($template);
            $templateContents = file_get_contents($template);

            $slots[$templateName] = array(
                "slots" => $this->fetchSlots($templateContents),
                "extends" => null,
            );

            preg_match('/extends["\'\s]+(.*?)["\']+?/s', $templateContents, $matches);
            if ( ! array_key_exists(1, $matches)) {
                continue;
            }

            $tokens = explode(':', $matches[1]);
            if ( ! array_key_exists(2, $tokens)) {
                continue;
            }

            $slots[$templateName]["extends"] = basename($tokens[2]);
        }
        
        return $slots;
    }

    /**
     * Fetches the slots attributes
     *
     * @param  string $templateContents
     * @return array
     */
    protected function fetchSlots($templateContents)
    {
        $validAttributes = array(
            'repeated' => '',
            'blockType' => '',
            'htmlContent' => ''
        );
        
        //preg_match_all('/BEGIN-SLOT[^\w]*[\r\n]([\s]*)(.*?)[\r\n][^\w]*END-SLOT/s', $templateContents, $matches, PREG_SET_ORDER);
        preg_match_all('/BEGIN-SLOT[^\w]*[\r\n](.*?)END-SLOT/si', $templateContents, $matches, PREG_SET_ORDER);
        $slots = array();
        foreach ($matches as $slotAttributes) {
            /*$spaces = $slotAttributes[1];
            $attributes = $slotAttributes[2];

            if ($spaces !== "") {
                $attributesArray = explode("\n", $attributes);
                $trimmedAttributes = array(); //
                foreach ($attributesArray as $line) {
                    $trimmedAttributes[] = str_replace($spaces, "", $line);
                }
                $attributes = implode("\n", $trimmedAttributes);
            }*/
            
            $attributes = "\n" . $slotAttributes[1];
            preg_match('/([\r\n][^\w]+)/', $attributes, $spacesMatch);
            $attributes = str_replace($spacesMatch[1], "\n", $attributes);
            
            $parsedAttributes = $this->ymlParser->parse($attributes);
            if ( ! array_key_exists('name', $parsedAttributes)) {
                continue;
            }

            $slotName = $parsedAttributes['name'];
            unset($parsedAttributes['name']);
            $attributes = array_intersect_key($parsedAttributes, $validAttributes);
            $wrongAttributes = array_diff_key($parsedAttributes, $validAttributes);
            $slots[$slotName] = $attributes;
            if (count($wrongAttributes) > 0) {
                $slots[$slotName]['errors'] = $wrongAttributes;
            }
        }

        return $slots;
    }
}