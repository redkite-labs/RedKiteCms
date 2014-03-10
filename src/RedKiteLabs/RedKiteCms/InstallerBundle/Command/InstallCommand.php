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

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
/**
 * Installs RedKite Cms
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class InstallCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Bootstraps the database with default values. Be careful if you try to run this command on an existing database, because it recreates the database from the scratch')
            ->setDefinition(array(
                new InputOption('skip-db-creation', null, InputOption::VALUE_OPTIONAL, 'When true does not create the database', false),                
                new InputOption('skip-cache-clean', null, InputOption::VALUE_OPTIONAL, 'When true does not clean the cache at the end of the installation', false),
            ))
            ->setName('redkitecms:install');
    }

    /**
     * @see Command
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer(); 
        if ( ! $container->hasParameter('rkcms_database_driver')) {
            throw new \RuntimeException('It seems that you have not configured the RedKite CMS environments. Please run the redkitecms:configure command to properly setup those environments.');
        }
        
        $kernel = $container->get('kernel');
        if (strpos($kernel->getEnvironment(), 'rkcms') === false) {
            throw new \RuntimeException('This command must run in rkcms environment. Please run it again adding the --env=rkcms switch');
        }
        
        $className = '\RedKiteLabs\RedKiteCms\InstallerBundle\Core\Installer\DbBootstrapper\GenericDbBootstrapper';
        $specificClassName = sprintf('\RedKiteLabs\RedKiteCms\InstallerBundle\Core\Installer\DbBootstrapper\%sDbBootstrapper', ucfirst($container->getParameter('rkcms_database_driver')));
        if (class_exists($specificClassName)) {
            $className = $specificClassName;
        }
        
        $kernelRootDir = $kernel->getRootDir();
        $dbBoootstrapper = new $className($container, $container->getParameter('kernel.root_dir'));
        
        if ( ! $input->getOption('skip-db-creation')) {
            $dbBoootstrapper->createDatabase();
                   
            $output->writeln("<info>Database has been created</info>");
        }
        
        $application = $this->getApplication();
        $in = new ArrayInput(array(
            'command'        => 'propel:build',
            '--insert-sql'       => true,
        ));
        $buildCommand = $application->find('propel:build');
        $buildCommand->run($in, $output);
        $result = $dbBoootstrapper->bootstrap();
        if (is_string($result)) {
            $output->writeln("<error>$result</error>");
            
            return -1;
        }
        
        $symlink = (in_array(strtolower(PHP_OS), array('unix', 'linux'))) ? ' --symlink' : '';
        $in = new ArrayInput(array(
            'command'        => 'assets:install',
            'target'        => $kernelRootDir . '/../web',
            $symlink,
        ));
        $buildCommand = $application->find('assets:install');
        $buildCommand->run($in, $output);
        
        $in = new ArrayInput(array(
            'command'        => 'assetic:dump',
        ));
        $buildCommand = $application->find('assetic:dump');
        $buildCommand->run($in, $output);
        
        if ( ! $input->getOption('skip-cache-clean')) {
            $in = new ArrayInput(array(
                'command'        => 'ca:c',
            ));
            $buildCommand = $application->find('ca:c');
            $buildCommand->run($in, $output);
        }
    }
}