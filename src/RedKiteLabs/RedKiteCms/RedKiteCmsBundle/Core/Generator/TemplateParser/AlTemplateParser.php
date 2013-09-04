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
        
        $templateFiles = $finder->files('*.twig')->in($directories);
        foreach ($templateFiles as $template) {
            $template = (string)$template;
            $templateName = basename($template);
            $templateContents = file_get_contents($template);
            
            $slots = $this->fetchSlots($templateContents);
            
            $templates[$templateName]['assets']['external_stylesheets'] = $this->fetchExternalStylesheets($templateContents);
            $templates[$templateName]['assets']['external_javascripts'] = $this->fetchExternalJavascripts($templateContents);
            $templates[$templateName]['assets']['external_stylesheets_cms'] = $this->fetchExternalStylesheetsCms($templateContents);
            $templates[$templateName]['assets']['external_javascripts_cms'] = $this->fetchExternalJavascriptsCms($templateContents);
            $templates[$templateName]['slots'] = $slots;
            if (strpos($template, $this->kernelDir) === false) {
                $templates[$templateName]['generate_template'] = dirname($template) == $this->templatesDir;
            }
        }
        
        return $templates;
    }

    /**
     * Fetches the external stylesheets
     *
     * @param string $templateContents
     * @return array
     */
    protected function fetchExternalStylesheets($templateContents)
    {
        return $this->fetchAssets($templateContents, 'EXTERNAL-STYLESHEETS');
    }

    /**
     * Fetches the external javascripts
     *
     * @param string $templateContents
     * @return array
     */
    protected function fetchExternalJavascripts($templateContents)
    {
        return $this->fetchAssets($templateContents, 'EXTERNAL-JAVASCRIPTS');
    }

    /**
     * Fetches the external stylesheets loaded when in cms mode
     *
     * @param string $templateContents
     * @return array
     */
    protected function fetchExternalStylesheetsCms($templateContents)
    {
        return $this->fetchAssets($templateContents, 'CMS-STYLESHEETS');
    }

    /**
     * Fetches the external javascripts loaded when in cms mode
     *
     * @param string $templateContents
     * @return array
     */
    protected function fetchExternalJavascriptsCms($templateContents)
    {
        return $this->fetchAssets($templateContents, 'CMS-JAVASCRIPTS');
    }

    /**
     * Fetches the assets attributes indentified by a section
     *
     * @param string $templateContents
     * @param string $section
     * @return array
     */
    protected function fetchAssets($templateContents, $section)
    {
        $pattern = sprintf('/BEGIN-%1$s[^\w\s]*[\r\n](.*?)[\r\n][^\w]*END-%1$s/s', $section);
        preg_match($pattern, $templateContents, $matches);
        
        return ( ! empty($matches)) ? explode("\n", $matches[1]) : array();
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
