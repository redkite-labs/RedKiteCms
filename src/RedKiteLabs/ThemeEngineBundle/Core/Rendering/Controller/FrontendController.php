<?php
/*
 * This file is part of the AlphaLemon FrontendBundle and it is distributed
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

namespace AlphaLemon\ThemeEngineBundle\Core\Rendering\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AlphaLemon\ThemeEngineBundle\Core\Event\PageRenderer\BeforePageRenderingEvent;
use AlphaLemon\ThemeEngineBundle\Core\Event\PageRendererEvents;

use Symfony\Component\HttpFoundation\Response;

/**
 * Defines the base controller application should inherit from
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class FrontendController extends BaseFrontendController
{
    public function showAction()
    {
        return $this->renderPage();
    }

    protected function renderPage()
    {
        try {
            $request = $this->container->get('request');

            $template = sprintf('%s:%s:%s.html.twig', $this->container->getParameter('alpha_lemon_theme_engine.deploy_bundle'), $request->getLocale(), $request->get('page'));
            $response = $this->render($template, array('base_template' => $this->container->getParameter('alpha_lemon_theme_engine.base_template')));

            // Dispatches the pre rendering events for current language and page
            $response = $this->dispatchEvents($request, $response);

            return $response;
        }
        catch(\Exception $ex) {
            $response = new Response();
            $response->setStatusCode(404);
            $response->setContent("CUSTOM ERROR PAGE");

            return $response;
        }
    }
}

