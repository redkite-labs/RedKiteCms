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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\Page;

use Symfony\Component\Validator\Validator;
use Symfony\Component\DependencyInjection\Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Defines the pages form fields
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class Page
{
    /**
     * @Assert\MaxLength(255)
     * @Assert\NotBlank(message = "The page name value should not be blank")
     */
    protected $pageName;

    /**
     * @Assert\NotBlank(message = "The template value should not be blank")
     */
    protected $template;

    /**
     * @Assert\Type("boolean")
     */
    protected $isHome;

    /**
     * @Assert\Type("boolean")
     */
    protected $isPublished;

    public function getPageName()
    {
        return $this->pageName;
    }

    public function setPageName($v)
    {
        $this->pageName = $v;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($v)
    {
        $this->template = $v;
    }

    public function getIsHome()
    {
        return $this->isHome;
    }

    public function setIsHome($v)
    {
        $this->isHome = $v;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function setIsPublished($v)
    {
        $this->isPublished = $v;
    }
}