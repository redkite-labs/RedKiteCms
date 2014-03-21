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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Factory;

use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\Slot;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\PageBlocksInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\ClassNotFoundException;

/**
 * Creates a slot converter from a known repeated status
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class SlotsConverterFactory implements SlotsConverterFactoryInterface
{
    /** @var null|PageBlocksInterface */
    protected $pageContentsContainer = null;
    /** @var null|FactoryRepositoryInterface */
    protected $factoryRepository = null;

    /**
     * Constructor
     *
     * @param PageBlocksInterface        $pageContentsContainer
     * @param FactoryRepositoryInterface $factoryRepository
     *
     * @api
     */
    public function __construct(PageBlocksInterface $pageContentsContainer, FactoryRepositoryInterface $factoryRepository)
    {
        $this->pageContentsContainer = $pageContentsContainer;
        $this->factoryRepository = $factoryRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @param  Slot                                                                                      $slot
     * @param  string                                                                                      $newRepeatedStatus
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\SlotConverterInterface
     * @throws ClassNotFoundException
     */
    public function createConverter(Slot $slot, $newRepeatedStatus)
    {
        $className = '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\SlotConverterTo' . ucfirst(strtolower($newRepeatedStatus));
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
