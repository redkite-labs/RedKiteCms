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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerInterface;

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

    public function __construct(Request $request, AlBlockManagerInterface $blockManager, Response $response = null)
    {
        $this->request = $request;
        $this->blockManager = $blockManager;
        $this->response = $response;
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

    public function setBlockManager(AlBlockManagerInterface $v)
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