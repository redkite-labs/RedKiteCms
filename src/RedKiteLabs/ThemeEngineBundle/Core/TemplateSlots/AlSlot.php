<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 */

namespace AlphaLemon\ThemeEngineBundle\Core\TemplateSlots;

/**
 * This class represents a slot on a page. The slot is the last html tag, usually a DIV tag,  
 * where the displayed content lives.
 * 
 * @author AlphaLemon
 */
class AlSlot
{
    private $repeated = 'page';
    private $slotName;
    private $blockType = 'Text';
    
    private $htmlContent = null;
    private $externalJavascript = null;
    private $internalJavascript = null;
    private $externalStylesheet = null;
    private $internalStylesheet = null;

    /**
     * Constructor
     * 
     * @param string    $slotName   The slot name
     * @param array     $options    An array of options, which are [repeated, blockType, htmlContent]
     */
    public function __construct($slotName, array $options = null)
    {
        if(null === $slotName)
        {
            throw new \InvalidArgumentException("The slotName param could not be null");
        }
        
        if(!is_string($slotName))
        {
            throw new \InvalidArgumentException("The slotName param must be a string");
        }
        
        $this->slotName = $slotName;
        $this->htmlContent = "This is the default text for the slot " . $slotName;
        if(null !== $options) $this->fromArray($options);
    }

    public function getSlotName()
    {
        return $this->slotName;
    }

    public function setRepeated($v)
    {
        $this->repeated = $v;
    }

    public function getRepeated()
    {
        return $this->repeated;
    }
    
    public function setBlockType($v)
    {
        $this->blockType = ucfirst($v);
    }

    public function getBlockType()
    {
        return $this->blockType;
    }
    
    public function setHtmlContent($v)
    {
        $this->htmlContent = $v;
    }

    public function getHtmlContent()
    {
        return $this->htmlContent;
    }
    
    public function setExternalJavascript($v)
    {
        $this->externalJavascript = $v;
    }

    public function getExternalJavascript()
    {
        return $this->externalJavascript;
    }
    
    public function setInternalJavascript($v)
    {
        $this->internalJavascript = $v;
    }

    public function getInternalJavascript()
    {
        return $this->internalJavascript;
    }
    
    public function setExternalStylesheet($v)
    {
        $this->externalStylesheet = $v;
    }

    public function getExternalStylesheet()
    {
        return $this->externalStylesheet;
    }
    
    public function setInternalStylesheet($v)
    {
        $this->internalStylesheet = $v;
    }

    public function getInternalStylesheet()
    {
        return $this->internalStylesheet;
    }

    /**
     * 
     * @param array $options    An array of options
     */
    protected function fromArray(array $options)
    {
        $repeated = (array_key_exists('repeated', $options)) ? $options['repeated'] : 'page';
        $this->setRepeated($repeated);
        
        $blockType = (array_key_exists('blockType', $options)) ? ucfirst($options['blockType']) : 'Text';
        $this->setBlockType($blockType);
        
        if(array_key_exists('htmlContent', $options)) $this->setHtmlContent($options['htmlContent']);
        if(array_key_exists('externalJavascript', $options)) $this->setExternalJavascript($options['externalJavascript']);
        if(array_key_exists('internalJavascript', $options)) $this->setInternalJavascript($options['internalJavascript']);
        if(array_key_exists('externalStylesheet', $options)) $this->setExternalStylesheet($options['externalStylesheet']);
        if(array_key_exists('internalStylesheet', $options)) $this->setInternalStylesheet($options['internalStylesheet']);
    }
}