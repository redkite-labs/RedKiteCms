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

namespace AlphaLemon\CmsInstallerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use AlphaLemon\CmsInstallerBundle\Core\Installer\Installer;

/**
 * Populates the database after a fresh install
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class InstallCmsCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            /*->setDescription('Populates the database with default values. Be careful if you try to run this command on an existind database, because it is resets and repopulates the database itself')
            ->setDefinition(array(
                new InputArgument('dsn', InputArgument::REQUIRED, 'The dsn to connect the database'),
                new InputOption('user', '', InputOption::VALUE_OPTIONAL, 'The database user', 'root'),
                new InputOption('password', null, InputOption::VALUE_OPTIONAL, 'The database password', ''),
            ))*/
            ->setName('alphalemon:install-cms');
    }

    /**
     * @see Command
     * 
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installer = new Installer($this->getContainer()->getParameter('kernel.root_dir') . '/../vendor');
        $installer->install('Acme', 'WebSiteBundle', 'mysql:host=localhost;dbname=alphalemon', 'alphalemon', 'root', '', 'mysql');
    }
}
