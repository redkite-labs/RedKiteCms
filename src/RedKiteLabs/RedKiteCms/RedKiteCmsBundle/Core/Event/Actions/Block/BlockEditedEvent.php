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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * Defines the BlockEditedEvent event
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class BlockEditedEvent extends Event
{
    private $request = null;
    private $blockManager = null;
    private $response;

    public function __construct(Request $request, AlBlockManager $blockManager, Response $response = null)
    {
        $this->request = $request;
        $this->blockManager = $blockManager;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Request $v)
    {
        $this->request = $v;
    }

    public function getBlockManager()
    {
        return $this->blockManager;
    }

    public function setBlockManager(AlBlockManager $v)
    {
        $this->blockManager = $v;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Response $v)
    {
        $this->response = $v;
    }
}

/*
 * class BlockEditedEvent extends Event
{
    private $response = null;
    private $request = null;
    private $blockManager = null;

    public function __construct(Request $request, Response $response, AlBlockManager $blockManager)
    {
        $this->request = $request;
        $this->response = $response;
        $this->blockManager = $blockManager;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Request $v)
    {
        $this->request = $v;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Response $v)
    {
        $this->response = $v;
    }

    public function getBlockManager()
    {
        return $this->blockManager;
    }

    public function setBlockManager(AlBlockManager $v)
    {
        $this->blockManager = $v;
    }
}
 */
