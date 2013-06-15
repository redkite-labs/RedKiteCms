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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory;

use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocksInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ClassNotFoundException;

/**
 * Creates a slot converter from a known repeated status
 * 
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class AlSlotsConverterFactory implements AlSlotsConverterFactoryInterface
{
    protected $pageContentsContainer = null;
    protected $factoryRepository = null;
    
    /**
     * Constructor
     * 
     * @param \AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocksInterface $pageContentsContainer
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
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
     * @param \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot $slot
     * @param string $newRepeatedStatus
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\className
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ClassNotFoundException
     */
    public function createConverter(AlSlot $slot, $newRepeatedStatus)
    {
        $className = '\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterTo' . ucfirst(strtolower($newRepeatedStatus));
        if (!class_exists($className)) {
            $exception = array(
                'message' => 'The class %className% that shoud define a new Slot Converter does not exist',
                'parameters' => array(
                    '%className%' => $className,
                ),
                'domain' => 'exceptions',
            );
            throw new ClassNotFoundException(json_encode($exception));
        }

        $slot->setRepeated($newRepeatedStatus);

        return new $className($slot, $this->pageContentsContainer, $this->factoryRepository);
    }
}
