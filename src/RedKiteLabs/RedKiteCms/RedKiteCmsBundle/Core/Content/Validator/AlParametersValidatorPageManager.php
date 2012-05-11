<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * AlParametersValidator
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlParametersValidatorPageManager extends AlParametersValidator
{
    public function __construct(TranslatorInterface $translator, $siteLanguages, $sitePages)
    {
        parent::__construct($translator);
        
        $this->setSiteLanguages($siteLanguages);
        $this->setSitePages($sitePages);
    }
    
    public function setSiteLanguages($v) 
    {
        if (!$this->isTraversable($v)) {
            throw new General\InvalidParameterTypeException('The site languages parameter must be traversable');
        }
        
        $this->siteLanguages = $v;
    }
    
    public function setSitePages($v) 
    {
        if (!$this->isTraversable($v)) {
            throw new General\InvalidParameterTypeException('The site pages parameter must be traversable');
        }
        
        $this->sitePages = $v;
    }
    
    public function getSiteLanguages() 
    {
        return $this->siteLanguages;
    }
    
    public function getSitePages() 
    {
        return $this->sitePages;
    }
    
    public function hasLanguages()
    {
        return (count($this->siteLanguages) > 0) ? true : false;
    }
    
    public function hasPages($min = 0)
    {
        return (count($this->sitePages) > $min) ? true : false;
    }
    
    private function isTraversable($v)
    {
        return (!is_array($v) && !$v instanceof Traversable) ? false : true;
    }
}