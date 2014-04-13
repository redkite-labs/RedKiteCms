<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Actions\Block;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerInterface;

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
     * @param Request                 $request
     * @param BlockManagerInterface $blockManager
     * @param Response                $response
     *
     * @api
     */
    public function __construct(Request $request, BlockManagerInterface $blockManager, Response $response = null)
    {
        $this->request = $request;
        $this->blockManager = $blockManager;
        $this->response = $response;
    }

    /**
     * Returns the handled request object
     *
     * @return Request
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
     * @param Request $request
     *
     * @api
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns the handled block manager object
     *
     * @return BlockManagerInterface
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
     * @param BlockManagerInterface $manager
     *
     * @api
     */
    public function setBlockManager(BlockManagerInterface $manager)
    {
        $this->blockManager = $manager;
    }

    /**
     * Returns the handled response object
     *
     * @return Response
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
     * @param Response $response
     *
     * @api
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
