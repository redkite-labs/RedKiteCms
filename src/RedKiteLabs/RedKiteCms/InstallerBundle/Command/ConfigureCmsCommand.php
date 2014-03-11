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

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Installer\Configurator\Configurator;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Installer\Environments\Environments;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Validator\Validator;

/**
 * Prepares the RedKite CMS configuration files
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ConfigureCmsCommand extends ContainerAwareCommand
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
        try {
            $container = $this->getContainer();
            $options = $this->inputOptionsToArray($input);

            $configuration = new Configurator($container->get('kernel'), $options);
            $this->writeMessages($output, $configuration->configure(), true);        
            $output->writeln("<info>The configuration has been written</info>");

            $installer = new Environments($container->getParameter('kernel.root_dir'), $options);
            $this->writeMessages($output, $installer->setUp(), true);
            $output->writeln("<info>The RedKite CMS environments have been set up</info>");
        }
        catch(\Exception $ex) {
            $output->writeln("<error>" . $ex ->getMessage() . "</error>");
            
            return -1;
        }
    }
    
    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(array(
            '',
            'This prepares the <comment>RedKite CMS</comment> configuration',
            '',
        ));
        
        $this->baseInteraction($input, $output);
    }
    
    /**
     * @see Command
     */
    protected function getBaseOptions()
    {
        return array(
            new InputOption('bundle', '', InputOption::VALUE_REQUIRED, 'The bundle that manages your site', 'AcmeWebSiteBundle'),
            new InputOption('driver', '', InputOption::VALUE_REQUIRED, 'The database driver to use', 'mysql'),
            new InputOption('host', '', InputOption::VALUE_REQUIRED, 'The database host', 'localhost'),
            new InputOption('database', '', InputOption::VALUE_REQUIRED, 'The database name', 'redkite'),
            new InputOption('port', '', InputOption::VALUE_REQUIRED, 'The database port', '3306'),
            new InputOption('user', '', InputOption::VALUE_REQUIRED, 'The database user', null),
            new InputOption('password', null, InputOption::VALUE_OPTIONAL, 'The database password', ''),          
            new InputOption('website-url', '', InputOption::VALUE_REQUIRED, 'The website url. This is required to build the website sitemap'),
        );
    }
    
    
    protected function inputOptionsToArray(InputInterface $input)
    {
        return array(
            "bundle" => $input->getOption('bundle'),
            "driver" => $input->getOption('driver'),
            "host" => $input->getOption('host'),
            "port" => $input->getOption('port'),
            "database" => $input->getOption('database'),
            "user" => $input->getOption('user'),
            "password" => $input->getOption('password'),
            "website-url" => $input->getOption('website-url'),
        );
    }
    
    protected function baseInteraction(InputInterface $input, OutputInterface $output)
    {
        $defaultValue = "AcmeWebSiteBundle";
        $question = array(
            "<info>Bundle name:</info> [<comment>$defaultValue</comment>] ",
        );
        
        $bundle = $this->getHelper('dialog')->askAndValidate($output, $question, array('RedKiteLabs\RedKiteCms\InstallerBundle\Core\Validator\Validator', 'validateBundleName'), false, $defaultValue);
        $input->setOption('bundle', $bundle);
        
        Validator::validateDeployBundle($this->getContainer()->get('kernel')->getRootDir(), $bundle);
        
        $defaultValue = "mysql";
        $question = array(
            "<info>Database driver (mysql, pgsql, sqlite):</info> [<comment>$defaultValue</comment>]  ",
        );
        $driver = $this->getHelper('dialog')->askAndValidate($output, $question, array('RedKiteLabs\RedKiteCms\InstallerBundle\Core\Validator\Validator', 'validateDriver'), false, $defaultValue);
        $input->setOption('driver', $driver);

        $defaultValue = "localhost";
        $question = array(
            "<info>Database host:</info> [<comment>$defaultValue</comment>]  ",
        );
        $host = $this->getHelper('dialog')->askAndValidate($output, $question, array('RedKiteLabs\RedKiteCms\InstallerBundle\Core\Validator\Validator', 'validateHost'), false, $defaultValue);
        $input->setOption('host', $host);

        $databaseRegex = '/^(?:[a-zA-Z_\-$\&\x7f-\xff][a-zA-Z0-9_\-$\&\x7f-\xff]*\\\?)+$/';
        $defaultValue = "redkite";
        $question = array(
            "<info>Database name:</info> [<comment>$defaultValue</comment>]  ",
        );
        $database = $this->getHelper('dialog')->askAndValidate($output, $question, array('RedKiteLabs\RedKiteCms\InstallerBundle\Core\Validator\Validator', 'validateDatabaseName'), false, $defaultValue);
        $input->setOption('database', $database);

        if ($driver != 'sqlite') {
            $defaultValue = 3306;
            if ($driver == "pgsql") { 
                $defaultValue = 5432;
            }
            $question = array(
                "<info>Database port:</info> [<comment>$defaultValue</comment>]  ",
            );
            $port = $this->getHelper('dialog')->askAndValidate($output, $question, array('RedKiteLabs\RedKiteCms\InstallerBundle\Core\Validator\Validator', 'validatePort'), false, $defaultValue);
            $input->setOption('port', $port);

            $defaultValue = "root";
            if ($driver == "pgsql") { 
                $defaultValue = "postgres";
            }
            $question = array(
                "<info>Database user:</info> [<comment>$defaultValue</comment>]  ",
            );
            $user = $this->getHelper('dialog')->askAndValidate($output, $question, array('RedKiteLabs\RedKiteCms\InstallerBundle\Core\Validator\Validator', 'validateUser'), false, $defaultValue);
            $input->setOption('user', $user);

            $defaultValue = "";
            $question = array(
                "<info>Database password:</info> [<comment>$defaultValue</comment>]  ",
            );
            $password = $this->getHelper('dialog')->askAndValidate($output, $question, array('RedKiteLabs\RedKiteCms\InstallerBundle\Core\Validator\Validator', 'validatePassword'), false, $defaultValue);
            $input->setOption('password', $password);
        }
            
        $defaultValue = "";
        $question = array(
            "<info>Website url:</info> [<comment>$defaultValue</comment>] ",
        );         
        $website = $this->getHelper('dialog')->askAndValidate($output, $question, array('RedKiteLabs\RedKiteCms\InstallerBundle\Core\Validator\Validator', 'validateUrl'), false, $defaultValue);
        $input->setOption('website-url', $website);    
    }
    
    protected function writeMessages(OutputInterface $output, array $messages = null, $stopExecution = false)
    {
        if ( null !== $messages && ! empty($messages)) { 
            foreach ($messages as $message) {
                $output->writeln($message);
            }
            
            if ($stopExecution) {
                exit;
            }
        }
    }
}
