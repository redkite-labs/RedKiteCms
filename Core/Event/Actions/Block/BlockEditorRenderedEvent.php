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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Event\Actions\Block;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface;

/**
 * Defines the BlockEditorRenderedEvent event
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 * @deprecated since 1.1.0
 * @codeCoverageIgnore
 */
class BlockEditorRenderedEvent extends Event
{
    private $response = null;
    private $blockManager = null;

    /**
     * Construct
     *
     * @param \Symfony\Component\HttpFoundation\Response                                 $response
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface $blockManager
     */
    public function __construct(Response $response, AlBlockManagerInterface $blockManager)
    {
        $this->response = $response;
        $this->blockManager = $blockManager;
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
}
