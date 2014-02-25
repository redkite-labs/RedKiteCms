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

namespace RedKiteLabs\RedKiteCmsBundle\Controller\Base;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends ContainerAware
{
    /** @var null|\RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslatorInterface */
    protected $translator = null;

    protected function renderDialogMessage($message, $statusCode = 404)
    {
        $response = new Response();
        $response->setStatusCode($statusCode);

        return $this->render('RedKiteCmsBundle:Dialog:dialog.html.twig', array('message' => $message), $response);
    }

    protected function translate($message, array $params = array(), $catalogue = "RedKiteCmsBundle")
    {
        if (null === $this->translator) {
            $this->translator = $this->container->get('red_kite_cms.translator');
        }

        return $this->translator->translate(
            $message,
            $params,
            $catalogue
        );
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string|\Symfony\Component\Form\FormTypeInterface $type    The built type of the form
     * @param mixed                                            $data    The initial data for the form
     * @param array                                            $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    public function renderView($view, array $parameters = array())
    {
        return $this->container->get('templating')->render($view, $parameters);
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->container->get('templating')->renderResponse($view, $parameters, $response);
    }

    /**
     * Create a repository for the specified model
     *
     * @param  string                                                                       $modelName The model name
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\RepositoryInterface A repository instance
     */
    protected function createRepository($modelName)
    {
        return $this->container->get('red_kite_cms.factory_repository')->createRepository($modelName);
    }
}
