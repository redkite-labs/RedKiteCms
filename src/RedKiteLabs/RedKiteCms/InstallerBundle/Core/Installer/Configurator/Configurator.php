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

namespace RedKiteCms\InstallerBundle\Core\Installer\Configurator;

use Symfony\Component\Yaml\Yaml;
use RedKiteCms\InstallerBundle\Core\Installer\Base\BaseOptions;
use RedKiteCms\InstallerBundle\Core\Generator\ConfigurationGenerator;

/**
 * Implements the base object to prepare RedKite CMS configuration
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class Configurator extends BaseOptions
{
    private $generator;
    
    public function __construct($kernelDir, array $options = array())
    {
        parent::__construct($kernelDir, $options);
                
        $this->generator = new ConfigurationGenerator($this->kernelDir, $this->options);
    }
    
    /**
     * Prepares the RedKite Cms configuration
     */
    public function configure()
    {
        $messages = $this->checkWritePermissions();
        if ( ! empty($messages)) {
            return $messages;
        }
        
        $this->checkPrerequisites();        
        $this->dsnBuilder->testConnection();
        $this->writeConfigurationParameters();
        $this->writeConfigurationFiles();
        $this->writeRoutes();
    }
    
    private function backUpFile($fileName)
    {
        $backupFile = $fileName . '.bak';
        
        // Have I already installed?
        if (file_exists($backupFile)) {
            
            // Restore original file
            unlink($fileName);
            $this->filesystem ->copy($backupFile, $fileName);
            
            return;
        }
        
        $this->filesystem ->copy($fileName, $backupFile);
    }
    
    private function writeConfigurationParameters()
    {
        $parametersFile = $this->kernelDir . '/config/parameters.yml';
        $this->checkFile($parametersFile);
        $this->backUpFile($parametersFile);
        
        $yaml = new Yaml();
        $params = $yaml->parse($parametersFile);
        
        $redKiteCmsParams = array
        (
            "parameters" => array(
                "rkcms_database_driver" => $this->driver,
                "rkcms_database_host" => $this->host,
                "rkcms_database_port" => $this->port,
                "rkcms_database_name" => $this->database,
                "rkcms_database_user" => $this->user,
                "rkcms_database_password" => $this->password,
            ),
        );
        
        $contents = $yaml->dump(array_merge_recursive($params, $redKiteCmsParams));
        file_put_contents($parametersFile, $contents);
    }

    private function writeConfigurationFiles()
    {
        $configFile = $this->kernelDir . '//config/config.yml';
        $this->checkFile($configFile);
        $this->backUpFile($configFile);
        
        // Writes the config.yml file
        $contents = file_get_contents($configFile);
        $deployBundle = $this->deployBundle;
        $contents = preg_replace_callback('/(bundles:[\s]+\[)([\w\s,]+)(\]+)/s', function ($matches) use ($deployBundle) {

            $bundles = trim($matches[2]);
            if (strpos($bundles, $deployBundle) !== false) {
               return $matches[1] . " " . $bundles . " " . $matches[3];
            }

            $value = ($bundles == "") ? $deployBundle : ", " . $deployBundle;
            $value =  $value . " ";

            return $matches[1] . " " . $bundles . $value . $matches[3];
        }, $contents);


        preg_match('/deploy_bundle:[\s]+' . $this->deployBundle . '/s', $contents, $match);
        if (empty($match)) {
            $contents .= "\nred_kite_labs_theme_engine:\n";
            $contents .= "    deploy_bundle: $this->deployBundle\n\n";
        }
        file_put_contents($configFile, $contents);

        $this->generator->generateConfigurations();
    }

    private function writeRoutes()
    {
        $configFile = $this->kernelDir . '/config/routing.yml';
        $this->checkFile($configFile);
        $this->backUpFile($configFile);

        $contents = file_get_contents($configFile);
        preg_match("/_$this->deployBundle/", $contents, $match);

        if(empty($match))
        {
            $config = "_$this->deployBundle:\n";
            $config .= "    resource: \"@$this->deployBundle/Resources/config/site_routing.yml\"\n\n";

            file_put_contents($configFile, $config . $contents);

            $siteRoutingFile = $this->vendorDir . "/../src/$this->companyName/$this->bundleName/Resources/config/site_routing.yml";
            file_put_contents($siteRoutingFile, "");
        }

        $this->generator->generateRoutes();
    }

    private function checkFile($fileName, $message = null)
    {
        if( ! is_file($fileName))
        {
            $message = (null === $message) ? PHP_EOL . 'The required ' . $fileName . ' file has not been found' : $message;

            throw new \RuntimeException($message);
        }
    }
}