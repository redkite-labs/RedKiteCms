<?php
/*
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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\LanguageModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\PageModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\BlockModelInterface;

/**
 * 
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlSlotsConverterFactory implements AlSlotsConverterFactoryInterface
{ 
    protected $slot;
    protected $pageContentsContainer;
    protected $languageModel;
    protected $pageModel;
    protected $blockModel;
    
    /**
     * Constructor 
     * @param ContainerInterface    $container
     * @param string                $activeThemeName  The active theme
     */
    public function __construct(AlSlot $slot, AlPageContentsContainerInterface $pageContentsContainer, LanguageModelInterface $languageModel, PageModelInterface $pageModel, BlockModelInterface $blockModel)
    {
        $this->slot = $slot;
        $this->pageContentsContainer = $pageContentsContainer;
        $this->languageModel = $languageModel;
        $this->pageModel = $pageModel;
        $this->blockModel = $blockModel;
    }
    
    /**
     * Create the slot converter
     * 
     * @param string $newRepeatedStatus
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\className
     * @throws \InvalidArgumentException 
     */
    public function createConverter($newRepeatedStatus)
    {
        $className = '\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterTo' . ucfirst(strtolower($newRepeatedStatus));
        if(!class_exists($className)) {
            throw new \InvalidArgumentException("Converter does not exist");
        }
        
        $this->slot->setRepeated($newRepeatedStatus); // = new AlSlot($slotName, array('repeated' => $newRepeatedStatus)); 
        
        return new $className($this->slot, $this->pageContentsContainer, $this->languageModel, $this->pageModel, $this->blockModel);
    }
}