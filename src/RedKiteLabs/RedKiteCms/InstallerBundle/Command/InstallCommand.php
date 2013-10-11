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

namespace RedKiteCms\InstallerBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use RedKiteCms\InstallerBundle\Core\CommandsAgent\CommandsAgent;
/**
 * Installs RedKite Cms
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class InstallCommand extends Base\CommandBase
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Bootstraps the database with default values. Be careful if you try to run this command on an existing database, because it recreates the database from the scratch')
            ->setDefinition($baseOptions = $this->getBaseOptions())
            ->setName('redkitecms:install');
    }

    /**
     * @see Command
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $options = $this->inputOptionsToArray($input);
        $messages = CommandsAgent::executeConfig($this->getContainer(), $options);
        $this->writeMessages($output, $messages, true);
        
        CommandsAgent::executeSetupCmsEnvironmentsCommand($this->getContainer(), $options);
        CommandsAgent::populateAndClean($this->getContainer(), $options);
    }
    
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(array(
            '',
            'This command helps you to configure <comment>RedKite CMS</comment> easily.',
            '',
            'Before you begin, you\'ll need to create a bundle. In Symfony2, a bundle is nothing more than ' .
            'a directory that houses everything related to a specific feature: in this case it will house your website.',
            '',
            'The bundle name should be made by the company name and a name that define the bundle itself.',
            '',
            'The <comment>RedKite CMS sandbox</comment> comes with a predefined bundle called <comment>AcmeWebSiteBundle</comment>,' .
            'where <comment>Acme</comment> is the company name and <comment>WebSiteBundle</comment> is the name of the bundle. ',
            '',
            'When you want to use another bundle, you must create it using the <comment>generate:bundle</comment> command and then ',
            'you must add it to the AppKernel class, before starting the CMS setup.',
            '',
            'RedKite CMS requires a database and uses <comment>Propel</comment> as predefined ORM, so you will be asked to enter ' .
            'the parameters required to configure it. Though RedKite CMS uses Propel, <comment>it doesn\'t mean that you must use ' .
            'that Orm for your project</comment>, if fact you may use your preferred Orm for your project',
            '',
            '<comment>This setup helps configures in one shot a mysql, postgres and sqlite database</comment>. If you need to use ' .
            'another one, please refer to the README file that comes with the sandbox.',
            '',
            'If you prefer to use a <comment>web interface</comment> instead of the console, you may open your browser at http://localhost/install',
            '',
        ));
        
        $this->baseInteraction($input, $output);
    }
}