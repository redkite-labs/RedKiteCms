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

namespace RedKiteLabs\RedKiteCmsBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Implements the actions for the ElFinder bundle
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlCmsElFinderController extends ContainerAware
{
    public function showFilesManagerAction()
    {
        return $this->container->get('templating')->renderResponse('RedKiteCmsBundle:Elfinder:file_manager.html.twig', array(
            'enable_yui_compressor' => $this->container->getParameter('alpha_lemon_cms.enable_yui_compressor'),
        ));
    }

    public function connectMediaAction()
    {
        $this->connect('el_finder_media_connector');
    }

    protected function connect($service)
    {
        $connector = $this->container->get($service);
        $connector->connect();
    }
}
