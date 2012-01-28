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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\Language;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\Validator;
use Symfony\Component\DependencyInjection\Exception;

/**
 * Defines the languages form fields
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class Language
{
    /**
     * @assert:NotBlank(message = "The language name value should not be blank")
     */
    protected $language;

    /**
     * @assert:AssertType("boolean")
     */
    protected $isMain = false;
    

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($v)
    {
        $this->language = $v;
    }

    public function getIsMain()
    {
        return $this->isMain;
    }

    public function setIsMain($v)
    {
        $this->isMain = $v;
    }
}