<?php
/*
 * This file is part of the RedKite CMS InstallerBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use RedKiteCms\InstallerBundle\Core\Form\RedKiteCmsParametersType;
use RedKiteCms\InstallerBundle\Core\Installer\Installer;

/**
 * Implements the controller to install RedKiteCms CMS
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class InstallerController extends Controller
{
    public function installAction()
    {
        $type = new RedKiteCmsParametersType();
        $form = $this->container->get('form.factory')->create($type, array('company' => 'Acme',
                                                                           'bundle' => 'WebSiteBundle',
                                                                           'host' => 'localhost',
                                                                           'database' => 'redkite',
                                                                           'user' => 'root',
                                                                           'driver' => 'mysql',
                                                                           'port' => '3306',
            ));

        $request = $this->container->get('request');
        $scheme = $request->getScheme().'://'.$request->getHttpHost();
        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $dsn = $data['dsn'];
                if (trim($data['dsn']) == "") {
                    switch($data['driver']) {
                        case 'mysql':
                            $dsn = sprintf('%s:host=%s;port=%s;dbname=%s', $data['driver'], $data['host'], $data['port'], $data['database']);
                            break;
                        case 'pgsql':
                            $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;user=%s;password=%s', $data['driver'], $data['host'], $data['port'], $data['database'], $data['user'], $data['password']);
                            break;
                    }
                }

                if(!empty($dsn)) {
                   try {
                       
                       $response = $this->render('RedKiteCmsInstallerBundle:Installer:install_success.html.twig', array(
                            'scheme'    => $scheme,
                        ));
                       
                       ob_start();
                       $installer = new Installer($this->container->getParameter('kernel.root_dir') . '/../vendor');
                       $installer->install($data['company'], $data['bundle'], $dsn, $data['database'], $data['user'], $data['password'], $data['driver'], $data['url']);
                       ob_end_clean();
                       
                       return $response;
                    }
                    catch(\Exception $ex) {
                       $this->container->get('session')->getFlashBag()->add('error', $ex->getMessage());
                    }
                }
                else {
                    $this->container->get('session')->getFlashBag()->add('error', "It seems that any data source has been configured");
                }
            }
        }

        return $this->render('RedKiteCmsInstallerBundle:Installer:install.html.twig', array(
            'form'    => $form->createView(),
        ));
    }
}

