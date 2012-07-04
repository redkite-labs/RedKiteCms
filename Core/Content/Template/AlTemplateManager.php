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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface;
use AlphaLemon\PageTreeBundle\Core\PageBlocks\AlPageBlocksInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel;

/**
 * AlTemplateManager is the object responsible to manage the template's slots.
 *
 *
 * The AlTemplateManager object collects the slots from the templated defined by an object derived
 * from an AlTemplateSlotsInterface.
 *
 * @api
 * @see AlSlotManager
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlTemplateManager extends AlTemplateBase
{
    protected $slotManagers = array();
    protected $template;
    protected $blockRepository;
    protected $pageBlocks;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $dispatcher
     * @param AlTemplate $template
     * @param AlPageBlocksInterface $pageBlocks
     * @param BlockRepositoryInterface $blockRepository
     * @param AlBlockManagerFactoryInterface $blockManagerFactory
     * @param AlParametersValidatorInterface $validator
     */
    public function __construct(EventDispatcherInterface $dispatcher, AlTemplate $template, AlPageBlocksInterface $pageBlocks, BlockRepositoryInterface $blockRepository = null, AlBlockManagerFactoryInterface $blockManagerFactory = null, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($dispatcher, $blockManagerFactory, $validator);

        $this->template = $template;
        $this->pageBlocks = $pageBlocks;
        $this->blockRepository =  (null === $blockRepository) ? new AlBlockRepositoryPropel() : $blockRepository;
    }

    /**
     * Sets the current AlTemplate object
     *
     * @param AlTemplate $templateSlots
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     * @api
     */
    public function setTemplate(AlTemplate $templateSlots)
    {
        $this->template = $templateSlots;

        return $this;
    }

    /**
     * Returns the current AlTemplateobject
     *
     * @return \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate
     * @api
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Sets the current AlTemplateSlots object
     *
     * @api
     * @param AlTemplateSlotsInterface $templateSlots
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     */
    public function setTemplateSlots(AlTemplateSlotsInterface $templateSlots)
    {
        $this->template->setTemplateSlots($templateSlots);

        return $this;
    }

    /**
     * Returns the current AlTemplateSlots object
     *
     * @api
     * @return \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots
     */
    public function getTemplateSlots()
    {
        return $this->template->getTemplateSlots();
    }

    /**
     * Sets the page contents container object
     *
     * @api
     * @param AlPageBlocksInterface $pageBlocks
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     */
    public function setPageBlocks(AlPageBlocksInterface $pageBlocks)
    {
        $this->pageBlocks = $pageBlocks;

        return $this;
    }

    /**
     * Returns the current page contents container object
     *
     * @api
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks
     */
    public function getPageBlocks()
    {
        return $this->pageBlocks;
    }

    /**
     * Sets the block model interface
     *
     * @api
     * @param BlockRepositoryInterface $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     */
    public function setBlockModel(BlockRepositoryInterface $v)
    {
        $this->pageBlocks = $v;

        return $this;
    }

    /**
     * Sets the block model object associated to the template manager
     *
     * @return BlockRepositoryInterface
     */
    public function getBlockModel()
    {
        return $this->blockRepository;
    }

    /**
     * Returns the managed slot managers
     *
     * @api
     * @return array
     */
    public function getSlotManagers()
    {
        return $this->slotManagers;
    }

    /**
     * Returns the slot manager that matches the given parameter
     *
     * @api
     * @param string $slotName
     * @return null|AlSlotManager
     */
    public function getSlotManager($slotName)
    {
        if (!is_string($slotName))
        {
            return null;
        }

        return (array_key_exists($slotName, $this->slotManagers)) ? $this->slotManagers[$slotName] : null;
    }

    /**
     * Returns the slot manager as an array
     *
     * @api
     * @param string $slotName
     * @return array
     * @throws \InvalidArgumentException
     */
    public function slotToArray($slotName)
    {
        if (!is_string($slotName)) {
            throw new \InvalidArgumentException($this->translate("slotToArray accepts only strings"));
        }

        if (!array_key_exists($slotName, $this->slotManagers)) {
            return array();
        }

        $slotManager = $this->slotManagers[$slotName];

        return $slotManager->toArray();
    }

    /**
     * Returns all the slotManagers as array
     *
     * @api
     * @return array
     */
    public function slotsToArray()
    {
        $slotContents = array();
        foreach ($this->slotManagers as $slotName => $slot)
        {
            $slotContents[$slotName] = $slot->toArray();
        }

        return $slotContents;
    }


    public function refresh()
    {
        $this->setUpSlotManagers();

        return $this;
    }

    /**
     * Populates each slot using the default contents and saves them to the database.
     *
     *
     * This method is used to add a new page based on the template managed by this object. The slots
     * are filled up using the dafault values provided by each single slot.
     *
     * @api
     * @param int $idLanguage           The id that identified the language to add
     * @param int $idPage               The id that identified the page to add
     * @param Boolean $ignoreRepeated   True skips the slots that are repeated on page
     * @return Boolean
     * @throws Exception
     */
    public function populate($idLanguage, $idPage, $ignoreRepeated = false)
    {
        if (count($this->slotManagers) > 0) {
            try {
                $this->refreshPageContentsContainer($idLanguage, $idPage);

                $result = false;
                $this->blockRepository->startTransaction();
                foreach ($this->slotManagers as $slot) {
                    if ($ignoreRepeated && $slot->getRepeated() != 'page') {
                        continue;
                    }

                    $slot->setForceSlotAttributes(true);
                    $result = $slot->addBlock($idLanguage, $idPage);
                    if (null !== $result) {
                        if (!$result) break;
                    }
                }

                if ($result) {
                    $this->blockRepository->commit();
                }
                else {
                    $this->blockRepository->rollBack();
                }

                return $result;
            }
            catch(\Exception $e)
            {
                if (isset($this->blockRepository) && $this->blockRepository !== null) {
                    $this->blockRepository->rollBack();
                }

                throw $e;
            }
        }
    }

    /**
     * Removes the blocks from the whole slot managers managed by the template manager
     *
     * @api
     * @param Boolean $ignoreRepeated
     * @return type
     * @throws Exception
     */
    public function clearBlocks($ignoreRepeated = true)
    {
        if (count($this->slotManagers) > 0) {
            try {
                $result = null;
                $this->blockRepository->startTransaction();
                foreach ($this->slotManagers as $slotManager) {
                    if ($ignoreRepeated && $slotManager->getSlot()->getRepeated() != 'page') {
                        continue;
                    }
                    $result = $slotManager->deleteBlocks();

                    if (!$result) {
                        break;
                    }
                }

                if(null === $result) return;

                if ($result) {
                    $this->blockRepository->commit();
                }
                else {
                    $this->blockRepository->rollBack();
                }

                return $result;
            }
            catch(\Exception $e)
            {
                if (isset($this->blockRepository) && $this->blockRepository !== null) {
                    $this->blockRepository->rollBack();
                }

                throw $e;
            }
        }

    }

    /**
     * Clear the blocks from the whole slot managers managed by the template manager,
     * for a page identified by the required parameters
     *
     * @api
     * @param type $languageId
     * @param type $pageId
     * @param type $ignoreRepeated
     * @return type
     * @throws Exception
     */
    public function clearPageBlocks($languageId, $pageId, $ignoreRepeated = true)
    {
        try {
            $this->blockRepository->startTransaction();

            $pageBlocks = clone($this->pageBlocks);
            $this->refreshPageContentsContainer($languageId, $pageId);

            $result = $this->clearBlocks($ignoreRepeated);
            $this->pageBlocks = $pageBlocks;
            $this->setUpSlotManagers();

            if ($result) {
                $this->blockRepository->commit();
            }
            else {
                $this->blockRepository->rollBack();
            }

            return $result;
        }
        catch(\Exception $e)
        {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Creates the slot managers from the current template slot class
     *
     * @api
     */
    protected function setUpSlotManagers()
    {
        $templateSlots = $this->template->getTemplateSlots();

        if (null === $templateSlots) {
            throw new General\ParameterIsEmptyException("Any template has been set");
        }

        $slots = $templateSlots->getSlots();
        if (empty($slots)) return;

        foreach ($slots as $slotName => $slot) {
            $this->slotManagers[$slotName] = $this->createSlotManager($slot);
        }

        // Looks for existing slots on previous theme, not included in the theme in use
        $orphanSlots = array_diff(array_keys($this->pageBlocks->getBlocks()), array_keys($slots));
        foreach ($orphanSlots as $slotName) {
            if ($slotName != "") {
                $slot = new AlSlot($slotName);
                $this->slotManagers[$slotName] = $this->createSlotManager($slot);
            }
        }
    }

    /**
     * Create the slot manager for the given slot
     *
     * @api
     * @param AlSlot $slot
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager
     */
    protected function createSlotManager(AlSlot $slot)
    {
        $slotName = $slot->getSlotName();
        $alBlocks = $this->pageBlocks->getSlotBlocks($slotName);
        $slotManager = new AlSlotManager($this->dispatcher, $slot, $this->blockRepository, $this->blockManagerFactory, $this->validator);
        $slotManager->setUpBlockManagers($alBlocks);

        return $slotManager;
    }

    /**
     * Refreshes the page container
     *
     * @param int $idLanguage
     * @param int $idPage
     */
    private function refreshPageContentsContainer($idLanguage, $idPage)
    {
        if($idLanguage != $this->pageBlocks->getIdLanguage() || $idPage != $this->pageBlocks->getIdPage()) {
            $this->pageBlocks
                ->setIdLanguage($idLanguage)
                ->setIdPage($idPage)
                ->refresh();
            $this->setUpSlotManagers();
        }
    }

}