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
 * Bootstraps the database
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BootstrapDbCommand extends Base\CommandBase
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Bootstraps the database with default values. Be careful if you try to run this command on an existing database, because it recreates the database from the scratch')
            ->setDefinition($baseOptions = $this->getBaseOptions())
            ->setName('redkitecms:database:bootstrap');
    }

    /**
     * @see Command
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        CommandsAgent::executeBootstrapDb($this->getContainer(), $this->inputOptionsToArray($input), $this->getApplication(), $output);
        
        $output->writeln("<info>The database has been bootstrapped</info>");
    }
    
    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(array(
            '',
            'This command bootstraps the database used by <comment>RedKite CMS</comment>',
            '',
            'Be careful when you run this command because it destroys the database and',            
            'recreates it from the scratch.',
            '',
        ));
        
        $this->baseInteraction($input, $output);
    }
}
