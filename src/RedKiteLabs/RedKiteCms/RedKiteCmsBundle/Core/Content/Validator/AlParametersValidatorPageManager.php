<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageModelation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;

use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\LanguageModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\PageModelInterface;

/**
 * AlParametersValidator
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlParametersValidatorPageManager extends AlParametersValidator
{
    protected $languageModel;
    protected $pageModel;
    
    public function __construct(LanguageModelInterface $languageModel, PageModelInterface $pageModel)
    {
        $this->languageModel = $languageModel;
        $this->pageModel = $pageModel;
    }
    
    public function setLanguageModel(LanguageModelInterface $v) 
    {
        $this->languageModel = $v;
    }
    
    public function setPageModel(PageModelInterface $v) 
    {
        $this->pageModel = $v;
    }
    
    public function getLanguageModel() 
    {
        return $this->languageModel;
    }
    
    public function getPageModel() 
    {
        return $this->pageModel;
    }
    
    public function hasLanguages()
    {
        return (count($this->languageModel->activeLanguages()) > 0) ? true : false;
    }
    
    public function hasPages($min = 0)
    {
        return (count($this->pageModel->activePages()) > $min) ? true : false;
    }
    
    public function pageExists($pageName)
    {
        return (count($this->pageModel->fromPageName($pageName)) > 0) ? true : false;
    }
}