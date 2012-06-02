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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Translator;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * AlTranslator
 * 
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlTranslator implements AlTranslatorInterface
{    
    protected $translator;
    
    /**
     * Constructor
     * 
     * @param TranslatorInterface $translator 
     */
    public function __construct(TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
    }
    
    /**
     * Sets the tranlator object
     * 
     * @param TranslatorInterface $translator
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Translator\AlTranslator 
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        
        return $this;
    }
    
    /**
     * Returns the Translator object
     * 
     * @return TranslatorInterface 
     */
    public function getTranslator()
    {
        return $this->translator;
    }
    
    /**
     * Translates the message when the translator has been set or returns the message when null
     * 
     * @param string $message
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     * 
     * @return string 
     */
    public function translate($message, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        return (null !== $this->translator) ? $this->translator->trans($message, $parameters, $domain, $locale) : $message;
    }
}