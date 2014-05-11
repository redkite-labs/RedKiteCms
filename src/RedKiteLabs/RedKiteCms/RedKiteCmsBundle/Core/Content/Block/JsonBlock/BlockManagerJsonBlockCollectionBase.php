<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorInterface;

/**
 * BlockManagerJsonBlockCollectionBase is the base object deputed to implement the
 * very basic methods to handle a json content which defines a collection of objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class BlockManagerJsonBlockCollectionBase extends BlockManagerJsonBase
{
    /** @var ContainerInterface */
    protected $container;
    /** @var \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface */
    protected $blocksRepository;
    /** @var \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Translator\TranslatorInterface  */
    protected $translator;

    /**
     * Constructor
     *
     * @param ContainerInterface             $container
     * @param ParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(ContainerInterface $container, ParametersValidatorInterface $validator = null)
    {
        $this->container = $container;
        $eventsHandler = $container->get('red_kite_cms.events_handler');
        $factoryRepository = $container->get('red_kite_cms.factory_repository');
        $this->blocksRepository = $factoryRepository->createRepository('Block');
        $this->translator = $this->container->get('red_kite_cms.translator');

        parent::__construct($eventsHandler, $factoryRepository, $validator);
    }

    /**
     * Manages the json collection, adding and removing items collection from the json
     * block
     *
     * @param  array         $values
     * @param  null|array    $savedValues
     * @param  null          $blockKey
     * @return array|boolean
     */
    protected function manageCollection(array $values, $savedValues = null, $blockKey = null)
    {
        if (array_key_exists('Content', $values)) {
            $data = json_decode($values['Content'], true);
            if (null === $savedValues) {
                $savedValues = $this->decodeJsonContent($this->alBlock);
            }

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

                $savedValues = $this->deleteItem($data, $savedValues, $blockKey);
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
        $item = $data["item"];
        $result = $this->manageChildren($item);

        if (false === $result) {
            $this->blocksRepository->rollback();

            return false;
        }

        $this->blocksRepository->commit();
        array_splice($savedValues, $this->nextItem, 0, array($data["value"]));

        return $savedValues;
    }

    protected function deleteItem($data, $savedValues)
    {
        $item = $data["item"];
        unset($savedValues[$item]);

        $result = $this->manageChildren($item, true);

        if (false === $result) {
            $this->blocksRepository->rollback();

            return false;
        }

        $this->blocksRepository->commit();

        return $savedValues;
    }

    protected function manageChildren($item, $delete=false)
    {
        $result = null;
        $this->nextItem = null;
        $blockKey = $this->alBlock->getId() . '-';
        $blocks = $this->blocksRepository->retrieveContentsBySlotName($blockKey . '%');
        $this->blocksRepository->startTransaction();
        foreach ($blocks as $block) {
            $itemProgressive = str_replace($blockKey, '', $block->getSlotName());
            if ($item == $itemProgressive || $item == -1) {
                $this->nextItem = $item + 1;

                if ($delete) {
                    $block->setToDelete(1);
                    $result = $block->save();
                    if (! $result) {
                        break;
                    }

                    $this->deleteChildren($block->getId() . '-' . $itemProgressive);
                }
            }

            if (null !== $this->nextItem && $itemProgressive >= $this->nextItem) {
                $prevSlotName = $block->getId() . '-' . $itemProgressive;
                $increment = 1;
                if ($delete) {
                    $increment = -1;
                }
                $itemProgressive += $increment;
                $newSlotName = $block->getId() . '-' . $itemProgressive;

                $this->updateSlotNames($prevSlotName, $newSlotName);
                if (! $this->updateSlotName($block, $blockKey . $itemProgressive)) {
                    break;
                }
            }
        }

        return $result;
    }

    protected function updateSlotNames($prevSlotName, $newSlotName)
    {
        $result = true;
        $blocks = $this->blocksRepository->retrieveContentsBySlotName($prevSlotName . '-%');
        foreach ($blocks as $block) {
            $blockSlotName = str_replace($prevSlotName, $newSlotName, $block->getSlotName());
            if (! $this->updateSlotName($block, $blockSlotName)) {
                break;
            }
        }

        return $result;
    }

    protected function updateSlotName(Block $block, $blockSlotName)
    {
        $block->setSlotName($blockSlotName);

        return $block->save();
    }

    protected function deleteChildren($prevSlotName)
    {
        $result = true;
        $blocks = $this->blocksRepository->retrieveContentsBySlotName($prevSlotName . '-%');
        foreach ($blocks as $block) {
            $block->setToDelete(1);
            if (! $block->save()) {
                break;
            }
        }

        return $result;
    }
}
