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
 * AlSlotContent
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlSlotContent
{
    private $slotName = null;
    private $content = null;
    private $replace = null;
    
    public function getSlotName()
    {
        return $this->slotName;
    }
    
    public function setSlotName($slotName)
    {
        if (!is_string($slotName)) {
            throw new \InvalidArgumentException('The slot name passed to "AlphaLemon\FrontendBundle\Core\SlotContent\AlSlotContent" must be a string');
        }        
        $this->slotName = $slotName;
        
        return $this;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function setContent($content)
    {
        if (!is_string($content)) {
            throw new \InvalidArgumentException('The content passed to "AlphaLemon\FrontendBundle\Core\SlotContent\AlSlotContent" must be a string');
        }  
        $this->content = $content;
        
        return $this;
    }
    
    public function isReplacing()
    {
        return $this->replace;
    }
    
    public function replace()
    {
        $this->replace = true;
        
        return $this;
    }
    
    
    public function inject()
    {
        $this->replace = false;
        
        return $this;
    }
}