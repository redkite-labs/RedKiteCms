<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Aligner;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\SlotsConverterFactoryInterface;
use RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\ThemesCollection;
use RedKiteLabs\ThemeEngineBundle\Core\Template\Template;

/**
 * RepeatedSlotsAligner is responsibile to align the slots repeated status when
 * a slot changes its status on a template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class RepeatedSlotsAligner
{
    protected $themesCollection;
    protected $slotsConverterFactory;
    protected $blockRepository;
    protected $languageId = null;
    protected $pageId = null;

    /**
     * Constructor
     *
     * @param ThemesCollection               $themesCollection
     * @param SlotsConverterFactoryInterface $slotsConverterFactory
     * @param FactoryRepositoryInterface     $factoryRepository
     *
     * @api
     */
    public function __construct(ThemesCollection $themesCollection, SlotsConverterFactoryInterface $slotsConverterFactory, FactoryRepositoryInterface $factoryRepository)
    {
        $this->themesCollection = $themesCollection;
        $this->slotsConverterFactory = $slotsConverterFactory;
        $this->factoryRepository = $factoryRepository;
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
    }

    /**
     * Sets the id of the language
     *
     * @param  int  $v
     * @return self
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
     * @param  int  $v
     * @return self
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
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Template\Template $template
     * @param array                                                   $templateSlots The template's slots
     *
     * @return null|boolean null is returned when any update is made
     *
     * @api
     */
    public function align(Template $template, array $templateSlots)
    {
        $slots = array_flip($template->getSlots());
        if (empty($templateSlots) || empty($slots)) {
            return null;
        }

        $templateName = strtolower($template->getTemplateName());
        $templateSlots = array_intersect_key($templateSlots, $slots);

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
     * @param  array      $templateSlots
     * @param  array      $changedSlots
     * @return boolean
     * @throws \Exception
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
     * @return array
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
