<?php

namespace AlphaLemon\ThemeEngineBundle\Core\Generator\TemplateParser;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * AlTemplateParser parses the twig templates from a given folder to look for
 * the information that defines the slot's attributes
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlTemplateParser
{
    private $templatesDir;
    private $ymlParser;

    /**
     * Constructor
     *
     * @param string $templatesDir
     */
    public function __construct($templatesDir)
    {
        $this->templatesDir = $templatesDir;
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
        $templateFiles = $finder->files('*.twig')->in($this->templatesDir);
        foreach ($templateFiles as $template) {
            $template = (string)$template;
            $templateContents = file_get_contents($template);
            $templates[basename($template)]['assets']['external_stylesheets'] = $this->fetchExternalStylesheets($templateContents);
            $templates[basename($template)]['assets']['external_javascripts'] = $this->fetchExternalJavascripts($templateContents);
            $templates[basename($template)]['assets']['external_stylesheets_cms'] = $this->fetchExternalStylesheetsCms($templateContents);
            $templates[basename($template)]['assets']['external_javascripts_cms'] = $this->fetchExternalJavascriptsCms($templateContents);
            $templates[basename($template)]['slots'] = $this->fetchSlots($templateContents);
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
        preg_match(sprintf('/BEGIN-%1$s\n(.*?)\nEND-%1$s/s', $section), $templateContents, $matches);

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
        $validAttributes = array('repeated' => '', 'blockType' => '', 'htmlContent' => '');

        preg_match_all('/BEGIN-SLOT\s([^\s\n]+)\n([\s]+)?(.*?)END-SLOT/s', $templateContents, $matches, PREG_SET_ORDER);
        $slots = array();
        foreach ($matches as $slotAttributes) {
            $slotName = strtolower($slotAttributes[1]);
            $spaces = $slotAttributes[2];
            $attributes = $slotAttributes[3];

            if ($spaces !== "") {
                $attributesArray = explode("\n", $attributes);
                $trimmedAttributes = array(); //
                foreach ($attributesArray as $line) {
                    $trimmedAttributes[] = str_replace($spaces, "", $line);
                }
                $attributes = implode("\n", $trimmedAttributes);
            }

            $parsedAttributes = $this->ymlParser->parse($attributes);
            $attributes = array_intersect_key($parsedAttributes, $validAttributes);
            $wrongAttributes = array_diff_key($parsedAttributes, $validAttributes);
            $slots[$slotName] = $attributes;
            $slots[$slotName]['errors'] = $wrongAttributes;
        }

        return $slots;
    }
}