<?php
/*
 * This file is part of the AlphaLemon FrontendBundle and it is distributed
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

namespace AlphaLemon\ThemeEngineBundle\Core\Rendering\SlotContent;

/**
 * AlSlotContent stores the information related to the content to replace
 * ona slot
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlSlotContent
{
    private $slotName = null;
    private $content = null;
    private $replace = null;
    
    /**
     * Returns the name of the slot
     * 
     * @return string 
     */
    public function getSlotName()
    {
        return $this->slotName;
    }
    
    /**
     * Sets the name of the slot
     * 
     * @return \AlphaLemon\ThemeEngineBundle\Core\Rendering\SlotContent\AlSlotContent 
     */
    public function setSlotName($slotName)
    {
        if (!is_string($slotName)) {
            throw new \InvalidArgumentException(sprintf('The slot name passed to "%s" must be a string', get_class($this)));
        }        
        $this->slotName = $slotName;
        
        return $this;
    }
    
    /**
     * Returns the content to replace
     * 
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Sets the content to replace
     * 
     * @return \AlphaLemon\ThemeEngineBundle\Core\Rendering\SlotContent\AlSlotContent 
     */
    public function setContent($content)
    {
        if (!is_string($content)) {
            throw new \InvalidArgumentException('The content passed to "AlphaLemon\FrontendBundle\Core\SlotContent\AlSlotContent" must be a string');
        }  
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * When true the content of the slot must be replaced, false injected
     * 
     * @return Boolean 
     */
    public function isReplacing()
    {
        return $this->replace;
    }
    
    /**
     * The slotContent is configured to replace the content on the slot
     * 
     * @return \AlphaLemon\ThemeEngineBundle\Core\Rendering\SlotContent\AlSlotContent 
     */
    public function replace()
    {
        $this->replace = true;
        
        return $this;
    }
    
    /**
     * The slotContent is configured to inject the content into the slot
     * 
     * @return \AlphaLemon\ThemeEngineBundle\Core\Rendering\SlotContent\AlSlotContent 
     */
    public function inject()
    {
        $this->replace = false;
        
        return $this;
    }
}