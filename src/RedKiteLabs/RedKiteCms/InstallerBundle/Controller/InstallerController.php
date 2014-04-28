<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Form\RedKiteCmsParametersType;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Installer\Installer;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\CommandsAgent\CommandsAgent;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\CommandsProcessor\CommandsProcessor;


/**
 * Implements the controller to install RedKite CMS
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class InstallerController extends Controller
{
    public function installAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $type = new RedKiteCmsParametersType();
        $form = $this->container->get('form.factory')->create($type, array(
            'bundle' => 'AcmeWebSiteBundle',
            'host' => 'localhost',
            'database' => 'redkite',
            'user' => 'root',
            'driver' => 'mysql',
            'port' => '3306',
        ));

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $options = $form->getData();
                
                array_walk($options, function(&$value, $key){ if (null !== $value) $value = '--' . $key . '=' . $value; });

                $cleanCache = false;
                $writeInstalledFile = true;
                $kernelRootDir = $this->container->get('kernel')->getRootDir();
                $installedFile = $kernelRootDir . '/Resources/.cms_installed';
                if (file_exists($installedFile)) {
                    $cleanCache = true;
                    $writeInstalledFile = false;
                }

                ob_start();
                $commands = array(
                    'redkitecms:configure --no-interaction ' . implode(" ", $options) => null,
                );
                $commandsProcessor = new CommandsProcessor($kernelRootDir);
                $result = $commandsProcessor->executeCommands($commands, function($type, $buffer){ echo $buffer; });  

                $commands = array(
                    'redkitecms:install --env=rkcms --skip-cache-clean=' . $cleanCache => null,
                );
                $commandsProcessor = new CommandsProcessor($kernelRootDir, 'rkconsole');
                $result = $commandsProcessor->executeCommands($commands, function($type, $buffer){ echo $buffer; });                
                $log = ob_get_contents();
                ob_end_clean();
                
                $template = 'RedKiteCmsInstallerBundle:Installer:install_failed.html.twig';
                if ($result) {
                    $template = 'RedKiteCmsInstallerBundle:Installer:install_success.html.twig';
                    
                    if ($writeInstalledFile) {
                        touch($installedFile);
                    }
                }

                $scheme = $request->getScheme().'://'.$request->getHttpHost();
                return $this->render($template, array(
                    'scheme'    => $scheme,
                    'log' => urldecode($log),
                ));
            }
        }

        return $this->render('RedKiteCmsInstallerBundle:Installer:install.html.twig', array(
            'form'    => $form->createView(),
        ));
    }
}
