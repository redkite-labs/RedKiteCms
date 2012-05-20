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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidator;

/**
 * The base class that defines a content manager object
 * 
 * Several entities are considered "content" by AlphaLemon CMS: 
 * 
 *   - Languages
 *   - Pages
 *   - Seo attributes
 *   - Templates
 *   - Slots
 *   - Blocks
 * 
 * All of them extends this class
 *
 * @api
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlContentManagerBase
{
    protected $dispatcher;
    protected $translator;
    protected $validator;

    /**
     * Constructor
     * 
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface $translator
     * @param AlParametersValidatorInterface $validator 
     */
    public function __construct(EventDispatcherInterface $dispatcher, TranslatorInterface $translator, AlParametersValidatorInterface $validator = null)
    {
        $this->dispatcher = $dispatcher;
        $this->translator = $translator;
        $this->validator = (null === $validator) ? new AlParametersValidator($translator) : $validator;
    }
    
    /**
     * Sets the event dispatcher object
     * 
     * @param EventDispatcherInterface $dispatcher
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase (for fluent API)
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        
        return $this;
    }
    
    /**
     * Sets the tranlator object
     * 
     * @param TranslatorInterface $translator
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase (for fluent API)
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        
        return $this;
    }
    
    /**
     * Sets the parameters validator object
     * 
     * @api
     * @param AlParametersValidatorInterface $validator
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase 
     */
    public function setValidator(AlParametersValidatorInterface $validator)
    {
        $this->validator = $validator;    
    
        return $this;
    }
        
    
    /**
     * Returns the Event dispatcher object
     * 
     * @return EventDispatcherInterface 
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
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
     * Returns the ParameterValidator object
     * 
     * @api
     * @return TranslatorInterface 
     */
    public function getValidator()
    {
        return $this->validator;
    }
}