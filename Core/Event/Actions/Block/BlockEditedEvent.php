<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Event\Actions\Block;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface;

/**
 * Defines the BlockEditedEvent event
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class BlockEditedEvent extends Event
{
    private $request = null;
    private $blockManager = null;
    private $response;

    /**
     * Construct
     *
     * @param \Symfony\Component\HttpFoundation\Request                                  $request
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface $blockManager
     * @param \Symfony\Component\HttpFoundation\Response                                 $response
     *
     * @api
     */
    public function __construct(Request $request, AlBlockManagerInterface $blockManager, Response $response = null)
    {
        $this->request = $request;
        $this->blockManager = $blockManager;
        $this->response = $response;
    }

    /**
     * Returns the handled request object
     *
     * @return \Symfony\Component\HttpFoundation\Request
     *
     * @api
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets the request
     *
     * @param \Symfony\Component\HttpFoundation\Request $v
     *
     * @api
     */
    public function setRequest(Request $v)
    {
        $this->request = $v;
    }

    /**
     * Returns the handled block manager object
     *
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface
     *
     * @api
     */
    public function getBlockManager()
    {
        return $this->blockManager;
    }

    /**
     * Sets the block manager
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface $v
     *
     * @api
     */
    public function setBlockManager(AlBlockManagerInterface $v)
    {
        $this->blockManager = $v;
    }

    /**
     * Returns the handled response object
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @api
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets the response
     *
     * @param \Symfony\Component\HttpFoundation\Response $v
     *
     * @api
     */
    public function setResponse(Response $v)
    {
        $this->response = $v;
    }
}
