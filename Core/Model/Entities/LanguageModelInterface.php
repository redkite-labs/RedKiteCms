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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Model\Entities;

/**
 * ModelInterface
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface LanguageModelInterface {
    
    /**
     * Fetches a language record using its primary key
     * 
     * @param int       The primary key
     * @return object   The fetched object
     */
    public function fromPK($id);
    
    /**
     * Fetches the main language record
     * 
     * @return object   The fetched object
     */
    public function mainLanguage();
    
    /**
     * Fetches a language record from its name
     * 
     * @param string    The language name
     * @return object   The fetched object
     */
    public function fromLanguageName($languageName);
    
    /**
     *  
     */
    public function activeLanguages();
    
    /**
     * Fetches the first language record
     * 
     * @return object   The fetched object
     */
    public function firstOne();
}