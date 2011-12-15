<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 * 
 */

namespace ThemeEngineCore\Event\PageRenderer;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use PageTreeCore\PageTree\AlPageTree;

/**
 * Defines the event dispatched before the page is rendered
 */
class BeforePageRenderingEvent extends Event
{
    protected $request = null;
    protected $pageTree = null;

    public function __construct(Request $request, AlPageTree $pageTree)
    {
        $this->request = $request;
        $this->pageTree = $pageTree;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getPageTree()
    {
        return $this->pageTree;
    }

    public function setPageTree($v)
    {
        if($this->pageTree != null) $this->pageTree = $v;
    }
}
