<?php
/*
 * This file is part of the RedKite CMS InstallerBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteCms\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use RedKiteCms\InstallerBundle\Core\Form\RedKiteCmsParametersType;
use RedKiteCms\InstallerBundle\Core\Installer\Installer;

use RedKiteCms\InstallerBundle\Core\CommandsAgent\CommandsAgent;

/**
 * Implements the controller to install RedKite CMS
 *
 * @author alphalemon <webmaster@redkite-labs.com>
 */
class InstallerController extends Controller
{
    public function installAction()
    {
        $type = new RedKiteCmsParametersType();
        $form = $this->container->get('form.factory')->create(
            $type, array('company' => 'Acme',
                        'bundle' => 'WebSiteBundle',
                        'host' => 'localhost',
                        'database' => 'redkite',
                        'user' => 'root',
                        'driver' => 'mysql',
                        'port' => '3306',
        ));

        $request = $this->container->get('request');
        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $options = $form->getData();
                
                $messages = CommandsAgent::executeConfig($this->container, $options);
                if (null === $messages) {
                    CommandsAgent::executeSetupCmsEnvironmentsCommand($this->container, $options);   
                    
                    ob_start();
                    CommandsAgent::populateAndClean($this->container, $options);
                    $log = ob_get_contents();
                    ob_end_clean();
                    
                    $scheme = $request->getScheme().'://'.$request->getHttpHost();
                    return $this->render('RedKiteCmsInstallerBundle:Installer:install_success.html.twig', array(
                        'scheme'    => $scheme,
                        'log' => urldecode($log),
                    ));
                }
                
                foreach ($messages as $message) {
                    $this->container->get('session')->getFlashBag()->add('error', strip_tags($message));
                }
            }
        }

        return $this->render('RedKiteCmsInstallerBundle:Installer:install.html.twig', array(
            'form'    => $form->createView(),
        ));
    }
}

