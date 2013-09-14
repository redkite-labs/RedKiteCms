<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;
use RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;

/**
 * AlSlotManager represents a slot on a page.
 *
 * A slot is the place on the page where one or more blocks lives.
 *
 * This object is responsible  to manage the blocks that it contains, adding, editing
 * and removing them.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlSlotManager extends AlTemplateBase
{
    protected $slot;
    protected $lastAdded = null;
    protected $blockManagers = array();
    protected $forceSlotAttributes = false;
    protected $skipSiteLevelBlocks = false;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface           $eventsHandler
     * @param \RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot                               $slot
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface   $blockRepository
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface     $blockManagerFactory
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler, AlSlot $slot, BlockRepositoryInterface $blockRepository, AlBlockManagerFactoryInterface $blockManagerFactory = null, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($eventsHandler, $blockManagerFactory, $validator);

        $this->slot = $slot;
        $this->blockRepository = $blockRepository;
    }

    /**
     * Sets the slot object
     *
     * @param  \RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot         $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\AlSlotManager
     *
     * @api
     */
    public function setSlot(AlSlot $v)
    {
        $this->slot = $v;

        return $this;
    }

    /**
     * Returns the slot object
     *
     * @return \RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot
     *
     * @api
     */
    public function getSlot()
    {
        return $this->slot;
    }

    /**
     * Sets the block model object
     *
     * @param  \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\AlSlotManager
     *
     * @api
     */
    public function setBlockRepository(BlockRepositoryInterface $v)
    {
        $this->blockRepository = $v;

        return $this;
    }

    /**
     * Returns the block manager object
     *
     * @return \RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot
     */
    public function getBlockRepository()
    {
        return $this->blockRepository;
    }

    /**
     * Sets the slot manager's behavior when a new block is added
     *
     * When true forces the add operation to use the default AlSlot attributes for
     * the new block type
     *
     * @param  boolean                                                         $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\AlSlotManager
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function setForceSlotAttributes($v)
    {
        if ( ! is_bool($v)) {
            throw new InvalidArgumentException('exception_boolean_value_required_for_setForceSlotAttributes');
        }

        $this->forceSlotAttributes = $v;

        return $this;
    }

    /**
     * Skips adding a new block when the slot is repeated at site level and the block
     * has been already added
     *
     * @param  boolean                                                         $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\AlSlotManager
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function setSkipSiteLevelBlocks($v)
    {
        if ( ! is_bool($v)) {
            throw new InvalidArgumentException('exception_boolean_value_required_for_setSkipSiteLevelBlocks');
        }

        $this->skipSiteLevelBlocks = $v;

        return $this;
    }

    /**
     * Returns the slot manager's behavior when a new block is added
     *
     * @return boolean
     *
     * @api
     */
    public function getForceSlotAttributes()
    {
        return $this->forceSlotAttributes;
    }

    /**
     * Returns the slot's blocks repeated status
     *
     * @return string
     *
     * @api
     */
    public function getRepeated()
    {
        return $this->slot->getRepeated();
    }

    /**
     * Returns the name of the slot
     *
     * @return string
     *
     * @api
     */
    public function getSlotName()
    {
        return $this->slot->getSlotName();
    }

    /**
     * Returns the block managers
     *
     * @return array
     *
     * @api
     */
    public function getBlockManagers()
    {
        return $this->blockManagers;
    }

    /**
     * Returns the first block manager placed on the slot
     *
     * @return null|AlBlockManager
     *
     * @api
     */
    public function first()
    {
        return ($this->length() > 0) ? $this->blockManagers[0] : null;
    }

    /**
     * Returns the last block manager placed on the slot
     *
     * @return null|AlBlockManager
     *
     * @api
     */
    public function last()
    {
        return ($this->length() > 0) ? $this->blockManagers[$this->length() - 1] : null;
    }

    /**
     * Returns the block manager at the given index.
     *
     * @return null|AlBlockManager
     *
     * @api
     */
    public function indexAt($index)
    {
        return ($index >= 0 && $index <= $this->length() - 1) ? $this->blockManagers[$index] : null;
    }

    /**
     * Returns the number of block managers managed by the slot manager
     *
     * @return int
     *
     * @api
     */
    public function length()
    {
        return count($this->blockManagers);
    }

    /**
     * Returns the last block manager added to the slot manager
     *
     * @return AlBlockManager object or null
     *
     * @api
     */
    public function lastAdded()
    {
        return $this->lastAdded;
    }

    /**
     * Adds a new AlBlock object to the slot
     *
     * The created block managed is added to the collection. When the $referenceBlockId param is valorized,
     * the new block is created under the block identified by the given id
     *
     * @param  int                                                                                          $idLanguage
     * @param  type                                                                                         $idPage
     * @param  type                                                                                         $type             The block type. By default a Text block is added
     * @param  type                                                                                         $referenceBlockId The id of the reference block. When given, the block is placed below this one
     * @return null|boolean
     * @throws InvalidArgumentTypeException
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function addBlock($idLanguage, $idPage, $type = 'Text', $referenceBlockId = null)
    { 
        if ((int) $idLanguage == 0) {
            throw new InvalidArgumentTypeException('exception_invalid_value_for_language_id');
        }

        if ((int) $idPage == 0) {
            throw new InvalidArgumentTypeException('exception_invalid_value_for_page_id');
        }

        try {
            $repeated = $this->slot->getRepeated();
            switch ($repeated) {
                case 'site':
                    $idPage = 1;
                    $idLanguage = 1;
                    //idGroup = 1; //TODO
                    break;
                case 'language':
                    $idPage = 1;
                    //idGroup = 1; //TODO
                    break;
                /* TODO
                case 'group':
                    $idPage = 1;
                    break;
                 */
                case 'page':
                    //idGroup = 1; //TODO
                    break;
            }

            // Make sure that a content repeated at site level is never added twice
            if ($this->skipSiteLevelBlocks && $repeated == 'site') {
                if (count($this->blockRepository->retrieveContents(1, 1, $this->slot->getSlotName())) > 0) {
                    return;
                }
            }

            // Forces the creation of the block type defined in the AlSlot object
            if ($this->forceSlotAttributes) {
                $type = $this->slot->getBlockType();
            }

            $alBlockManager = $this->blockManagerFactory->createBlockManager($type);
            if (null === $alBlockManager) {
                $exception = array(
                    'message' => 'exception_type_not_exists',
                    'parameters' => array(
                        '%type%' => $type,
                    ),
                );
                
                throw new InvalidArgumentException(json_encode($exception));
            }

            $result = true;
            $this->blockRepository->startTransaction();

            // Find the block position
            $leftArray = array();
            $rightArray = array();
            $managersLength = $this->length();
            $position = $managersLength + 1;
            if (null !== $referenceBlockId) {
                $index = $this->getBlockManagerIndex($referenceBlockId);
                if (null !== $index) {
                    // The new block must de added below the current one, so it must retrieve the block manager down the reference manager
                    $index += 1;
                    if ($managersLength > $index) {
                        $leftArray = array_slice($this->blockManagers, 0 , $index);
                        $rightArray = array_slice($this->blockManagers, $index , $managersLength - 1);

                        $manager = $this->blockManagers[$index];
                        $position = $manager->get()->getContentPosition();
                        $result = $this->adjustPosition('add', $rightArray);
                    }
                }
            }

            if (false !== $result) {
                $values = array(
                  "PageId"          => $idPage,
                  "LanguageId"      => $idLanguage,
                  "SlotName"        => $this->slot->getSlotName(),
                  "Type"            => $type,
                  "ContentPosition" => $position,
                  "CreatedAt"       => date("Y-m-d H:i:s"),
                );

                if ($this->forceSlotAttributes) {
                    $content = $this->slot->getContent();
                    if (null !== $content) $values["Content"] = $content;
                }

                $alBlockManager->set(null);
                $result = $alBlockManager->save($values);
            }

            if ($result !== false) {
                $this->blockRepository->commit();
                
                if (!empty($leftArray) || !empty($rightArray)) {
                    $index = $position - 1;
                    $this->blockManagers = array_merge($leftArray, array($index => $alBlockManager), $rightArray);
                } else {
                    $this->blockManagers[] = $alBlockManager;
                }

                $this->lastAdded = $alBlockManager;
                
                return $result;
            } 
            
            $this->blockRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Edits the block
     *
     * @param  int                                                                                          $idBlock The id of the block to edit
     * @param  array                                                                                        $values  The new values
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * 
     * @api
     */
    public function editBlock($idBlock, array $values)
    {
        $blockManager = $this->getBlockManager($idBlock);
        if (null !== $blockManager) {
            try {
                $this->blockRepository->startTransaction();

                $result = $blockManager->save($values);
                if ($result !== false) {
                    $this->blockRepository->commit();
                    
                    return $result;
                }
                
                $this->blockRepository->rollBack();

                return $result;
            } catch (\Exception $e) {
                if (isset($this->blockRepository) && $this->blockRepository !== null) {
                    $this->blockRepository->rollBack();
                }

                throw $e;
            }
        }
    }

    /**
     * Deletes the block from the slot
     *
     * @param  int                                                                                          $idBlock The id of the block to remove
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * 
     * @api
     */
    public function deleteBlock($idBlock)
    {
        $leftArray = array();
        $rightArray = array();
        $info = $this->getBlockManagerAndIndex($idBlock);
        if (null === $info) {
            return null;
        }
        
        $index = $info['index'];
        $leftArray = array_slice($this->blockManagers, 0 , $index);
        $rightArray = array_slice($this->blockManagers, $index + 1, $this->length() - 1);

        try {
            $this->blockRepository->startTransaction();

            // Adjust the blocks position
            $result = $this->adjustPosition('del', $rightArray);
            if (false !== $result) {
                $result = $info['manager']->delete();
            }

            if (false !== $result) {
                $this->blockRepository->commit();

                $this->blockManagers = array_merge($leftArray, $rightArray);
                
                return $result;
            } 
            
            $this->blockRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Deletes all the blocks managed by the slot
     *
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * 
     * @api
     */
    public function deleteBlocks()
    {
        try {
            if (count($this->blockManagers) > 0) {
                $result = null;
                $this->blockRepository->startTransaction();

                foreach ($this->blockManagers as $blockManager) {
                    $result = $blockManager->delete();
                    if (false === $result) {
                        break;
                    }
                }

                if (false !== $result) {
                    $this->blockRepository->commit();
                    $this->blockManagers = array();

                    return $result;
                }
                
                $this->blockRepository->rollBack();

                return $result;
            }
        } catch (\Exception $e) {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Retrieves the block manager by the block's id
     *
     * @param  type                                                                        $idBlock The id of the block to retrieve
     * @return null|\RedKiteLabs\RedKiteCmsBundle\Core\Content\AlContentManagerInterface
     *
     * @api
     */
    public function getBlockManager($idBlock)
    {
        $info = $this->getBlockManagerAndIndex($idBlock);

        return (null !== $info) ? $info['manager'] : null;
    }

    /**
     * Retrieves the block manager index by the block's id
     *
     * @param  int $idBlock The id of the block to retrieve
     * @return int
     *
     * @api
     */
    public function getBlockManagerIndex($idBlock)
    {
        $info = $this->getBlockManagerAndIndex($idBlock);

        return (null !== $info) ? $info['index'] : null;
    }

    /**
     * Returns the managed blocks as an array
     *
     * @return array
     *
     * @api
     */
    public function toArray()
    {
        $result = array();
        foreach ($this->blockManagers as $blockManager) {
            if (null !== $blockManager) {
                $result[] = $blockManager->toArray();
            }
        }

        return $result;
    }

    /**
     * Sets up the block managers.
     *
     * When the blocks have not been given, it retrieves all the pages's contents saved on the slot
     *
     * @param array $alBlocks
     *
     * @api
     */
    public function setUpBlockManagers(array $alBlocks)
    {
        foreach ($alBlocks as $alBlock) {
            $alBlockManager = $this->blockManagerFactory->createBlockManager($alBlock);
            $this->blockManagers[] = $alBlockManager;
        }
    }

    /**
     * Retrieves the block manager and the index by the block's id
     *
     * @param  int        $idBlock The id of the block to retrieve
     * @return null|array
     */
    protected function getBlockManagerAndIndex($idBlock)
    {
        foreach ($this->blockManagers as $index => $blockManager) {
            if ($blockManager->get()->getId() == $idBlock) {
                return array('index' => $index, 'manager' => $blockManager);
            }
        }

        return null;
    }

    /**
     * Adjusts the blocks position on the slot, when a new block is added or a block is deleted.
     *
     * When in *add* mode, it creates a space between the adding block's position and
     * the blocks below, incrementing their position by one
     *
     * When in *del* mode, decrements by 1 the position of the blocks placed below the
     * removing block
     *
     * @param  string                                                                                       $op       The operation to do. It accepts add or del as valid values
     * @param  array                                                                                        $managers An array of block managers
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @throws InvalidArgumentException     
     */
    protected function adjustPosition($op, array $managers)
    {
        try {
            // Checks the $op parameter. If doesn't match, throwns and exception
            $required = array("add", "del");
            if (!in_array($op, $required)) {
                // @codeCoverageIgnoreStart
                $exception = array(
                    'message' => 'exception_invalid_argumento_for_adjustPosition',
                    'parameters' => array(
                        '%className%' => get_class($this),
                        '%options%' => $required, 
                        '%parameter%' => $op,
                    ),
                );
                throw new InvalidArgumentException(json_encode($exception));
                // @codeCoverageIgnoreEnd
            }
            
            if (count($managers) == 0) {
                return;
            }
            
            $result = null;
            $this->blockRepository->startTransaction();
            foreach ($managers as $blockManager) {
                $block = $blockManager->get();
                $position = ($op == 'add') ? $block->getContentPosition() + 1 : $block->getContentPosition() - 1;
                $result = $this->blockRepository
                                ->setRepositoryObject($block)
                                ->save(array("ContentPosition" => $position));

                if (false === $result) break;
            }

            if (false !== $result) {
                $this->blockRepository->commit();
                
                return $result;
            }
            
            $this->blockRepository->rollBack();

            return $result;            
        } catch (\Exception $e) {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }
}
