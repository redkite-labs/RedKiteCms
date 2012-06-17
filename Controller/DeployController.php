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
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DeployController extends Controller
{
    public function localAction()
    {
        try
        {
            $deployer = $this->container->get('alphalemon_cms.local_deployer');
            $deployer->deploy();
            // TODO
            // Asset install
            // Assetic dump
            // Clear cache
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => 'The site has been deployed'));
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }
}