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
    public function __construct($templatesDir, $kernelDir, $themeName)
    {
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
        $templates = array();
        $finder = new Finder(); 
        
        $directories = array(
            $this->templatesDir, 
        );
        
        $globalResourcesFolder = $this->kernelDir . '/Resources/views/' . $this->themeName;
        if (is_dir($globalResourcesFolder)) {
            $directories[] = $globalResourcesFolder;
        }
        
        $slots = array();
        $templateFiles = $finder->files('*.twig')->in($directories);
        foreach ($templateFiles as $template) {
            $template = (string)$template;
            $templateFolder = dirname($template);
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

        $templates = array();
        foreach ($templateFiles as $template) {
            $template = (string)$template;
            $templateName = basename($template);
            $templateContents = file_get_contents($template);
            
            $currentTemplateName = $templateName;
            $currentSlots = array();
            do {
                $currentTemplateSlots = $slots[$currentTemplateName]["slots"];
                if(null === $currentTemplateSlots) {
                    $currentTemplateSlots = array();
                }
                $currentSlots = array_merge($currentSlots, $currentTemplateSlots);
                $currentTemplateName = $slots[$currentTemplateName]["extends"];

            } while($currentTemplateName != null);
            
            if (strpos($template, $this->kernelDir) === false && dirname($template) == $this->templatesDir && ! (empty($currentSlots))) {
                $templates[$templateName]['slots'] = $currentSlots;   
            }
        }

        return $templates;
    }

    /**
     * Fetches the slots attributes
     *
     * @param string $templateContents
     * @return array
     */
    protected function fetchSlots($templateContents)
    {
        $validAttributes = array(
            'repeated' => '',
            'blockType' => '',
            'htmlContent' => ''
        );

        preg_match_all('/BEGIN-SLOT[^\w]*[\r\n]([\s]*)(.*?)[\r\n][^\w]*END-SLOT/s', $templateContents, $matches, PREG_SET_ORDER);
        $slots = array();
        foreach ($matches as $slotAttributes) {
            $spaces = $slotAttributes[1];
            $attributes = $slotAttributes[2];

            if ($spaces !== "") {
                $attributesArray = explode("\n", $attributes);
                $trimmedAttributes = array(); //
                foreach ($attributesArray as $line) {
                    $trimmedAttributes[] = str_replace($spaces, "", $line);
                }
                $attributes = implode("\n", $trimmedAttributes);
            }

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
