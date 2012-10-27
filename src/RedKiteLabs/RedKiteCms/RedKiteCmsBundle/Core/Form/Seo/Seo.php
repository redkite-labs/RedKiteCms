<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\Seo;

/**
 * Defines the page attributes form fields
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class Seo
{
    protected $idPage;
    protected $idLanguage;
    protected $permalink;
    protected $title;
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
