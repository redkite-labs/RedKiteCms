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

namespace RedKiteCms\InstallerBundle\Command\Base;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Defines the base command to install RedKite Cms
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class CommandBase extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function getBaseOptions()
    {
        return array(
            new InputOption('company', '', InputOption::VALUE_REQUIRED, 'Your company name, where the main bundle that manages your site lives', 'Acme'),
            new InputOption('bundle', '', InputOption::VALUE_REQUIRED, 'The bundle that manages your site', 'WebSiteBundle'),
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
            "company" => $input->getOption('company'),
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
            "<info>Database driver (mysql, pgsql, sqlite):</info> [<comment>$defaultValue</comment>]  ",
        );
        $driver = $this->askAndValidateRegex($output, $question, $defaultValue, '/[a-z]+/');

        if( ! in_array($driver, array('mysql', 'pgsql', 'sqlite'))) {
            throw new \InvalidArgumentException(sprintf('Driver value must be one of the following: [mysql, pgsql, sqlite]. You entered %s. Operation aborted', $driver));
        }
        $input->setOption('driver', $driver);

        $defaultValue = "localhost";
        $question = array(
            "<info>Database host:</info> [<comment>$defaultValue</comment>]  ",
        );
        $host = $this->askAndValidateRegex($output, $question, $defaultValue, '/[a-z0-9\.]+/');
        $input->setOption('host', $host);

        $defaultValue = "redkite";
        $question = array(
            "<info>Database name:</info> [<comment>$defaultValue</comment>]  ",
        );
        $database = $this->askAndValidateRegex($output, $question, $defaultValue);
        $input->setOption('database', $database);

        if ($driver != 'sqlite') {
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
        }
            
        $defaultValue = "";
        $question = array(
            "<info>Website url:</info> [<comment>$defaultValue</comment>] ",
        );
        
        $defaultValue = "";
        $question = array(
            "<info>Website url:</info> [<comment>$defaultValue</comment>] ",
        );            
        $website = $this->askAndValidateRegex($output, $question, $defaultValue, '/^http:\/\/?[^\/]+\/$/i', 'Website url must start with "http://" and must end with "/"');
        $input->setOption('website-url', $website);    
    }

    protected function askAndValidateRegex(OutputInterface $output, array $question, $defaultValue, $regex = null, $message = 'The entered value contains invalid characters')
    {
        $value = $this->getHelper('dialog')->askAndValidate($output, $question, function($input) use($regex, $message) {
            if (null !== $regex && !preg_match($regex, $input)) {
                throw new \InvalidArgumentException($message);
            }

            return $input;
        }, 10, $defaultValue);

        return $value;
    }
}