<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\FilesystemEntity;

use JMS\Serializer\SerializerInterface;
use RedKiteCms\Exception\Publish\PageNotPublishedException;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Finder\Finder;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Page is the object deputed to handle a website page
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\FilesystemEntity
 */
class Page extends Entity implements RenderableInterface
{
    /**
     * @type string
     */
    private $username;
    /**
     * @type array
     */
    private $pageAttributes = array();
    /**
     * @type array
     */
    private $seoAttributes = array();
    /**
     * @type array
     */
    private $pageSlots = array();
    /**
     * @type string
     */
    private $pageName = null;
    /**
     * @type string
     */
    private $language = null;
    /**
     * @type string
     */
    private $country = null;
    /**
     * @type string
     */
    private $currentLanguage = null;
    /**
     * @type \RedKiteCms\FilesystemEntity\SlotParser
     */
    private $slotParser;

    /**
     * Constructor
     *
     * @param \JMS\Serializer\SerializerInterface $serializer
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $optionsResolver
     * @param \RedKiteCms\FilesystemEntity\SlotParser $slotParser
     */
    public function __construct(
        SerializerInterface $serializer,
        OptionsResolver $optionsResolver,
        SlotParser $slotParser
    ) {
        parent::__construct($serializer, $optionsResolver);

        $this->slotParser = $slotParser;
    }

    /**
     * Returns the page attributes
     *
     * @return array
     */
    public function getPageAttributes()
    {
        return $this->pageAttributes;
    }

    /**
     * Returns the seo attributes for this page
     *
     * @return array
     */
    public function getSeoAttributes()
    {
        return $this->seoAttributes;
    }

    /**
     * Returns the page slots
     *
     * @return array
     */
    public function getPageSlots()
    {
        return $this->pageSlots;
    }

    /**
     * Returns page name
     * @return string
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * Returns the current page language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Returns the current language country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * returns the full language, combining language and country
     *
     * @return string
     */
    public function getCurrentLanguage()
    {
        return $this->currentLanguage;
    }

    /**
     * {@inheritdoc}
     */
    public function render($sourceDir, array $options, $username = null)
    {
        $this->pageName = $options["page"];
        $this->language = $options["language"];
        $this->country = $options["country"];
        $this->currentLanguage = $this->language . '_' . $this->country;

        $this->username = $username;
        $this->fetchPageAttributes($sourceDir);
        $this->fetchPageBlocks($sourceDir, $options);
    }

    private function fetchPageAttributes($sourceDir)
    {
        $fileName = (null !== $this->username) ? $this->username : 'page';
        $file = sprintf('%s/pages/pages/%s/%s.json', $sourceDir, $this->pageName, $fileName);
        $this->pageAttributes = json_decode(FilesystemTools::readFile($file), true);

        $fileName = (null !== $this->username) ? $this->username : 'seo';
        $file = sprintf('%s/pages/pages/%s/%s/%s.json', $sourceDir, $this->pageName, $this->currentLanguage, $fileName);
        if ($fileName === 'seo' && !file_exists($file)) {
            throw new PageNotPublishedException('production_page_not_available');
        }
        $this->seoAttributes = json_decode(FilesystemTools::readFile($file), true);
    }

    private function fetchPageBlocks($sourceDir, array $options)
    {
        $slotsFolder = sprintf('%s/pages/pages/%s/%s', $sourceDir, $this->pageName, $this->currentLanguage);
        $this->renderSlots($sourceDir, $slotsFolder, $options);

        $slotsFolder = sprintf('%s/slots', $sourceDir, $this->pageName);
        $this->renderSlots($sourceDir, $slotsFolder, $options);
    }

    private function renderSlots($sourceDir, $slotsFolder, array $options)
    {
        $finder = new Finder();
        $folders = $finder->directories()->depth(0)->in($slotsFolder);
        foreach ($folders as $folder) {
            $folder = (string)$folder;
            $slotName = basename($folder);
            $options["slot"] = $slotName;
            $slot = new Slot($this->serializer, $this->optionsResolver, $this->slotParser);
            $slot->render($sourceDir, $options, $this->username);
            $this->pageSlots[$slotName] = $slot;
        }
    }
}