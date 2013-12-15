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

use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;

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
abstract class BaseBlocks
{
    protected $blockRepository;
    
    /**
     * Constructor
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface $blockRepository
     */
    public function __construct(BlockRepositoryInterface $blockRepository)
    {
        $this->blockRepository = $blockRepository;
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
     * @param  string                                                                                    $op       The operation to do. It accepts add or del as valid values
     * @param  array                                                                                     $managers An array of block managers
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

                if (false === $result) {
                    break;
                }
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