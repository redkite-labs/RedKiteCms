<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
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

namespace AlphaLemon\AlphaLemonCmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Implements the actions for the ElFinder bundle
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlCmsElFinderController extends Controller
{
    public function showFilesManagerAction()
    {
        return $this->render('AlphaLemonCmsBundle:Elfinder:file_manager.html.twig');
    }

    public function connectMediaAction()
    {
        $this->connect('el_finder_media_connector');
    }

    public function connectStylesheetsAction()
    {
        $this->connect('el_finder_css_connector');
    }

    public function connectJavascriptsAction()
    {
        $this->connect('el_finder_js_connector');
    }

    protected function connect($service)
    {
        $connector = $this->container->get($service);
        $connector->connect();
    }
}
