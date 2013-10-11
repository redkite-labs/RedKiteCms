<?php
/*
 * This file is part of the RedKite CMS InstallerBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infomation, please view the LICENSE
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
 * Prepares the RedKite CMS configuration files
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ConfigureCmsCommand extends Base\CommandBase
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Installs the RedKite CMS for your system')
            ->setDefinition($this->getBaseOptions())
            ->setName('redkitecms:configure');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = CommandsAgent::executeConfig($this->getContainer(), $this->inputOptionsToArray($input));
        
        $this->writeMessages($output, $messages, true);
        
        $output->writeln("<info>The configuration has been written</info>");
    }
    
    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(array(
            '',
            'This prepares the <comment>RedKite CMS</comment>  configuration',
            '',
        ));
        
        $this->baseInteraction($input, $output);
    }
}