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

namespace AlphaLemon\AlphaLemonCmsBundle\Command\Update;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Upgrades to pre-alpha1
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class UpdateToPreAlpha1Command extends Base\BaseUpdateCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Updates the database to AlphaLemon CMS Pre-Alpha 1')
            ->setDefinition(array(
                new InputArgument('dsn', InputArgument::REQUIRED, 'The dsn to connect the database'),
                new InputOption('user', '', InputOption::VALUE_OPTIONAL, 'The database user', 'root'),
                new InputOption('password', null, InputOption::VALUE_OPTIONAL, 'The database password', ''),
                new InputOption('driver', null, InputOption::VALUE_OPTIONAL, 'The database driver', 'mysql'),
            ))
            ->setName('alphalemon:update-to-pre-alpha-1');
    }

    /**
     * @see Command
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = new \PropelPDO($input->getArgument('dsn'), $input->getOption('user'), $input->getOption('password'));
        $sqlFile = sprintf(__DIR__ . '/../../Resources/dbupdate/%s/AlphaLemonCmsPreAlpha1.sql', $input->getOption('driver'));
        $this->executeQueries($connection, $sqlFile);
        
        $sourcePath = $this->getContainer()->getParameter('kernel.root_dir') . '/../web/bundles/alphalemoncms/uploads';
        $targetPath = $this->getContainer()->getParameter('kernel.root_dir') . '/../web/uploads';
        if (!is_dir($targetPath)) {
            $fs = new Filesystem();
            $fs->mirror($sourcePath, $targetPath);
        }
        
        $output->writeln('<info>The database has been updated.</info>');
    }
}
