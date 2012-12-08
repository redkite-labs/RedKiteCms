<?php
/**
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

use AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocksInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;

/**
 * AlTemplateManager wrap an AlTemplate object to manage the template's slots when the
 * AlphaLemon Cms editor is active
 *
 *
 * The AlTemplateManager object collects the slots from the templated defined by an object derived
 * from an AlTemplateSlotsInterface.
 *
 *
 * @see AlSlotManager
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
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
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface $eventsHandler
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     * @param \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate $template
     * @param \AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocksInterface $pageBlocks
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface $blockManagerFactory
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler, AlFactoryRepositoryInterface $factoryRepository, AlTemplate $template = null, AlPageBlocksInterface $pageBlocks = null, AlBlockManagerFactoryInterface $blockManagerFactory = null, AlParametersValidatorInterface $validator = null)
    {
        $blockManagerFactory = (null === $blockManagerFactory) ? new AlBlockManagerFactory($factoryRepository) : $blockManagerFactory;
        parent::__construct($eventsHandler, $blockManagerFactory, $validator);

        $this->template = $template;
        $this->factoryRepository = $factoryRepository;
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
        $this->pageBlocks = (null === $pageBlocks) ? new AlPageBlocks($this->factoryRepository) : $pageBlocks;
    }

    /**
     * Clones the holden objects, when the object is cloned
     * 
     * @api
     */
    public function __clone()
    {
        if (null !== $this->template) $this->template = clone($this->template);
        if (null !== $this->blockRepository) $this->blockRepository = clone($this->blockRepository);
        if (null !== $this->pageBlocks) $this->pageBlocks = clone($this->pageBlocks);
    }

    /**
     * Sets the current AlTemplate object
     * 
     * @param \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate $template
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     * 
     * @api
     */
    public function setTemplate(AlTemplate $template)
    {
        if ($template !== $this->template) {
            $this->template = $template;
            $this->setUpSlotManagers();
        }

        return $this;
    }

    /**
     * Returns the current AlTemplateobject
     *
     * @return \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate
     * 
     * @api
     */
    public function getTemplate()
    {
        return $this->template;
    }
    
    /**
     * Sets the current AlTemplateSlots object
     * 
     * @param \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface $templateSlots
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     * 
     * @api
     */
    public function setTemplateSlots(AlTemplateSlotsInterface $templateSlots)
    {
        $this->template->setTemplateSlots($templateSlots);

        return $this;
    }

    /**
     * Returns the current AlTemplateSlots object
     *
     * @return \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots
     * 
     * @api
     */
    public function getTemplateSlots()
    {
        return $this->template->getTemplateSlots();
    }
    
    /**
     * Sets the page contents container object
     * 
     * @param \AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocksInterface $pageBlocks
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     * 
     * @api
     */
    public function setPageBlocks(AlPageBlocksInterface $pageBlocks)
    {
        $this->pageBlocks = $pageBlocks;

        return $this;
    }

    /**
     * Returns the current page contents container object
     *
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks
     * 
     * @api
     */
    public function getPageBlocks()
    {
        return $this->pageBlocks;
    }
    
    /**
     * Sets the block model interface
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     * 
     * @api
     */
    public function setBlockRepository(BlockRepositoryInterface $v)
    {
        $this->blockRepository = $v;

        return $this;
    }

    /**
     * Sets the block model object associated to the template manager
     *
     * @return BlockRepositoryInterface
     * 
     * @api
     */
    public function getBlockRepository()
    {
        return $this->blockRepository;
    }

    /**
     * Returns the managed slot managers
     *
     * @return array
     * 
     * @api
     */
    public function getSlotManagers()
    {
        return $this->slotManagers;
    }

    /**
     * Returns the slot manager that matches the given parameter
     * 
     * @param type $slotName
     * @return null|array
     * 
     * @api
     */
    public function getSlotManager($slotName)
    {
        if (!is_string($slotName)) {
            return null;
        }

        return (array_key_exists($slotName, $this->slotManagers)) ? $this->slotManagers[$slotName] : null;
    }
    
    /**
     * Returns the slot manager as an array
     *
     * @param string $slotName
     * @return array
     * @throws \InvalidArgumentException
     * 
     * @api
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
     * @return array
     * 
     * @api
     */
    public function slotsToArray()
    {
        $slotContents = array();
        foreach ($this->slotManagers as $slotName => $slot) {
            $slotContents[$slotName] = $slot->toArray();
        }

        return $slotContents;
    }

    /**
     * Refreshes the TemplateManager
     * 
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     * 
     * @api
     */
    public function refresh()
    {
        $this->setUpSlotManagers();

        return $this;
    }

    /**
     *Populates each slot using the default contents and saves them to the database.
     *
     * This method is used to add a new page based on the template managed by this object. The slots
     * are filled up using the dafault values provided by each single slot.
     *
     *
     * @param  int       $idLanguage     The id that identified the language to add
     * @param  int       $idPage         The id that identified the page to add
     * @param  boolean   $skipRepeated True skips the slots that are repeated on page
     * @return boolean
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Exception
     * 
     * @api
     */
    public function populate($idLanguage, $idPage, $skipRepeated = false)
    {
        try {
            $this->refreshPageBlocks($idLanguage, $idPage);

            $result = false;
            $this->blockRepository->startTransaction();
            foreach ($this->slotManagers as $slotManager) {
                if ($skipRepeated && $slotManager->getRepeated() != 'page') {
                    continue;
                }
                
                $slotManager
                    ->setForceSlotAttributes(true)
                    ->setSkipSiteLevelBlocks(true)
                ;
                $result = $slotManager->addBlock($idLanguage, $idPage);
                if(false === $result) break;
            }

            if ($result !== false) {
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
     * Removes the blocks from the whole slot managers managed by the template manager
     *
     * @param  boolean $skipRepeated When true skips the slots with a repeated status
     * @return boolean
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Exception
     * 
     * @api
     */
    public function clearBlocks($skipRepeated = true)
    {
        try {
            $result = null;
            $this->blockRepository->startTransaction();
            foreach ($this->slotManagers as $slotManager) {
                if ($skipRepeated && $slotManager->getSlot()->getRepeated() != 'page') {
                    continue;
                }
                $result = $slotManager->deleteBlocks();

                if(false === $result) break;
            }

            if ($result !== false) {
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
     * Clear the blocks from the whole slot managers managed by the template manager,
     * for a page identified by the required parameters
     * 
     * @param int $languageId
     * @param int $pageId
     * @param boolean $skipRepeated
     * @return boolean
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Exception
     * 
     * @api
     */
    public function clearPageBlocks($languageId, $pageId, $skipRepeated = true)
    {
        try {
            $this->blockRepository->startTransaction();

            $pageBlocks = clone($this->pageBlocks);
            $this->refreshPageBlocks($languageId, $pageId);

            $result = $this->clearBlocks($skipRepeated);
            $this->pageBlocks = $pageBlocks;
            $this->setUpSlotManagers();

            if ($result !== false) {
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
     * Creates the slot managers from the current template slot class
     * 
     * @return null|boolean
     * @throws General\ParameterIsEmptyException
     * @throws Exception\EmptyTemplateSlotsException
     */
    protected function setUpSlotManagers()
    {
        if (null === $this->template) {
            return;
        }

        $this->slotManagers = array();
        $templateSlots = $this->template->getTemplateSlots();
        
        if (null === $templateSlots) {
            throw new General\ParameterIsEmptyException("Any template has been set");
        }

        $slots = $templateSlots->getSlots();
        if (empty($slots)) {
            throw new Exception\EmptyTemplateSlotsException(sprintf('The template "%s" has any slot attached. Please check your template\'s configuration', $this->template->getTemplateName()));
        }

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
     * @param \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot $slot
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager
     */
    protected function createSlotManager(AlSlot $slot)
    {
        $slotName = $slot->getSlotName();
        $alBlocks = $this->pageBlocks->getSlotBlocks($slotName);
        $slotManager = new AlSlotManager($this->eventsHandler, $slot, $this->blockRepository, $this->blockManagerFactory, $this->validator);
        $slotManager->setUpBlockManagers($alBlocks);

        return $slotManager;
    }

    /**
     * Refreshes the page container
     *
     * @param int $idLanguage
     * @param int $idPage
     */
    private function refreshPageBlocks($idLanguage, $idPage)
    {
        if ($idLanguage != $this->pageBlocks->getIdLanguage() || $idPage != $this->pageBlocks->getIdPage()) {
            $this->pageBlocks
                ->setIdLanguage($idLanguage)
                ->setIdPage($idPage)
                ->refresh();
            $this->setUpSlotManagers();
        }
    }
}