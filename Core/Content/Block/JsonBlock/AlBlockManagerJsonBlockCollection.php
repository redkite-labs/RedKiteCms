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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock;

use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * AlBlockManagerJsonBlockCollection is the base object deputated to handle a json content
 * which defines a collection of objects
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
abstract class AlBlockManagerJsonBlockCollection extends AlBlockManagerJsonBase
{
    protected $container;
    protected $blocksRepository;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface                             $container
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        $this->container = $container;
        $eventsHandler = $container->get('alpha_lemon_cms.events_handler');
        $factoryRepository = $container->get('alpha_lemon_cms.factory_repository');
        $this->blocksRepository = $factoryRepository->createRepository('Block');

        parent::__construct($eventsHandler, $factoryRepository, $validator);
    }

    /**
     * {@inheritdoc}
     *
     * Extends the base edit method to manage a json collection of objects
     *
     * @api
     */
    protected function edit(array $values)
    {
        $values = $this->manageCollection($values);

        if (false === $values) {
            return false;
        }
        
        return parent::edit($values);
    }

    /**
     * Manages the json collection, adding and removing items collection from the json
     * block
     *
     * @param  array $values
     * @return array|boolean
     */
    protected function manageCollection(array $values)
    {
        if (array_key_exists('Content', $values)) {            
            $data = json_decode($values['Content'], true);
            $savedValues = $this->decodeJsonContent($this->alBlock);

            if ($data["operation"] == "add") {
                if (isset($data["item"])) {
                    
                    $savedValues = $this->addItem($data, $savedValues);
                    if (false === $savedValues) {
                        return false;
                    }
                } else {
                    $savedValues[] = $data["value"];
                }
                $values = array("Content" => json_encode($savedValues));
            }

            if ($data["operation"] == "remove") {
                
                $savedValues = $this->deleteItem($data, $savedValues);
                if (false === $savedValues) {
                    return false;
                }
                
                $values = array("Content" => json_encode(array_values($savedValues)));
            }
        }

        return $values;
    }
    
    protected function addItem($data, $savedValues)
    {  
        $result = null;
        $nextItem = null;
        $item = $data["item"];
        $blockKey = $this->alBlock->getId() . '-'; 
        $blocks = $this->blocksRepository->retrieveContentsBySlotName($blockKey . '%');
        $this->blocksRepository->startTransaction();

        foreach($blocks as $block) {
            $itemProgressive = str_replace($blockKey, '', $block->getSlotName());
            if ($item == $itemProgressive || $item == -1) {
                $nextItem = $item + 1;
            }

            if (null !== $nextItem && $itemProgressive >= $nextItem) { 
                $itemProgressive++; 
                $block->setSlotName($blockKey . $itemProgressive);
                $result = $block->save();
                if (! $result) {
                    break;
                }
            }
        }

        if (false === $result) {
            $this->blocksRepository->rollback();

            return false;
        }

        $this->blocksRepository->commit();
        array_splice($savedValues, $nextItem, 0, array($data["value"]));

        return $savedValues;
    }
    
    protected function deleteItem($data, $savedValues)
    {
        $item = $data["item"]; 
        unset($savedValues[$item]);

        $result = null;
        $nextItem = null;
        $blockKey = $this->alBlock->getId() . '-';         
        $blocks = $this->blocksRepository->retrieveContentsBySlotName($blockKey . '%');
        $this->blocksRepository->startTransaction();

        foreach($blocks as $block) {
            $itemProgressive = str_replace($blockKey, '', $block->getSlotName()); 
            if ($item == $itemProgressive) { 
                $nextItem = $item + 1;
                
                $block->setToDelete(1);
                $result = $block->save();
                if (! $result) {
                    break;
                }
            }

            if (null !== $nextItem && $itemProgressive >= $nextItem) { 
                $itemProgressive--; 
                $block->setSlotName($blockKey . $itemProgressive);
                $result = $block->save();
                if (! $result) {
                    break;
                }
            }
        }

        if (false === $result) {
            $this->blocksRepository->rollback();

            return false;
        }

        $this->blocksRepository->commit();

        return $savedValues;
    }
}
