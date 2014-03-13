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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;

/**
 * AlPageBlocks is the object deputated to load and handle the blocks for a website
 * page
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlPageBlocks implements AlPageBlocksInterface
{
    /**
     * @var int
     */
    protected $idPage = null;

    /**
     * @var int
     */
    protected $idLanguage = null;

    /**
     * @var \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface
     */
    protected $factoryRepository = null;

    /**
     * @var \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface
     */
    protected $blockRepository;

    protected $blocks = array();
    protected $alBlocks = array();

    /**
     * Constructor
     *
     * @param AlFactoryRepositoryInterface $factoryRepository
     *
     * @api
     */
    public function __construct(AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->factoryRepository = $factoryRepository;
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlotBlocks($slotName)
    {
        return (array_key_exists($slotName, $this->blocks)) ? $this->blocks[$slotName] : array();
    }

    /**
     * Returns an array which containt the handled page information
     *
     * @return array
     */
    public function getPageInformation()
    {
        return array(
            'idLanguage' => $this->idLanguage,
            'idPage' => $this->idPage,
        );
    }

    /**
     * Sets the blocks
     *
     * @param array|object $blocks A traversable list of blocks
     */
    public function setAlBlocks($blocks)
    {
        $this->alBlocks = $blocks;
        $this->arrangeBlocks();

        return $this;
    }

    /**
     * Returns the block types associated to this PageBlock
     *
     * @return array
     */
    public function getBlockTypes()
    {
        $types = array();
        foreach ($this->alBlocks as $block) {
            $type = $block->getType();
            if ( !in_array($type, $types)) {
                $types[] = $type;
            }
        }

        return $types;
    }

    /**
     * {@inheritdoc}
     */
    public function refresh($languageId, $pageId)
    {
        if (!is_numeric($languageId)) {
            throw new General\InvalidArgumentTypeException('exception_invalid_value_for_page_id');
        }

        if (!is_numeric($pageId)) {
            throw new General\InvalidArgumentTypeException('exception_invalid_value_for_language_id');
        }

        if ($languageId == $this->idLanguage && $pageId == $this->idPage) {
            return $this;
        }

        $this->idLanguage = $languageId;
        $this->idPage = $pageId;

        $this->alBlocks = $this->blockRepository->retrieveContents(array(1, $languageId), array(1, $pageId));
        $this->arrangeBlocks();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function add($slotName, $value, $position = null)
    {
        if (null !== $position && array_key_exists($position, $this->blocks[$slotName])) {
            $this->blocks[$slotName][$position] = $value;
        } else {
            $this->blocks[$slotName][] = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addRange(array $values, $override = false)
    {
        foreach ($values as $slotName => $contents) {
            if (array_key_exists($slotName, $this->blocks) && $override) {
                $this->clearSlotBlocks($slotName);
            }

            if (null !== $contents) {
                foreach ($contents as $content) {
                    $this->add($slotName, $content);
                }
            } else {
                $this->blocks[$slotName] = null;
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearSlotBlocks($slotName)
    {
        $this->checkSlotExists($slotName);

        $this->blocks[$slotName] = array();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearSlots()
    {
        $slots = array_keys($this->blocks);
        foreach ($slots as $slotName) {
            $this->clearSlotBlocks($slotName);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeSlot($slotName)
    {
        $this->checkSlotExists($slotName);

        unset($this->blocks[$slotName]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeSlots()
    {
        $this->blocks = array();

        return $this;
    }

    /**
     * Checks the requested slot exists
     *
     * @param  string                   $slotName
     * @throws InvalidArgumentException
     */
    protected function checkSlotExists($slotName)
    {
        if (!array_key_exists($slotName, $this->blocks)) {
            throw new InvalidArgumentException(sprintf('The slot "%s" does not exist. Nothing to clear', $slotName));
        }
    }

    /**
     * Arranges the blocks retrieved from the database into an array where blocks are
     * grouped by slot name
     */
    protected function arrangeBlocks()
    {
        $this->blocks = array();
        foreach ($this->alBlocks as $alBlock) {
            $this->blocks[$alBlock->getSlotName()][] = $alBlock;
        }
    }
}
