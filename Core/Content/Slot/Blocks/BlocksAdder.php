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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks;

use RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface;

/**
 * BlocksAdder is the object deputated to add and edit a block on a slot
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class BlocksAdder extends BaseBlocks
{
    private $lastAdded = null;
    private $lastEdited = null;
    private $blockManagerFactory;

    /**
     * Constructor
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface $blockRepository
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface $blockManagerFactory
     */
    public function __construct(BlockRepositoryInterface $blockRepository, AlBlockManagerFactoryInterface $blockManagerFactory)
    {
        parent::__construct($blockRepository);
        
        $this->blockManagerFactory = $blockManagerFactory;
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
     * Returns the last edited block manager
     *
     * @return AlBlockManager object or null
     *
     * @api
     */
    public function lastEdited()
    {
        return $this->lastEdited;
    }
    
    /**
     * Adds a new AlBlock object to the slot
     *
     * The created block managed is added to the collection. When the $referenceBlockId param is valorized,
     * the new block is created under the block identified by the given id
     * 
     * @param \RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot $slot
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection $blockManagersCollection
     * @param array $options
     * @return boolean|null
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException
     */
    public function add(AlSlot $slot, BlockManagersCollection $blockManagersCollection, array $options)
    {
        try {
            $repeated = $slot->getRepeated();
            $options = $this->normalizeRepeatedStatus($repeated, $options);
            
            // Make sure that a content repeated at site level is never added twice
            if ($options["skipSiteLevelBlocks"] && $repeated == 'site' && count($this->blockRepository->retrieveContents(1, 1, $slot->getSlotName())) > 0) {
                return;
            }
            
            $this->blockRepository->startTransaction();
            
            $blockManagerPosition = $blockManagersCollection->getBlockManagerIndex($options["referenceBlockId"]);
            $options["position"] = $blockManagerPosition + 1;
            if (null !== $blockManagerPosition && $options["insertDirection"] == 'bottom') {
                $options["position"] = $blockManagerPosition + 2;
                $blockManagerPosition += 1;
            }
            
            $blockManager = $this->createBlockManager($options["type"]);
            $parts = $blockManagersCollection->insertAt($blockManager, $blockManagerPosition);
            $result = $this->adjustPosition('add', $parts["right"]);
                        
            if ($result !== false) {
                $result = $this->saveBlockmanager($slot, $blockManager, $options);
            }
            
            if ($result !== false) {
                $this->blockRepository->commit();
                $this->lastAdded = $blockManager;

                return $result;
            }

            $this->blockRepository->rollBack();
            
            return $result;
        } catch (\Exception $e) {
            $this->blockRepository->rollBack();

            throw $e;
        }
    }
    
    /**
     * Edits the block
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface $blockManager
     * @param array $values
     * @return boolean|null
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException
     * 
     * @api
     */
    public function edit(AlBlockManagerInterface $blockManager, array $values)
    {        
        try {
            $this->blockRepository->startTransaction();
            
            $result = $blockManager->save($values);
            if ($result !== false) {
                $this->blockRepository->commit();
                $this->lastEdited = $blockManager;

                return $result;
            }

            $this->blockRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            $this->blockRepository->rollBack();

            throw $e;
        }
    }
    
    private function normalizeRepeatedStatus($repeated, array $options)
    {
        switch ($repeated) {
            case 'site':
                $options["idPage"] = 1;
                $options["idLanguage"] = 1;
                break;
            case 'language':
                $options["idPage"] = 1;
                break;
        }
        
        return $options;
    }
    
    private function createBlockManager($type)
    {
        $blockManager = $this->blockManagerFactory->createBlockManager($type);
        if (null === $blockManager) {
            $exception = array(
                'message' => 'exception_type_not_exists',
                'parameters' => array(
                    '%type%' => $type,
                ),
            );

            throw new InvalidArgumentException(json_encode($exception));
        }
        
        return $blockManager;
    }
    
    private function saveBlockmanager(AlSlot $slot, $blockManager, array $options)
    {            
        $values = array(
            "PageId"          => $options["idPage"],
            "LanguageId"      => $options["idLanguage"],
            "SlotName"        => $slot->getSlotName(),
            "Type"            => $options["type"],
            "ContentPosition" => $options["position"],
            //"CreatedAt"       => date("Y-m-d H:i:s"),
        );
        
        if ($options["forceSlotAttributes"]) {
            $content = $slot->getContent();
            if (null !== $content) {
                $values["Content"] = $content;
            }
        }

        $blockManager->set(null);
        
        return $blockManager->save($values);
    }
}