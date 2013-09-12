<?php
/*
 * This file is part of the RedKiteCmsCMS InstallerBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\InstallerBundle\Core\Installer;

use Symfony\Component\Filesystem\Filesystem;
use RedKiteLabs\RedKiteCmsBundle\Core\CommandsProcessor;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Orm\OrmInterface;

/**
 * Description of installer
 *
 * @author alphalemon <webmaster@alphalemoncms.com>
 */
class Installer {

    protected $deployBundle;
    protected $companyName;
    protected $bundleName;
    protected $dsn;
    protected $database;
    protected $user;
    protected $password;
    protected $driver;
    protected $vendorDir;
    protected $filesystem;
    protected $orm;
    protected $commandsProcessor;
    protected $websiteUrl;
    
    public function __construct($vendorDir, OrmInterface $orm = null, CommandsProcessor\AlCommandsProcessorInterface $commandsProcessor = null)
    {
        $this->vendorDir = $this->normalizePath($vendorDir);
        $this->orm = $orm;
        $consolePath = $this->vendorDir . '/../app';
        $this->commandsProcessor = (null === $commandsProcessor) ? new CommandsProcessor\AlCommandsProcessor($consolePath) : $commandsProcessor;
        $this->filesystem = new Filesystem();
    }

    public function install($companyName, $bundleName, $dsn, $database, $user, $password, $driver, $websiteUrl)
    {
        $this->companyName = $companyName;
        $this->bundleName = $bundleName;
        $this->dsn = $dsn;

        // strip the dsn from the database name because it might not be created yet
        $this->shortDsn = preg_replace('/[;]?[\w]+=' . $database . '[;]?/', '', $dsn);

        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        $this->driver = $driver;
        $this->deployBundle = $companyName . $bundleName;
        $this->websiteUrl = $websiteUrl;

        if(null === $this->orm) $this->setUpOrm($this->shortDsn);
        $this->checkPrerequisites();
        $this->setUpEnvironments();
        $this->writeConfigurations();
        $this->writeRoutes();
        $this->createDb();
        $this->manipulateAppKernel();
        $this->setup();
    }

    /**
     * Normalize a path as a unix path
     *
     * @param   string      $path
     * @return  string
     */
    protected function normalizePath($path)
    {
        return preg_replace('/\\\/', '/', $path);
    }

    protected function checkPrerequisites()
    {
        $this->checkClass('propel', '\Propel');
        $this->checkFolder($this->vendorDir . '/phing');
        $this->checkClass('PropelBundle', 'Propel\PropelBundle\PropelBundle');
        $this->checkClass('RedKiteCmsBundle', 'RedKiteLabs\RedKiteCmsBundle\RedKiteCmsBundle');
        $this->checkClass('ElFinderBundle', 'RedKiteLabs\ElFinderBundle\RedKiteLabsElFinderBundle');
        $this->checkClass('ThemeEngineBundle', 'RedKiteLabs\ThemeEngineBundle\RedKiteLabsThemeEngineBundle');
        
        $appKernelFile = $this->vendorDir . '/../app/AppKernel.php';
        $this->checkFile($appKernelFile);

        $contents = file_get_contents($appKernelFile);
        preg_match("/[\s|\t]+new " . $this->companyName . "\\\\" . $this->bundleName . "/s", $contents, $match);
        if(empty ($match))
        {
            $message = "\nRedKite CMS requires an existing bundle to work with. You enter as working bundle the following: $this->companyName\\$this->bundleName but, the bundle is not enable in AppKernel.php file. Please add the bundle or enable it ther run the script again.\n";

            throw new \RuntimeException($message);
        }
    }

    protected function manipulateAppKernel()
    {
        $updateFile = false;
        $kernelFile = $this->vendorDir . '/../app/AppKernel.php';
        $this->backUpFile($kernelFile);
        $contents = file_get_contents($kernelFile);

        if(strpos($contents, 'new RedKiteLabs\BootstrapBundle\RedKiteLabsBootstrapBundle()') === false)
        {
            $cmsBundles = "\n            new RedKiteLabs\BootstrapBundle\RedKiteLabsBootstrapBundle(),\n";
            $cmsBundles .= "        );";
            $contents = preg_replace('/[\s]+\);/s', $cmsBundles, $contents);
            $updateFile = true;
        }

        if(strpos($contents, 'new \RedKiteLabs\BootstrapBundle\Core\Autoloader\BundlesAutoloader') === false)
        {
            $cmsBundles = "\n\n        \$bootstrapper = new \RedKiteLabs\BootstrapBundle\Core\Autoloader\BundlesAutoloader(__DIR__, \$this->getEnvironment(), \$bundles);\n";
            $cmsBundles .= "        \$bundles = \$bootstrapper->getBundles();\n\n";
            $cmsBundles .= "        return \$bundles;";
            $contents = preg_replace('/[\s]+return \$bundles;/s', $cmsBundles, $contents);
            $updateFile = true;
        }

        if(strpos($contents, '$configFolder = __DIR__ . \'/config/bundles/config') === false)
        {
            $cmsBundles = "\n        \$configFolder = __DIR__ . '/config/bundles/config/' . \$this->getEnvironment();\n";
            $cmsBundles .= "        if (is_dir(\$configFolder)) {\n";
            $cmsBundles .= "            \$finder = new \Symfony\Component\Finder\Finder();\n";
            $cmsBundles .= "            \$configFiles = \$finder->depth(0)->name('*.yml')->in(\$configFolder);\n";
            $cmsBundles .= "            foreach (\$configFiles as \$config) {\n";
            $cmsBundles .= "                \$loader->load((string)\$config);\n";
            $cmsBundles .= "            };\n";
            $cmsBundles .= "        };\n\n";
            $cmsBundles .= "        \$loader->load(__DIR__.'/config/config_'.\$this->getEnvironment().'.yml');";

            $contents = preg_replace('/[\s]+\$loader\-\>load\(__DIR__\.\'\/config\/config_\'\.\$this\-\>getEnvironment\(\).\'.yml\'\);/s', $cmsBundles, $contents);
            $updateFile = true;
        }

        if ($updateFile) file_put_contents($kernelFile, $contents);

        return;
    }

    protected function checkClass($libraryName, $className)
    {
        if(!class_exists($className))
        {
            $message = "\nAn error occoured. RedKite CMS requires the " . $libraryName . " library. Please install that library then run the script again.\n";

            throw new \RuntimeException($message);
        }
    }

    protected function checkFolder($dirName)
    {
        if(!is_dir($dirName))
        {
            $message = "\nAn error occoured. RedKite CMS requires " . basename($dirName) . " installed into " . dirname($dirName) . " folder. Please install the required library then run the script again.\n";

            throw new \RuntimeException($message);
        }
    }

    protected function checkFile($fileName, $message = null)
    {
        if( ! is_file($fileName))
        {
            $message = (null === $message) ? PHP_EOL . 'The required ' . $fileName . ' file has not been found' : $message;

            throw new \RuntimeException($message);
        }
    }
    
    protected function backUpFile($fileName)
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

    protected function setUpEnvironments()
    {
        $this->filesystem ->copy($this->vendorDir . '/redkite-cms/redkite-cms-bundle/RedKiteLabs/RedKiteCmsBundle/Resources/environments/frontcontrollers/rkcms.php', $this->vendorDir . '/../web/rkcms.php', true);
        $this->filesystem ->copy($this->vendorDir . '/redkite-cms/redkite-cms-bundle/RedKiteLabs/RedKiteCmsBundle/Resources/environments/frontcontrollers/rkcms_dev.php', $this->vendorDir . '/../web/rkcms_dev.php', true);
        $this->filesystem ->copy($this->vendorDir . '/redkite-labs/redkite-labs-theme-engine-bundle/RedKiteLabs/ThemeEngineBundle/Resources/environments/frontcontrollers/stage.php', $this->vendorDir . '/../web/stage.php', true);
        $this->filesystem ->copy($this->vendorDir . '/redkite-labs/redkite-labs-theme-engine-bundle/RedKiteLabs/ThemeEngineBundle/Resources/environments/frontcontrollers/stage_dev.php', $this->vendorDir . '/../web/stage_dev.php', true);
        $this->filesystem ->mkdir($this->vendorDir . '/../web/uploads/assets');
        $this->filesystem ->mkdir($this->vendorDir . '/redkite-cms/redkite-cms-bundle/RedKiteLabs/RedKiteCmsBundle/Resources/public/uploads/assets/media');
        $this->filesystem ->mkdir($this->vendorDir . '/redkite-cms/redkite-cms-bundle/RedKiteLabs/RedKiteCmsBundle/Resources/public/uploads/assets/js');
        $this->filesystem ->mkdir($this->vendorDir . '/redkite-cms/redkite-cms-bundle/RedKiteLabs/RedKiteCmsBundle/Resources/public/uploads/assets/css');

        $this->filesystem ->mkdir($this->vendorDir . '/../app/propel/sql');
    }

    protected function writeConfigurations()
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
        $contents .= $this->writeDatabaseConfiguration($this->dsn);
        $contents .= "red_kite_cms:\n";
        $contents .= "    website_url: " . $this->websiteUrl;
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
        $contents .= $this->writeDatabaseConfiguration($this->shortDsn . ';dbname=' . $this->database . '_test');
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

    private function writeDatabaseConfiguration($dsn)
    {
        $contents = "\n\nred_kite_labs_theme_engine:\n";
        $contents .= "    deploy_bundle: " . $this->deployBundle;
        $contents .= "\n\npropel:\n";
        $contents .= "    path:       \"%kernel.root_dir%/../vendor/propel/propel1\"\n";
        $contents .= "    phing_path: \"%kernel.root_dir%/../vendor/phing/phing\"\n\n";
        $contents .= "    dbal:\n";
        $contents .= "        driver:               $this->driver\n";
        $contents .= "        user:                 $this->user\n";
        $contents .= "        password:             $this->password\n";
        $contents .= "        dsn:                  $dsn\n";
        $contents .= "        options:              {}\n";
        $contents .= "        attributes:           {}\n";
        $contents .= "        default_connection:   default\n\n";

        return $contents;
    }

    protected function writeConfigFile($configFile, $sectionRegex, $sectionContents)
    {
        $contents = file_get_contents($configFile);
        preg_match($sectionRegex, $contents, $match);
        if (empty($match)) {
            file_put_contents($configFile, $contents . $sectionContents);
        }
    }
    
    protected function overrideConfigFile($configFile, $contents)
    {
        if (is_file($configFile)) {
            unlink($configFile);
        }

        file_put_contents($configFile, $contents);
    }

    protected function writeRoutes()
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

    protected function setUpOrm($dsn = null)
    {
        try
        {
            $dsn = (null === $dsn) ? $this->dsn : $dsn;

            $connection = new \PropelPDO($dsn, $this->user, $this->password);
            $this->orm = new \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\Base\AlPropelOrm($connection);
        }
        catch(\Exception $ex)
        {
            throw new \RuntimeException("An error occoured when trying to connect the database with the given parameters. The server returned the following error:\n\n" . $ex->getMessage());
        }
    }

    protected function createDb()
    {
        try
        {
            $queries = array('DROP DATABASE IF EXISTS ' . $this->database,
                             'CREATE DATABASE ' . $this->database);

            foreach($queries as $query) {
                if (false === $this->orm->executeQuery($query)) {
                    throw new \RuntimeException("The database has not be created. Check your configuration parameters");
                }
            }
        }
        catch(\Exception $ex)
        {
            throw $ex;
        }
    }

    protected function setup()
    {
        $symlink = (in_array(strtolower(PHP_OS), array('unix', 'linux'))) ? ' --symlink' : '';
        $assetsInstall = 'assets:install --env=rkcms_dev ' . $this->vendorDir . '/../web' . $symlink;
        $populate = sprintf('redkitecms:populate --env=rkcms_dev "%s" --user=%s --password=%s', $this->dsn, $this->user, $this->password);
        
        $commands = array('propel:build --insert-sql --env=rkcms_dev' => null,
                          $assetsInstall => null,
                          $populate => null,
                          'assetic:dump --env=rkcms_dev' => null,
                          'cache:clear --env=rkcms_dev' => null,
            );

        $this->commandsProcessor->executeCommands($commands, function($type, $buffer){ echo $buffer; });
    }
}
