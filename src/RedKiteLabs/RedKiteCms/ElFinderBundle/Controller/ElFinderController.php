<?php
/*
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteLabs <webmaster@RedKiteLabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://RedKiteLabs.com
 * 
 * @license    MIT License
 */

namespace RedKiteLabs\RedKiteCms\ElFinderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ElFinderController extends Controller
{
    public function showAction()
    {
        return $this->render('RedKiteLabsElFinderBundle:ElFinder:show.html.twig'); 
    }

    public function connectAction()
    {
        $connector = $this->container->get('el_finder_connector');
        $connector->connect();
    }
}
