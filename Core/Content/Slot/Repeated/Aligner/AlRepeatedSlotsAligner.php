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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Aligner;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\Finder\Finder;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsFactoryInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidFileNameException;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\Base\AlPropelOrm;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactoryInterface;

/**
 * AlRepeatedSlotsManager is responsible to verify when a slot changes its repetition status and
 * to update the contents to reflect it. This job is achieved saving the current status for each slot
 * in an xml file, which is used when the comparison with the active slots status is made.
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlRepeatedSlotsAligner
{
    protected $kernel;
    protected $templateSlotsFactory;
    protected $slotsConverterFactory;
    protected $cacheFile;
    protected $skeletonFile;
    protected $orm;

    /**
     * Constructor
     * @param ContainerInterface    $container
     * @param string                $activeThemeName  The active theme
     */
    public function __construct(KernelInterface $kernel, AlTemplateSlotsFactoryInterface $templateSlotsFactory, AlSlotsConverterFactoryInterface $slotsConverterFactory, AlPropelOrm $orm = null)
    {
        $this->kernel = $kernel;
        $this->templateSlotsFactory = $templateSlotsFactory;
        $this->slotsConverterFactory = $slotsConverterFactory;
        $this->orm = (null === $orm) ? new AlPropelOrm() : $orm;

        $this->cacheFile =  $kernel->getRootDir() . '/Resources/active_theme_slots.xml';
        $this->skeletonFile = AlToolkit::locateResource($this->kernel, '@AlphaLemonCmsBundle/Resources/data/xml/repeated-slots-skeleton.xml');
    }

    public function setCacheFile($fileName)
    {
        $this->cacheFile = $fileName;

        return $this;
    }

    public function setSkeletonFile($fileName)
    {
        if(file_exists($fileName) && @simplexml_load_file($fileName)) {
            $this->skeletonFile = $fileName;
        }

        return $this;
    }

    public function getCacheFile()
    {
        return $this->cacheFile;
    }

    public function getSkeletonFile()
    {
        return $this->skeletonFile;
    }

    /**
     * Compares the slots and updates the contents according the new status
     *
     * @param   string  $templateName   The current template to check
     * @param   array   $savedSlots     The saved slots
     *
     * @return  boolean or null when any update is made
     */
    public function align($themeName, $templateName, array $templateSlots)
    {
        $result = true;
        $savedSlots = $this->loadSavedSlots($templateName);
        if(null !== $savedSlots)
        {
            $currentSlots = $this->templateSlotsToArray($templateSlots);
            $diffCurrent = array_diff_assoc($currentSlots, $savedSlots);
            if(empty($diffCurrent))
            {
                return null;
            }

            $diffActive = array_diff_assoc($savedSlots, $currentSlots);
            $changedSlots = array_intersect_key($diffCurrent, $diffActive);

            $result = $this->updateSlotStatus($templateSlots, $changedSlots);
        }

        // The xml file is made for the first time
        if($result) $this->saveSlots($themeName, $templateName);

        return $result;
    }

    /**
     * Updates the slot status for the given slots
     *
     * @param   array   $changedSlots   The slots to update
     * @return  boolean
     */
    protected function updateSlotStatus(array $templateSlots, array $changedSlots)
    {
        try
        {
            $result = true;
            $this->orm->startTransaction();
            foreach($changedSlots as $slotName => $repeated)
            {
                $converter = $this->slotsConverterFactory->createConverter($templateSlots[$slotName], $repeated);
                $result = $converter->convert();
                if(!$result) {
                    break;
                }
            }

            if ($result)
            {
                $this->orm->commit();
            }
            else
            {
                $this->orm->rollBack();
            }

            return $result;
        }
        catch(\Exception $e)
        {
            if (isset($this->orm) && $this->orm !== null) {
                $this->orm->rollBack();
            }

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
        if(!is_file($this->cacheFile)) {
            return null;
        }

        $xml = @simplexml_load_file($this->cacheFile);
        if (false === $xml) {
            return;
        }

        $result = array();
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
    protected function saveSlots($themeName, $templateName)
    {
        $slotClassesPath = AlToolkit::locateResource($this->kernel, $themeName) . '/Core/Slots';

        $result = array();
        $finder = new Finder();
        $files = $finder->depth(0)->files()->in($slotClassesPath);
        foreach($files as $file)
        {
            $pathInfo = pathinfo($file);
            $filename = $pathInfo["filename"];
            $templateSlots = $this->templateSlotsFactory->create($themeName, $filename);
            $templateName = strtolower($templateName);
            $result[$templateName] = $this->templateSlotsToArray($templateSlots->getSlots());
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
        foreach($slots as $slot)
        {
            $result[$slot->getSlotName()] = $slot->getRepeated();
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
        $skeleton = file_get_contents($this->skeletonFile);
        $xml = new \SimpleXMLElement($skeleton);
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

        $xml->asXML($this->cacheFile);
    }
}