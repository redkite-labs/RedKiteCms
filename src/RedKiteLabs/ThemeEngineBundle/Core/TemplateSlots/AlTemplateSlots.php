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
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This is the base class where the template's slots must be defined
 *
 * @author AlphaLemon
 */
abstract class AlTemplateSlots implements AlTemplateSlotsInterface
{
    private $kernel;
    private $slots = array();
    private $fixturesFolder = null;
    private $themeName;
    private $templateName;

    /**
     * Constructor
     */
    public function __construct(KernelInterface $kernel = null, $fixturesFolder = null)
    {
        if (null === $fixturesFolder && null === $kernel) {
           throw new \RuntimeException("The template's folder cannot be located. Please give a valid folder or a valid kernel instance to locate the fixtures folder");
        }

        if (null === $kernel && !realpath($fixturesFolder)) {
           throw new \InvalidException("The fixtures folder does not exist. Please add a valid folder where the template's slots are located");
        }

        $this->kernel = $kernel;
        $this->fixturesFolder = $fixturesFolder;
        $this->slots = $this->configure();
    }

    public function getThemeName()
    {
        return $this->themeName;
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * { @inheritdoc }
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

        return $this->slots[$slotName]->getHtmlContent();
    }

    /**
     * Returns all the slots by repeated status
     * @return type
     */
    public function toArray()
    {
        //throw new \Exception('Please rename me [AlTemplateSlots toArray()]');
        $slots = array();
        foreach($this->slots as $slot)
        {
            $slots[$slot->getRepeated()][] = $slot->getSlotName();
        }

        return $slots;
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

        $this->themeName = $themeName;
        $this->templateName = $templateName;

        $baseSlots = $this->retrieveSlotsFromFixtureFile($themeName, 'base');
        $templateSlots = $this->retrieveSlotsFromFixtureFile($themeName, $templateName);
        $fixturedSlots = array_merge($baseSlots, $templateSlots);

        $slots = array();
        foreach($fixturedSlots as $slotName => $values)
        {
            if('~' === $values) $values = null;
            $slots[$slotName] = new AlSlot($slotName, $values);
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
    private function loadFixtures($themeName, $templateName)
    {
        $fixturesFolder = (null === $this->fixturesFolder) ? AlToolkit::locateResource($this->kernel, $themeName) . 'Resources/fixtures' : $this->fixturesFolder;
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
}