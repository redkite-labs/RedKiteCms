<?php
/**
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

class DeployController extends Base\BaseController
{
    public function localAction()
    {
        try {
            $deployer = $this->container->get('alpha_lemon_cms.local_deployer');
            $deployer->deploy();
            $response = $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => 'The site has been deployed'));
            
            $symlink = (in_array(strtolower(PHP_OS), array('unix', 'linux'))) ? '--symlink' : '';
            $command = sprintf('assets:install %s %s', $this->container->getParameter('alpha_lemon_cms.web_folder_full_path'), $symlink);
            $commandProcessor = $this->container->get('alpha_lemon_cms.commands_processor');
            $commandProcessor->executeCommands(array(
                $command => null,
                'assetic:dump' => null,
                'cache:clear --env=prod' => null,
            ));

            return $response;
        } catch (\Exception $e) {
            return $this->renderDialogMessage($e->getMessage());
        }
    }
}