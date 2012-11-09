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

namespace AlphaLemon\CmsInstallerBundle\Core\Installer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;
use AlphaLemon\AlphaLemonCmsBundle\Core\CommandsProcessor;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Orm\OrmInterface;

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

    public function __construct($vendorDir, OrmInterface $orm = null, CommandsProcessor\AlCommandsProcessorInterface $commandsProcessor = null)
    {
        $this->vendorDir = $this->normalizePath($vendorDir);
        $this->orm = $orm;
        $consolePath = $this->vendorDir . '/../app';
        $this->commandsProcessor = (null === $commandsProcessor) ? new CommandsProcessor\AlCommandsProcessor($consolePath) : $commandsProcessor;
        $this->filesystem = new Filesystem();
    }

    public function install($companyName, $bundleName, $dsn, $database, $user, $password, $driver)
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
        $this->checkClass('AlphaLemonCmsBundle', 'AlphaLemon\AlphaLemonCmsBundle\AlphaLemonCmsBundle');
        $this->checkClass('ElFinderBundle', 'AlphaLemon\ElFinderBundle\AlphaLemonElFinderBundle');
        $this->checkClass('ThemeEngineBundle', 'AlphaLemon\ThemeEngineBundle\AlphaLemonThemeEngineBundle');
        $this->checkFolder($this->vendorDir . '/../web/js/tiny_mce');
        $yuiCompressor = $this->vendorDir . '/../app/Resources/java/yuicompressor.jar';
        $this->checkFile($yuiCompressor, "\nAn error occoured. AlphaLemon CMS requires " . basename($yuiCompressor) . " installed into " . dirname($yuiCompressor) . " folder. Please install the required library then run the script again.\n");
        $appKernelFile = $this->vendorDir . '/../app/AppKernel.php';
        $this->checkFile($appKernelFile);

        $contents = file_get_contents($appKernelFile);
        preg_match("/[\s|\t]+new " . $this->companyName . "\\\\" . $this->bundleName . "/s", $contents, $match);
        if(empty ($match))
        {
            $message = "\nAlphaLemon CMS requires an existing bundle to work with. You enter as working bundle the following: $this->companyName\\$this->bundleName but, the bundle is not enable in AppKernel.php file. Please add the bundle or enable it ther run the script again.\n";

            throw new \RuntimeException($message);
        }
    }

    protected function manipulateAppKernel()
    {
        $updateFile = false;
        $kernelFile = $this->vendorDir . '/../app/AppKernel.php';
        $this->backUpFile($kernelFile);
        $contents = file_get_contents($kernelFile);

        if(strpos($contents, 'new AlphaLemon\BootstrapBundle\AlphaLemonBootstrapBundle()') === false)
        {
            $cmsBundles = "\n            new AlphaLemon\BootstrapBundle\AlphaLemonBootstrapBundle(),\n";
            $cmsBundles .= "        );";
            $contents = preg_replace('/[\s]+\);/s', $cmsBundles, $contents);
            $updateFile = true;
        }

        if(strpos($contents, 'new \AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader') === false)
        {
            $cmsBundles = "\n\n        \$bootstrapper = new \AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader(__DIR__, \$this->getEnvironment(), \$bundles);\n";
            $cmsBundles .= "        \$bundles = \$bootstrapper->getBundles();\n\n";
            $cmsBundles .= "        return \$bundles;";
            $contents = preg_replace('/[\s]+return \$bundles;/s', $cmsBundles, $contents);
            $updateFile = true;
        }

        if(strpos($contents, '$configFolder = __DIR__ . \'/config/bundles/config\';') === false)
        {
            $cmsBundles = "\n        \$configFolder = __DIR__ . '/config/bundles/config/' . \$this->getEnvironment();\n";
            $cmsBundles .= "        \$finder = new \Symfony\Component\Finder\Finder();\n";
            $cmsBundles .= "        \$configFiles = \$finder->depth(0)->name('*.yml')->in(\$configFolder);\n";
            $cmsBundles .= "        foreach (\$configFiles as \$config) {\n";
            $cmsBundles .= "            \$loader->load((string)\$config);\n";
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
            $message = "\nAn error occoured. AlphaLemon CMS requires the " . $libraryName . " library. Please install that library then run the script again.\n";

            throw new \RuntimeException($message);
        }
    }

    protected function checkFolder($dirName)
    {
        if(!is_dir($dirName))
        {
            $message = "\nAn error occoured. AlphaLemon CMS requires " . basename($dirName) . " installed into " . dirname($dirName) . " folder. Please install the required library then run the script again.\n";

            throw new \RuntimeException($message);
        }
    }

    protected function checkFile($fileName, $message = null)
    {
        if(!is_file($fileName))
        {
            $message = (null === $message) ? PHP_EOL . 'The required ' . $fileName . ' file has not been found' : $message;

            throw new \RuntimeException($message);
        }
    }
    
    protected function backUpFile($fileName)
    {
        $backupFile = $fileName . '.bak';
        
        if ( !file_exists($backupFile)) {
            $this->filesystem ->copy($fileName, $backupFile);
        }
    }

    protected function setUpEnvironments()
    {
        $this->filesystem ->copy($this->vendorDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/environments/frontcontrollers/alcms.php', $this->vendorDir . '/../web/alcms.php', true);
        $this->filesystem ->copy($this->vendorDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/environments/frontcontrollers/alcms_dev.php', $this->vendorDir . '/../web/alcms_dev.php', true);
        $this->filesystem ->mkdir($this->vendorDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/public/uploads/assets/media');
        $this->filesystem ->mkdir($this->vendorDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/public/uploads/assets/js');
        $this->filesystem ->mkdir($this->vendorDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/public/uploads/assets/css');

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
            $contents .= "\nalpha_lemon_theme_engine:\n";
            $contents .= "    deploy_bundle: $this->deployBundle\n\n";
        }
        file_put_contents($configFile, $contents);

        $configFile = $this->vendorDir . '/../app/config/config_alcms.yml';
        if (!is_file($configFile)) {
            $contents = "imports:\n";
            $contents .= "    - { resource: parameters.yml }\n";
            $contents .= "    - { resource: \"@AlphaLemonCmsBundle/Resources/config/config_alcms.yml\" }\n";
            $contents .= "    - { resource: \"@AlphaLemonCmsBundle/Resources/config/security.yml\" }";
            $contents .= $this->writeDatabaseConfiguration($this->dsn);
            file_put_contents($configFile, $contents);
        }

        $configFile = $this->vendorDir . '/../app/config/config_alcms_dev.yml';
        if (!is_file($configFile)) {
            $contents = "imports:\n";
            $contents .= "    - { resource: config_alcms.yml }\n";
            $contents .= "    - { resource: \"@AlphaLemonCmsBundle/Resources/config/config_alcms_dev.yml\" }";
            file_put_contents($configFile, $contents);
        }

        $configFile = $this->vendorDir . '/../app/config/config_alcms_test.yml';
        if (!is_file($configFile)) {
            $contents = "imports:\n";
            $contents .= "    - { resource: config_alcms_dev.yml }\n";
            $contents .= "    - { resource: \"@AlphaLemonCmsBundle/Resources/config/config_alcms_test.yml\" }";
            $contents .= $this->writeDatabaseConfiguration($this->shortDsn . ';dbname=' . $this->database . '_test');
            file_put_contents($configFile, $contents);
        }
    }

    private function writeDatabaseConfiguration($dsn)
    {
        $contents = "\n\nalpha_lemon_theme_engine:\n";
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

        $configFile = $this->vendorDir . '/../app/config/routing_alcms.yml';
        if (!is_file($configFile)) {
            $contents = "_alcms:\n";
            $contents .= "    resource: \"@AlphaLemonCmsBundle/Resources/config/routing_alcms.yml\"";
            file_put_contents($configFile, $contents);
        }

        $configFile = $this->vendorDir . '/../app/config/routing_alcms_dev.yml';
        if (!is_file($configFile)) {
            $contents = "_alcms:\n";
            $contents .= "    resource: \"@AlphaLemonCmsBundle/Resources/config/routing_alcms_dev.yml\"\n\n";
            $contents .= "_alcms_dev:\n";
            $contents .= "    resource: routing_alcms.yml";
            file_put_contents($configFile, $contents);
        }

        $configFile = $this->vendorDir . '/../app/config/routing_alcms_test.yml';
        if (!is_file($configFile)) {
            $contents = "_alcms_dev:\n";
            $contents .= "    resource: resource: routing_alcms_dev.yml\n\n";
            $contents .= "_al_text_bundle:\n";
            $contents .= "    resource: \"@TextBundle/Resources/config/routing/routing.xml\"";
            file_put_contents($configFile, $contents);
        }
    }

    protected function setUpOrm($dsn = null)
    {
        try
        {
            $dsn = (null === $dsn) ? $this->dsn : $dsn;

            $connection = new \PropelPDO($dsn, $this->user, $this->password);
            $this->orm = new \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\Base\AlPropelOrm($connection);
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
        $assetsInstall = 'assets:install --env=alcms_dev ' . $this->vendorDir . '/../web' . $symlink;
        $populate = sprintf('alphalemon:populate --env=alcms_dev "%s" --user=%s --password=%s', $this->dsn, $this->user, $this->password);
        $commands = array('propel:build --insert-sql --env=alcms_dev' => null,
                          $assetsInstall => null,
                          $populate => null,
                          'assetic:dump --env=alcms_dev' => null,
                          'cache:clear --env=alcms_dev' => null,
            );

        $this->commandsProcessor->executeCommands($commands, function($type, $buffer){ echo $buffer; });
    }
}
