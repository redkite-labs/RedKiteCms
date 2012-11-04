<?php
/**
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 * 
 */

namespace AlphaLemon\ThemeEngineBundle\Core\Rendering\Event\PageRenderer;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defines the event dispatched before the page is rendered
 */
class BeforePageRenderingEvent extends Event
{
    protected $response = null;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}


/*
 * class BeforePageRenderingEvent extends Event
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
 * 
 */