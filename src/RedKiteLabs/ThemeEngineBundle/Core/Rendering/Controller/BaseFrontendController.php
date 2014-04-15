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

namespace RedKiteLabs\ThemeEngineBundle\Core\Rendering\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use RedKiteLabs\ThemeEngineBundle\Core\Rendering\Event\PageRenderer\BeforePageRenderingEvent;
use RedKiteLabs\ThemeEngineBundle\Core\Rendering\Event\PageRendererEvents;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defines the base controller application should inherit from
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseFrontendController extends Controller
{
    protected $dispatcher;
    protected $event;

    protected function dispatchEvents(Request $request, Response $response)
    {
        $this->dispatcher = $this->container->get('event_dispatcher');

        // Dispatches the pre rendering events for current language and page
        $this->event = new BeforePageRenderingEvent($response);
        $this->dispatchSiteEvent();
        $this->dispatchCurrentLanguageEvent($request);
        $this->dispatchCurrentPageEvent($request);

        return $this->event->getResponse();
    }

    protected function dispatchSiteEvent()
    {
        $this->dispatcher->dispatch(PageRendererEvents::BEFORE_RENDER_PAGE, $this->event);
    }

    protected function dispatchCurrentLanguageEvent(Request $request)
    {
        $eventName = sprintf('page_renderer.before_%s_rendering', $request->getLocale());
        $this->dispatcher->dispatch($eventName, $this->event);
    }

    protected function dispatchCurrentPageEvent(Request $request)
    {
        $eventName = sprintf('page_renderer.before_%s_rendering', $request->get('page'));
        $this->dispatcher->dispatch($eventName, $this->event);
    }
}
