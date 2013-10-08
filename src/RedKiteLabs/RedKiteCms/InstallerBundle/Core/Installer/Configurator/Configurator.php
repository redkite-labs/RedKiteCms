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

/**
 * Implements the base object to prepare RedKite CMS configuration
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class Configurator extends BaseOptions
{
    /**
     * Prepares the RedKite Cms configuration
     */
    public function configure()
    {
        $this->checkPrerequisites();
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
        $parametersFile = $this->vendorDir . '/../app/config/parameters.yml';
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
        $this->overrideConfigFile($parametersFile, $contents);
    }

    private function writeConfigurationFiles()
    {
        $configFile = $this->vendorDir . '/../app/config/config.yml';
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

        $configFile = $this->vendorDir . '/../app/config/config_rkcms.yml';
        $contents = "imports:\n";
        $contents .= "    - { resource: parameters.yml }\n";
        $contents .= "    - { resource: \"@RedKiteCmsBundle/Resources/config/config_rkcms.yml\" }\n";
        $contents .= "    - { resource: \"@RedKiteCmsBundle/Resources/config/security.yml\" }";
        $contents .= $this->writeDatabaseConfiguration();
        $contents .= "red_kite_cms:\n";
        $contents .= "    website-url: " . $this->websiteUrl;
        $this->overrideConfigFile($configFile, $contents);
        
        $configFile = $this->vendorDir . '/../app/config/config_rkcms_dev.yml';
        $contents = "imports:\n";
        $contents .= "    - { resource: config_rkcms.yml }\n";
        $contents .= "    - { resource: \"@RedKiteCmsBundle/Resources/config/config_rkcms_dev.yml\" }";
        $this->overrideConfigFile($configFile, $contents);
        
        $configFile = $this->vendorDir . '/../app/config/config_rkcms_test.yml';
        $contents = "imports:\n";
        $contents .= "    - { resource: config_rkcms_dev.yml }\n";
        $contents .= "    - { resource: \"@RedKiteCmsBundle/Resources/config/config_rkcms_test.yml\" }";
        $contents .= $this->writeDatabaseConfiguration();
        $this->overrideConfigFile($configFile, $contents);
        
        $configFile = $this->vendorDir . '/../app/config/config_stage.yml';
        $contents = "imports:\n";
        $contents .= "    - { resource: config.yml }\n\n";
        $contents .= "framework:\n";
        $contents .= "    router:   { resource: \"%kernel.root_dir%/config/routing_stage.yml\" }";
        $this->overrideConfigFile($configFile, $contents);

        $configFile = $this->vendorDir . '/../app/config/config_stage_dev.yml';
        $contents = "imports:\n";
        $contents .= "    - { resource: config_dev.yml }\n\n";
        $contents .= "framework:\n";
        $contents .= "    router:   { resource: \"%kernel.root_dir%/config/routing_stage_dev.yml\" }";
        $this->overrideConfigFile($configFile, $contents);
    }
    
    private function overrideConfigFile($configFile, $contents)
    {
        if (is_file($configFile)) {
            unlink($configFile);
        }

        file_put_contents($configFile, $contents);
    }

    private function writeRoutes()
    {
        $configFile = $this->vendorDir . '/../app/config/routing.yml';
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

        $configFile = $this->vendorDir . '/../app/config/routing_rkcms.yml';
        $contents = "_rkcms:\n";
        $contents .= "    resource: \"@RedKiteCmsBundle/Resources/config/routing_rkcms.yml\"";
        $this->overrideConfigFile($configFile, $contents);

        $configFile = $this->vendorDir . '/../app/config/routing_rkcms_dev.yml';
        $contents = "_rkcms:\n";
        $contents .= "    resource: \"@RedKiteCmsBundle/Resources/config/routing_rkcms_dev.yml\"\n\n";
        $contents .= "_rkcms_dev:\n";
        $contents .= "    resource: routing_rkcms.yml";
        $this->overrideConfigFile($configFile, $contents);
        
        $configFile = $this->vendorDir . '/../app/config/routing_rkcms_test.yml';
        $contents = "_rkcms_dev:\n";
        $contents .= "    resource: resource: routing_rkcms_dev.yml\n\n";
        $contents .= "_al_text_bundle:\n";
        $contents .= "    resource: \"@TextBundle/Resources/config/routing/routing.xml\"";        
        $this->overrideConfigFile($configFile, $contents);
        
        $configFile = $this->vendorDir . '/../app/config/routing_stage.yml';
        $contents = "_" . $this->deployBundle . "Stage:\n";
        $contents .= "    resource: \"@$this->deployBundle/Resources/config/site_routing_stage.yml\"\n\n";
        $this->overrideConfigFile($configFile, $contents);

        $configFile = $this->vendorDir . '/../app/config/routing_stage_dev.yml';
        $contents = "_stage_prod:\n";
        $contents .= "    resource: routing_stage.yml\n\n";
        $contents .= "_stage_dev:\n";
        $contents .= "    resource: routing_dev.yml";
        $this->overrideConfigFile($configFile, $contents);
    }

    private function checkFile($fileName, $message = null)
    {
        if( ! is_file($fileName))
        {
            $message = (null === $message) ? PHP_EOL . 'The required ' . $fileName . ' file has not been found' : $message;

            throw new \RuntimeException($message);
        }
    }

    private function writeDatabaseConfiguration()
    {
        $contents = "\n\nred_kite_labs_theme_engine:\n";
        $contents .= "    deploy_bundle: " . $this->deployBundle;
        $contents .= "\n\npropel:\n";
        $contents .= "    path:       \"%kernel.root_dir%/../vendor/propel/propel1\"\n";
        $contents .= "    phing_path: \"%kernel.root_dir%/../vendor/phing/phing\"\n\n";
        $contents .= "    dbal:\n";
        $contents .= "        driver:               %rkcms_database_driver%\n";
        if (null !== $this->user) {
        $contents .= "        user:                 %rkcms_database_user%\n";
        $contents .= "        password:             %rkcms_database_password%\n";
        }
        $contents .= "        dsn:                  " . $this->dsnBuilder->configureParametrizedDsn() . "\n";
        $contents .= "        options:              {}\n";
        $contents .= "        attributes:           {}\n\n";

        return $contents;
    }
}
