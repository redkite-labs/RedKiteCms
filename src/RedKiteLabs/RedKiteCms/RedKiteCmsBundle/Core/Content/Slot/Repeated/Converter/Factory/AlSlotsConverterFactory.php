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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Factory;

use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\AlSlot;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocksInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\ClassNotFoundException;

/**
 * Creates a slot converter from a known repeated status
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlSlotsConverterFactory implements AlSlotsConverterFactoryInterface
{
    /** @var null|AlPageBlocksInterface */
    protected $pageContentsContainer = null;
    /** @var null|AlFactoryRepositoryInterface */
    protected $factoryRepository = null;

    /**
     * Constructor
     *
     * @param AlPageBlocksInterface        $pageContentsContainer
     * @param AlFactoryRepositoryInterface $factoryRepository
     *
     * @api
     */
    public function __construct(AlPageBlocksInterface $pageContentsContainer, AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->pageContentsContainer = $pageContentsContainer;
        $this->factoryRepository = $factoryRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @param  AlSlot                                                                                      $slot
     * @param  string                                                                                      $newRepeatedStatus
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterInterface
     * @throws ClassNotFoundException
     */
    public function createConverter(AlSlot $slot, $newRepeatedStatus)
    {
        $className = '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterTo' . ucfirst(strtolower($newRepeatedStatus));
        if (!class_exists($className)) {
            $exception = array(
                'message' => 'exception_class_not_defined',
                'parameters' => array(
                    '%className%' => $className,
                ),
            );
            throw new ClassNotFoundException(json_encode($exception));
        }

        $slot->setRepeated($newRepeatedStatus);

        return new $className($slot, $this->pageContentsContainer, $this->factoryRepository);
    }
}
