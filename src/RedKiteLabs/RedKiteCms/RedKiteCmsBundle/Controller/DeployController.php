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
use Symfony\Component\Process\Process;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AlphaLemon\AlphaLemonCmsBundle\Core\CommandsProcessor\AlCommandsProcessor;

class DeployController extends Controller
{
    public function localAction()
    {
        try
        {
            $deployer = $this->container->get('alphalemon_cms.local_deployer');
            $deployer->deploy();

            $response = $this->render('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => 'The site has been deployed'));

            $appDir = $this->container->get('kernel')->getRootDir();
            $symlink = (in_array(strtolower(PHP_OS), array('unix', 'linux'))) ? '--symlink' : '';
            $command = sprintf('assets:install %s %s', $this->container->getParameter('alphalemon_cms.web_folder'), $symlink);

            $commandProcessor = new AlCommandsProcessor($appDir);
            $commandProcessor->executeCommands(array(
                $command => null,
                'assetic:dump' => null,
                'cache:clear' => null,
            ));

            return $response;
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }
}