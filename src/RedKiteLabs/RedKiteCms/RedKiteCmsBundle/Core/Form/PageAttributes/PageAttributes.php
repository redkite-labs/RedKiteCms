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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\PageAttributes;

use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Defines the page attributes form fields
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class PageAttributes
{
    /**
     * @Assert\NotBlank(message = "The page id, which the page attributes belongs, is mandatory")
     */
    protected $idPage;

    /**
     *
     * @Assert\NotBlank(message = "The language id, which the page attributes belongs, is mandatory")
     */
    protected $idLanguage;
    
    /**
     * @Assert\MaxLength(255)
     */
    protected $permalink;

    /**
     * @Assert\MaxLength(60)
     * @Assert\NotBlank(message = "The metatag title value should not be blank")
     */
    protected $title;

    /**
     * @Assert\NotBlank(message = "The metatag description value should not be blank")
     */
    protected $description;
    protected $keywords;

    public function getIdPage()
    {
        return $this->idPage;
    }

    public function setIdPage($v)
    {
        $this->idPage = $v;
    }

    public function getIdLanguage()
    {
        return $this->idLanguage;
    }

    public function setIdLanguage($v)
    {
        $this->idLanguage = $v;
    }

    public function getPermalink()
    {
        return $this->permalink;
    }

    public function setPermalink($v)
    {
        $this->permalink = $v;
    }
    
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($v)
    {
        $this->title = $v;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($v)
    {
        $this->description = $v;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setKeywords($v)
    {
        $this->keywords = $v;
    }
}