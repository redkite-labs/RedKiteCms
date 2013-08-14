<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Aligner;

use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactoryInterface;
use RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection;

/**
 * AlRepeatedSlotsAligner is responsibile to align the slots repeated status when
 * a slot changes its status on a template
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
class AlRepeatedSlotsAligner
{
    protected $themesCollection;
    protected $slotsConverterFactory;
    protected $blockRepository;
    protected $languageId = null;
    protected $pageId = null;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection                                        $themesCollection
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactoryInterface $slotsConverterFactory
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface                          $factoryRepository
     *
     * @api
     */
    public function __construct(AlThemesCollection $themesCollection, AlSlotsConverterFactoryInterface $slotsConverterFactory, AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->themesCollection = $themesCollection;
        $this->slotsConverterFactory = $slotsConverterFactory;
        $this->factoryRepository = $factoryRepository;
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
    }

    /**
     * Sets the id of the language
     *
     * @param  int                                                                                       $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Aligner\AlRepeatedSlotsAligner
     *
     * @api
     */
    public function setLanguageId($v)
    {
        $this->languageId = $v;

        return $this;
    }

    /**
     * Sets the id of the page
     *
     * @param  int                                                                                       $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Aligner\AlRepeatedSlotsAligner
     *
     * @api
     */
    public function setPageId($v)
    {
        $this->pageId = $v;

        return $this;
    }

    /**
     * Fetches the id of the language
     *
     * @return int
     *
     * @api
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Fetches the id of the page
     *
     * @return int
     *
     * @api
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Compares the slots and updates the contents according the new status
     *
     * @param string $templateName  The current template to check
     * @param array  $templateSlots The template's slots
     *
     * @return null|boolean null is returned when any update is made
     *
     * @api
     */
    public function align($templateName, array $templateSlots)
    {
        if (empty($templateSlots)) {
            return null;
        }

        $templateName = strtolower($templateName);

        if (null === $this->languageId) {
            $languageRepository = $this->factoryRepository->createRepository('Language');
            $language = $languageRepository->mainLanguage();
            $this->languageId = $language->getId();
        }

        if (null === $this->pageId) {
            $pageRepository = $this->factoryRepository->createRepository('Page');
            $page = $pageRepository->fromTemplateName($templateName, true);
            $this->pageId = $page->getId();
        }

        $pageBlocks = $this->blockRepository->retrieveContents(array(1, $this->languageId), array(1, $this->pageId));
        $currentSlots = $this->templateSlotsToArray($templateSlots);

        $changedSlots = array();
        foreach ($pageBlocks as $pageBlock) {
            $slotName = $pageBlock->getSlotName();
            if (array_key_exists($slotName, $currentSlots)) {
                $languageId = $pageBlock->getLanguageId();
                $pageId = $pageBlock->getPageId();
                $currentRepeatedStatus = 'page';
                if ($languageId == 1 && $pageId == 1) {
                    $currentRepeatedStatus = 'site';
                }

                if ($languageId != 1 && $pageId == 1) {
                    $currentRepeatedStatus = 'language';
                }

                if ($currentRepeatedStatus != $currentSlots[$slotName]) {
                    $changedSlots[$slotName] = $currentSlots[$slotName];
                }
            }
        }

        return ( ! empty($changedSlots)) ? $this->updateSlotStatus($templateSlots, $changedSlots) : null;
    }

    /**
     * Updates the slot status for the given slots
     *
     * @param  array                                                                        $templateSlots
     * @param  array                                                                        $changedSlots
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Aligner\Exception
     *
     * @api
     */
    protected function updateSlotStatus(array $templateSlots, array $changedSlots)
    {
        try {
            $result = true;
            $this->blockRepository->startTransaction();
            foreach ($changedSlots as $slotName => $repeated) {
                $converter = $this->slotsConverterFactory->createConverter($templateSlots[$slotName], $repeated);
                if (null === $converter) continue;

                $result = $converter->convert();
                if (!$result) {
                    break;
                }
            }

            if ($result) {
                $this->blockRepository->commit();
            } else {
                $this->blockRepository->rollBack();
            }

            return $result;
        } catch (\Exception $e) {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Converts the slots to an array where the key is the slot name and the value is the repeated status
     *
     * @param  array $slots
     * @return type
     *
     * @api
     */
    protected function templateSlotsToArray($slots)
    {
        $result = array();
        foreach ($slots as $slot) {
            $result[$slot->getSlotName()] = $slot->getRepeated();
        }

        return $result;
    }
}
