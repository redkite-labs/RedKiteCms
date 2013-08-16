<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
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

namespace RedKiteLabs\ThemeEngineBundle\Core\Rendering\Event\PageRenderer;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defines the event dispatched before the page is rendered
 */
class BeforePageRenderingEvent extends Event
{
    protected $response = null;

    /**
     * Constructor
     * 
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function __construct(Response $response)
    {
        $this->setResponse($response);
    }

    /**
     * Gets the response
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets the response
     * 
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}