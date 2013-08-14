<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Form\Page;

/**
 * Defines the pages form fields
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class Page
{
    protected $pageName;
    protected $template;
    protected $isHome;
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
