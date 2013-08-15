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

namespace RedKiteLabs\RedKiteCmsBundle\Controller\Base;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends ContainerAware
{
    protected $translator = null;
    
    protected function renderDialogMessage($message, $statusCode = 404)
    {
        $response = new Response();
        $response->setStatusCode($statusCode);

        return $this->container->get('templating')->renderResponse('RedKiteCmsBundle:Dialog:dialog.html.twig', array('message' => $message), $response);
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
}
