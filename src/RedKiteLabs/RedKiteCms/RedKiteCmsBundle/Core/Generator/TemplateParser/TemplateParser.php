<?php

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Generator\TemplateParser;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * AlTemplateParser parses the twig templates from a given folder to look for
 * the information that defines the slot's attributes
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlTemplateParser
{
    private $templateLocator;
    private $nameParser;
    private $ymlParser;
    private $templatesDir;
    private $kernelDir;
    private $themeName;

    /**
     * Constructor
     *
     * @param TemplateLocator             $templateLocator
     * @param TemplateNameParserInterface $nameParser
     * @internal param string $templatesDir
     */
    public function __construct(TemplateLocator $templateLocator, TemplateNameParserInterface $nameParser)
    {
        $this->templateLocator = $templateLocator;
        $this->nameParser = $nameParser;

        $this->ymlParser = new Yaml();
    }

    /**
     * Parses the templates folder and returns the retrieved information
     * as array
     *
     * @param  string $templatesDir
     * @param  string $kernelDir
     * @param  string $themeName
     * @return array
     */
    public function parse($templatesDir, $kernelDir, $themeName)
    {
        $this->templatesDir = $templatesDir;
        $this->kernelDir = $kernelDir;
        $this->themeName = $themeName;

        $directories = $this->initDirectories();

        $slotsDirectory = dirname($this->templatesDir) . '/Slots';
        $slots = $this->parseSlots($slotsDirectory);
        $finder = new Finder();
        $templateFiles = $finder->files('*.twig')->in($directories);

        $templateContents = array();
        foreach ($templateFiles as $template) {
            $templateName = basename((string) $template);
            $templateContents[$templateName]["content"] =  file_get_contents($template);

            // Generate templates only in the theme top folder
            $generate = false;
            if (strpos($template, $this->kernelDir) === false && dirname($template) == $this->templatesDir) {
                $generate = true;
            }
            $templateContents[$templateName]["generate"] = $generate;
        }

        $templates = array();
        foreach ($templateFiles as $template) {
            $templateName = basename((string) $template);
            if (! $templateContents[$templateName]["generate"]) {
                continue;
            }

            $fileContents = $templateContents[$templateName]["content"];
            $contents = $this->joinTemplates($templateContents, $fileContents);
            $templateSlots = $this->parseBlocks(implode("\n", $contents), array_keys($slots));

            $templates[] = array(
                'name' => $templateName,
                'slots' => $templateSlots,
            );
        }

        return array(
            "templates" => $templates,
            "slots" => $slots,
        );
    }

    private function initDirectories()
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

    private function joinTemplates($templateContents, $fileContents, $contents = array())
    {
        $contents[] = $fileContents;

        preg_match('/extends["\'\s]+(.*?)["\']+?/s', $fileContents, $matches);
        if ( ! array_key_exists(1, $matches)) {
            return $contents;
        }

        $tokens = explode(':', $matches[1]);
        if ( ! array_key_exists(2, $tokens)) {
            return $contents;
        }

        $fileContents = $templateContents[basename($tokens[2])]["content"];

        return $this->joinTemplates($templateContents, $fileContents, $contents);
    }

    private function parseBlocks($templateContents, $slots)
    {
        preg_match_all('/\{\{ block\([\'"]([^\)]+)[\'"]\)/s', $templateContents, $matches);
        if ( ! array_key_exists(1, $matches)) {
            return array();
        }

        // Ignore blocks not included in found slots
        $templateSlots = array();
        foreach ($matches[1] as $slotName) {
            if ( ! in_array($slotName, $slots)) {
                continue;
            }

            $templateSlots[] = $slotName;
        }

        return $templateSlots;
    }

    private function parseSlots($slotsDirectory)
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

        foreach ($matches[1] as $file) {
            $template = $this->templateLocator->locate($this->nameParser->parse($file));
            $templateContents = file_get_contents($template);
            $slots = array_merge($slots, $this->fetchSlots($templateContents), $this->parseTemplateForSlots($templateContents));
        }

        return $slots;
    }

    /**
     * Fetches the slots attributes
     *
     * @param  string $templateContents
     * @return array
     */
    private function fetchSlots($templateContents)
    {
        $validAttributes = array(
            'repeated' => '',
            'blockType' => '',
            'htmlContent' => '',
            'blockDefinition' => ''
        );

        preg_match_all('/BEGIN-SLOT[^\w]*[\r\n](.*?)END-SLOT/si', $templateContents, $matches, PREG_SET_ORDER);
        $slots = array();
        foreach ($matches as $slotAttributes) {
            $attributes = "\n" . $slotAttributes[1];
            preg_match('/([\r\n][^\w]+)/', $attributes, $spacesMatch);
            $attributes = str_replace($spacesMatch[1], "\n", $attributes);

            try {
                $parsedAttributes = $this->ymlParser->parse($attributes);
                if ( ! array_key_exists('name', $parsedAttributes)) {
                    continue;
                }

                if ( array_key_exists('blockDefinition', $parsedAttributes)) {
                    $parsedAttributes['blockDefinition'] = json_encode($parsedAttributes['blockDefinition']);
                }
            } catch (ParseException $ex) {
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
