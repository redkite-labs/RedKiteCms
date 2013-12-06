<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
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

namespace RedKiteLabs\ThemeEngineBundle\Core\Rendering\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Defines the base controller application should inherit from
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class FrontendController extends BaseFrontendController
{
    public function showAction()
    {
        $templatesFolder = $this->container->getParameter('red_kite_labs_theme_engine.deploy.templates_folder');

        return $this->renderPage($templatesFolder);
    }

    public function stageAction()
    {
        $stageTemplatesFolder = $this->container->getParameter('red_kite_labs_theme_engine.deploy.stage_templates_folder');

        return $this->renderPage($stageTemplatesFolder, 'RedKiteLabsThemeEngineBundle:Stage:stage.html.twig');
    }

    protected function renderPage($templatesFolder)
    {
        try {
            $request = $this->container->get('request');

            $language = $request->getLocale();
            $page = $request->get('page');
            $deployBundle = $this->container->getParameter('red_kite_labs_theme_engine.deploy_bundle');
            $baseTemplate = $this->container->getParameter('red_kite_labs_theme_engine.base_template');

            try {
                $template = sprintf('%s:%s:%s/%s.html.twig', $deployBundle, $templatesFolder, $language, $page);
                $response = $this->render($template, array('base_template' => $baseTemplate));
            } catch (\InvalidArgumentException $ex) { // Backward compatibility
                // @codeCoverageIgnoreStart
                $template = sprintf('%s:%s:%s.html.twig', $deployBundle, $language, $page);
                $response = $this->render($template, array('base_template' => $baseTemplate));
                // @codeCoverageIgnoreEnd
            }

            // Dispatches the pre rendering events for current language and page
            $response = $this->dispatchEvents($request, $response);

            return $response;
        } catch (\Exception $ex) {
            $statusCode = (method_exists($ex, 'getStatusCode')) ? $ex->getStatusCode() : 500;
            $response = new Response();
            $response->setStatusCode($statusCode);

            $values = array(
                'status_code' => $statusCode,
                'message' => $ex->getMessage(),
            );

            return $this->container->get('templating')->renderResponse('RedKiteLabsThemeEngineBundle:Error:error.html.twig', $values, $response);
        }
    }
}
