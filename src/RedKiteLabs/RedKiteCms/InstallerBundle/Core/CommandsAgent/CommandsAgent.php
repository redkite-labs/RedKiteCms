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

namespace RedKiteCms\InstallerBundle\Core\CommandsAgent;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;
use RedKiteCms\InstallerBundle\Core\Installer\Configurator\Configurator;
use RedKiteCms\InstallerBundle\Core\Installer\Environments\Environments;

/**
 * Implements the commands
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class CommandsAgent
{
    /**
     * Config command
     * 
     * @param type $container
     * @param array $options
     */
    public static function executeConfig($container, array $options)
    {
        $configuration = new Configurator($container->getParameter('kernel.root_dir'), $options);
        $configuration->configure();
    }
    
    /**
     * Cms environments command
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $options
     */
    public static function executeSetupCmsEnvironmentsCommand(ContainerInterface $container, array $options)
    {
        $installer = new Environments($container->getParameter('kernel.root_dir'), $options);
        $installer->setUp();
    }
    
    /**
     * Bootstrap database command
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $options
     * @param \Symfony\Component\Console\Application $application
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public static function executeBootstrapDb(ContainerInterface $container, array $options, Application $application, OutputInterface $output)
    {
        $className = '\RedKiteCms\InstallerBundle\Core\Installer\DbBootstrapper\GenericDbBootstrapper';        
        $specificClassName = sprintf('\RedKiteCms\InstallerBundle\Core\Installer\DbBootstrapper\%sDbBootstrapper', ucfirst($options['driver']));
        if (class_exists($specificClassName)) {
            $className = $specificClassName;
        }
        
        $kernelRootDir = $container->get('kernel')->getRootDir();
        $dbBoootstrapper = new $className($container, $container->getParameter('kernel.root_dir'), $options);
        $dbBoootstrapper->createDatabase();
        
        $in = new \Symfony\Component\Console\Input\ArrayInput(array(
            'command'        => 'propel:build',
            '--insert-sql'       => true,
        ));
        $buildCommand = $application->find('propel:build');
        $buildCommand->run($in, $output);
        
        $dbBoootstrapper->bootstrap();
    }   
    
    /**
     * Populates the database, installs and dumps assets, cleans the cache when needed
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $options
     */
    public static function populateAndClean(ContainerInterface $container, array $options)
    {
        array_walk($options, function(&$value, $key){if (null !== $value) $value = '--' . $key . '=' . $value;});
        
        $symlink = (in_array(strtolower(PHP_OS), array('unix', 'linux'))) ? ' --symlink' : '';
        $kernelRootDir = $container->get('kernel')->getRootDir();
        $commands = array(
            'redkitecms:database:bootstrap --no-interaction --env=rkcms ' . implode(" ", $options) => null,
            sprintf('assets:install %s %s --env=rkcms',  $kernelRootDir . '/../web',  $symlink) => null,
            'assetic:dump --env=rkcms' => null,
        );

        $writeInstalledFile = true;
        $installedFile = $container->get('kernel')->getRootDir() . '/Resources/.cms_installed';
        if (file_exists($installedFile)) {
            $commands['ca:c --env=rkcms'] = null;
            $writeInstalledFile = false;
        }

        $commandsProcessor = new \RedKiteLabs\RedKiteCmsBundle\Core\CommandsProcessor\AlCommandsProcessor($kernelRootDir);
        $commandsProcessor->executeCommands($commands, function($type, $buffer){ echo $buffer; });
        
        if ($writeInstalledFile) {
            touch($installedFile);
        }
    }
}