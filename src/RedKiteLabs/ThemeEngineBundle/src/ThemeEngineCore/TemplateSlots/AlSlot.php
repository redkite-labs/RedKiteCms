<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 */

namespace ThemeEngineCore\TemplateSlots;

/**
 * This class represents a slot on a page. The slot is the last html tag, usually a DIV tag,  
 * where the displayed content lives.
 * 
 * @author AlphaLemon
 */
class AlSlot
{
    private $repeated = 'page';
    private $defaultText = null;
    private $slotName;
    private $contentType = 'Text';

    /**
     * Constructor
     * 
     * @param string    $slotName   The slot name
     * @param array     $options    An array of options, which are [repeated, contentType, defaultText]
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
        $this->defaultText = "This is the default text for the slot " . $slotName;
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

    public function setDefaultText($v)
    {
        $this->defaultText = $v;
    }

    public function getDefaultText()
    {
        return $this->defaultText;
    }

    public function setContentType($v)
    {
        $this->contentType = ucfirst($v);
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * 
     * @param array $options    An array of options
     */
    protected function fromArray(array $options)
    {
        $repeated = (array_key_exists('repeated', $options)) ? $options['repeated'] : 'page';
        $this->setRepeated($repeated);
        
        $repeated = (array_key_exists('contentType', $options)) ? ucfirst($options['contentType']) : 'Text';
        $this->setContentType($repeated);
        
        if(array_key_exists('defaultText', $options)) $this->setDefaultText($options['defaultText']);
    }
}