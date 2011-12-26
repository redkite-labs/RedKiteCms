<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 */

namespace AlphaLemon\ElFinderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ElFinderController extends Controller
{
    public function showAction()
    {
        return $this->render('AlphaLemonElFinderBundle:ElFinder:show.html.twig'); 
    }

    public function connectAction()
    {
        $connector = $this->container->get('el_finder_connector');
        $connector->connect();
    }
}
