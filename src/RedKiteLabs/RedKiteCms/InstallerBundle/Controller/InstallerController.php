<?php
/*
 * This file is part of the AlphaLemonCMS InstallerBundle and it is distributed
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

namespace AlphaLemon\CmsInstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use AlphaLemon\CmsInstallerBundle\Core\Form\AlphaLemonCmsParametersType;
use AlphaLemon\AlphaLemonCmsBundle\Core\Installer\Installer;

/**
 * Implements the controller to install AlphaLemon CMS
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class InstallerController extends Controller
{
    public function installAction()
    {
        $type = new AlphaLemonCmsParametersType();
        $form = $this->container->get('form.factory')->create($type, array('company' => 'Acme', 
                                                                           'bundle' => 'WebSiteBundle', 
                                                                           'host' => 'localhost',
                                                                           'database' => 'alphalemon_composer_20',
                                                                           'user' => 'root',
                                                                           'driver' => 'mysql',
                                                                           'port' => '3306',
            ));

        $request = $this->container->get('request');
        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
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
                        $installer = new Installer($this->container->getParameter('kernel.root_dir') . '/../vendor');
                        $installer->install($data['company'], $data['bundle'], $dsn, $data['database'], $data['user'], $data['password'], $data['driver']);

                        return new RedirectResponse('/alcms.php/backend/en/index');
                    }
                    catch(\Exception $ex) {
                        $this->get('session')->setFlash('error', $ex->getMessage());
                    } 
                }
                else {
                    $this->get('session')->setFlash('error', "It seems that any data source has been configured");
                }
            }
        }

        return $this->render('AlphaLemonCmsInstallerBundle:Installer:install.html.twig', array(
            'form'    => $form->createView(),
        ));
    }
}

