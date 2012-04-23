<?php
/*
 * This file is part of the AlphaLemonCMS InstallerBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infomation, please view the LICENSE
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
 * Installs the AlphaLemon CMS for your system
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
            ->setDescription('Installs the AlphaLemon CMS for your system')
            ->setDefinition(array(
                new InputOption('company', '', InputOption::VALUE_REQUIRED, 'Your company name, where the main bundle that manages your site lives'),
                new InputOption('bundle', '', InputOption::VALUE_REQUIRED, 'The bundle that manages your site'),
                new InputOption('driver', '', InputOption::VALUE_REQUIRED, 'The database driver to use', 'mysql'),
                new InputOption('host', '', InputOption::VALUE_OPTIONAL, 'The database host', 'localhost'),
                new InputOption('database', '', InputOption::VALUE_OPTIONAL, 'The database name', 'alphalemon'),
                new InputOption('port', '', InputOption::VALUE_OPTIONAL, 'The database port', '3306'),
                new InputOption('user', '', InputOption::VALUE_OPTIONAL, 'The database user', 'root'),
                new InputOption('password', null, InputOption::VALUE_OPTIONAL, 'The database password', ''),
                new InputOption('dsn', '', InputOption::VALUE_OPTIONAL, 'The dsn to connect the database'),
            ))
            ->setName('alphalemon:install-cms');
    }

    /**
     * @see Command
     * 
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installer = new Installer($this->getContainer()->getParameter('kernel.root_dir') . '/../vendor');
        $installer->install($input->getOption('company'), 
                $input->getOption('bundle'), 
                $input->getOption('dsn'), 
                $input->getOption('database'), 
                $input->getOption('user'), 
                $input->getOption('password'), 
                $input->getOption('driver'));
    }
    
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(array(
            '',
            'This command helps you to configure <comment>AlphaLemon CMS</comment> easily.',
            '',
            'AlphaLemon CMS requires an active bundle to work, which is the one that manages your website.' .
            'The bundle name is always made by the company name and a name that define the bundle itself.',
            '',
            'The <comment>AlphaLemon CMS sandbox</comment> comes with a predefined bundle called <comment>AcmeWebSiteBundle</comment>,' .
            'where <comment>Acme</comment> is the company name and <comment>WebSiteBundle</comment> is the name of the bundle. ' .
            'You may use another bundle, but it must be created and added to the AppKernel class before starting the CMS setup.',
            '',
            'AlphaLemon CMS requires a database and uses <comment>Propel</comment> as predefined ORM, so you will be asked to enter ' .
            'the parameters required to configure it. Though AlphaLemon CMS uses Propel, <comment>it doesn\'t mean that you must use ' .
            'that Orm for your project</comment>, if fact you may use your preferred Orm for your project',
            '',
            'If you prefer to use a <comment>web interface</comment> instead of the console, you may open your browser at http://localhost/install',
            '',
        ));
        
        $namespaceRegex = '/^(?:[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\\\?)+$/';
        $defaultValue = "Acme";
        $question = array( 
            "<info>Company name:</info> [<comment>$defaultValue</comment>] ",
        );
        
        $company = $this->askAndValidateRegex($output, $question, $defaultValue, $namespaceRegex);
        $input->setOption('company', $company);
        
        $defaultValue = "WebSiteBundle";
        $question = array(
            "<info>Bundle name:</info> [<comment>$defaultValue</comment>] ",
        );
        
        $bundle = $this->askAndValidateRegex($output, $question, $defaultValue, $namespaceRegex);
        $input->setOption('bundle', $bundle);
        
        $defaultValue = "mysql";
        $question = array(
            "<info>Database driver (mysql, pgsql, other):</info> [<comment>$defaultValue</comment>]  ",
        );
        $driver = $this->askAndValidateRegex($output, $question, $defaultValue, '/[a-z]+/');
        
        if(!in_array($driver, array('mysql', 'pgsql', 'other'))) {
            throw new \InvalidArgumentException(sprintf('Driver value must be one of the following: [mysql, pgsql, other]. You entered %s. Operation aborted', $driver));
        }
        
        $input->setOption('driver', $driver);
        
        if ($driver != 'other') {
            $defaultValue = "localhost";
            $question = array(
                "<info>Database host:</info> [<comment>$defaultValue</comment>]  ",
            );
            $host = $this->askAndValidateRegex($output, $question, $defaultValue, '/[a-z]+/');
            $input->setOption('host', $host);
            
            $defaultValue = "alphalemon";
            $question = array(
                "<info>Database name:</info> [<comment>$defaultValue</comment>]  ",
            );
            $database = $this->askAndValidateRegex($output, $question, $defaultValue);
            $input->setOption('database', $database);
            
            $defaultValue = ($driver == "mysql") ? 3306 : 5432;
            $question = array(
                "<info>Database port:</info> [<comment>$defaultValue</comment>]  ",
            );
            $port = $this->askAndValidateRegex($output, $question, $defaultValue, '/[0-9]+/');
            $input->setOption('port', $port);
            
            $defaultValue = "root";
            $question = array(
                "<info>Database user:</info> [<comment>$defaultValue</comment>]  ",
            );
            $user = $this->askAndValidateRegex($output, $question, $defaultValue, '/[a-z]+/');
            $input->setOption('user', $user);
            
            $defaultValue = "";
            $question = array(
                "<info>Database password:</info> [<comment>$defaultValue</comment>]  ",
            );
            $password = $this->askAndValidateRegex($output, $question, $defaultValue);
            $input->setOption('password', $password);
            
            
            
            switch($driver) {
                case 'mysql':
                    $dsn = sprintf('%s:host=%s;port=%s;dbname=%s', $driver, $host, $port, $database);
                    break;
                case 'pgsql':
                    $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;user=%s;password=%s', $driver, $host, $port, $database, $user, $password);
                    break;
            }
        }
        else {
            $defaultValue = "";
            $question = array(
                "<info>dsn:</info> [<comment>$defaultValue</comment>]  ",
            );
            $dsn = $this->askAndValidateRegex($output, $question, $defaultValue);            
            if (trim($dsn) == "") {
                throw new \InvalidArgumentException('The dsn option cannot be empty. Operation aborted');
            }
        }
        
        $input->setOption('dsn', $dsn);
    }
    
    private function askAndValidateRegex(OutputInterface $output, array $question, $defaultValue, $regex = null)
    {
        $value = $this->getHelper('dialog')->askAndValidate($output, $question, function($input) use($regex) {
            if (null !== $regex && !preg_match($regex, $input)) {
                throw new \InvalidArgumentException('The entered value contains invalid characters.');
            }
            
            return $input;
        }, 10, $defaultValue);
        
        return $value;
    }
    
}
