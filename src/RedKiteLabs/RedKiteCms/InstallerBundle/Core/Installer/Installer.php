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
use Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Description of installer
 *
 * @author alphalemon <webmaster@alphalemoncms.com>
 */
class Installer {
    private $deployBundle;
    private $companyName;
    private $bundleName;
    private $dsn;
    private $database;
    private $user;
    private $password;
    private $driver;
    private $rootDir;
    
    public function __construct($basepath) 
    {
        $this->rootDir = \AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit::normalizePath($basepath);        
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
                
        $this->checkPrerequisites();
        $this->setUpEnvironments();
        $this->writeConfigurations();
        $this->writeRoutes();
        $this->createDb();
        $this->manipulateAppKernel();
        $this->setup();
    }
    
    private function checkPrerequisites()
    {
        $connection = $this->connectDb($this->shortDsn);
        
        $this->checkClass('propel', '\Propel');
        $this->checkFolder($this->rootDir . '/phing');
        $this->checkClass('PropelBundle', 'Propel\PropelBundle\PropelBundle');
        $this->checkClass('AlphaLemonCmsBundle', 'AlphaLemon\AlphaLemonCmsBundle\AlphaLemonCmsBundle');
        $this->checkClass('PageTreeBundle', 'AlphaLemon\PageTreeBundle\AlphaLemonPageTreeBundle');
        $this->checkClass('AlValumUploaderBundle', 'AlphaLemon\AlValumUploaderBundle\AlValumUploaderBundle');
        $this->checkClass('AlValumUploaderBundle', 'AlphaLemon\AlValumUploaderBundle\AlValumUploaderBundle');
        $this->checkClass('ElFinderBundle', 'AlphaLemon\ElFinderBundle\AlphaLemonElFinderBundle');
        $this->checkClass('ThemeEngineBundle', 'AlphaLemon\ThemeEngineBundle\AlphaLemonThemeEngineBundle');
        $this->checkFolder($this->rootDir . '/../web/js/tiny_mce');
        $this->checkFile($this->rootDir . '/../app/Resources/java/yuicompressor.jar');
        
        $contents = file_get_contents($this->rootDir . '/../app/AppKernel.php');
        preg_match("/[\s|\t]+new " . $this->companyName . "\\\\" . $this->bundleName . "/s", $contents, $match);
        if(empty ($match))
        {
            echo "\nAlphaLemon CMS requires an existing bundle to work with. You enter as working bundle the following: $this->companyName\\$this->bundleName but, the bundle is not enable in AppKernel.php file. Please add the bundle or enable it ther run the script again.\n";
            
            die;
        }
    }
    
    private function manipulateAppKernel()
    {
        $updateFile = false;
        $kernelFile = $this->rootDir . '/../app/AppKernel.php';
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
            $cmsBundles = "\n\n        \$bootstrapper = new \AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader(\$this->getEnvironment(), \$bundles);\n";
            $cmsBundles .= "        \$bundles = \$bootstrapper->getBundles();\n\n";
            $cmsBundles .= "        return \$bundles;";
            $contents = preg_replace('/[\s]+return \$bundles;/s', $cmsBundles, $contents);
            $updateFile = true;
        }
        
        if(strpos($contents, '$configFolder = __DIR__ . \'/config/bundles/config\';') === false)
        {
            $cmsBundles = "\n        \$configFolder = __DIR__ . '/config/bundles/config';\n";
            $cmsBundles .= "        \$finder = new \Symfony\Component\Finder\Finder();\n";
            $cmsBundles .= "        \$configFiles = \$finder->depth(0)->name('*.yml')->in(\$configFolder);\n";
            $cmsBundles .= "        foreach (\$configFiles as \$config) {\n";
            $cmsBundles .= "            \$loader->load((string)\$config);\n";
            $cmsBundles .= "        };\n\n";
            $cmsBundles .= "        \$loader->load(__DIR__.'/config/config_'.\$this->getEnvironment().'.yml');";
        
            $contents = preg_replace('/[\s]+\$loader\-\>load\(__DIR__\.\'\/config\/config_\'\.\$this\-\>getEnvironment\(\).\'.yml\'\);/s', $cmsBundles, $contents);
            $updateFile = true;
        }
        require_once $this->rootDir . '/../app/AppKernel.php';
        return;
        /*
        $updateFile = false;
        $kernelFile = $this->rootDir . '/../app/AppKernel.php';
        $contents = file_get_contents($kernelFile);
       
        if(strpos($contents, 'new AlphaLemon\FrontendBundle\AlphaLemonFrontendBundle()') === false)
        {
            $cmsBundles = "\n            new AlphaLemon\FrontendBundle\AlphaLemonFrontendBundle(),\n";
            $cmsBundles .= "            new AlphaLemon\PageTreeBundle\AlphaLemonPageTreeBundle(),\n";
            $cmsBundles .= "            new AlphaLemon\ThemeEngineBundle\AlphaLemonThemeEngineBundle(),\n";
            $cmsBundles .= "        );";
            $contents = preg_replace('/[\s]+\);/s', $cmsBundles, $contents);
            $updateFile = true;
        }
        
        if(strpos($contents, '$themes = new \AlphaLemon\ThemeEngineBundle\Core\Autoloader\ThemesAutoloader()') === false)
        {
            $cmsBundles = "\$themes = new \AlphaLemon\ThemeEngineBundle\Core\Autoloader\ThemesAutoloader();\n";
            $cmsBundles .= "        \$bundles = array_merge(\$bundles, \$themes->getBundles());\n";            
            $cmsBundles .= "        if (in_array(\$this->getEnvironment(), array('alcms', 'alcms_dev', 'test'))) {\n";
            $cmsBundles .= "            \$bundles[] = new Propel\PropelBundle\PropelBundle();\n";
            $cmsBundles .= "            \$bundles[] = new AlphaLemon\AlValumUploaderBundle\AlValumUploaderBundle();\n";
            $cmsBundles .= "            \$bundles[] = new AlphaLemon\ElFinderBundle\AlphaLemonElFinderBundle();\n"; 
            $cmsBundles .= "            \$bundles[] = new AlphaLemon\AlphaLemonCmsBundle\AlphaLemonCmsBundle();\n";  
            $cmsBundles .= "            \$internalBundles = new \AlphaLemon\AlphaLemonCmsBundle\Core\Autoloader\InternalBundlesAutoloader();\n";;
            $cmsBundles .= "            \$bundles = array_merge(\$bundles, \$internalBundles->getBundles());\n";
            $cmsBundles .= "        }\n\n";
            $cmsBundles .= "        if (in_array(\$this->getEnvironment(), array('dev', 'test', 'alcms_dev'";

            $contents = preg_replace('/if \(in_array\(\$this-\>getEnvironment\(\), array\(\'dev\', \'test\'/s', $cmsBundles, $contents);
            $updateFile = true;
        }
        
        if($updateFile) file_put_contents($kernelFile, $contents);
        
        require_once $this->rootDir . '/../app/AppKernel.php';*/
    }
    
    private function addBundle(KernelManipulator $km, $bundleName)
    {
        try
        {
            $km->addBundle($bundleName);
        }
        catch (\RuntimeException $ex)
        {
            
        }
    }


    private function checkClass($libraryName, $className)
    {
        if(!class_exists($className))
        {
            echo "\nAn error occoured. AlphaLemon CMS requires the " . $libraryName . " library. Please install that library then run the script again.\n";
            die;
        }
    }
    
    private function checkFolder($dirName)
    {
        if(!is_dir($dirName))
        {
            echo "\nAn error occoured. AlphaLemon CMS requires " . basename($dirName) . " installed into " . dirname($dirName) . " folder. Please install the required library then run the script again.\n";
            die;
        }
    }
    
    private function checkFile($fileName)
    {
        if(!is_file($fileName))
        {
            echo "\nAn error occoured. AlphaLemon CMS requires " . basename($fileName) . " installed into " . dirname($fileName) . " folder. Please install the required library then run the script again.\n";
            die;
        }
    }
    
    private function setUpEnvironments()
    {
        $fs = new Filesystem();
        $fs->copy($this->rootDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/environments/frontcontrollers/alcms.php', $this->rootDir . '/../web/alcms.php', true);
        $fs->copy($this->rootDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/environments/frontcontrollers/alcms_dev.php', $this->rootDir . '/../web/alcms_dev.php', true);
        $fs->copy($this->rootDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/environments/config/config_alcms.yml', $this->rootDir . '/../app/config/config_alcms.yml', true);
        $fs->copy($this->rootDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/environments/config/config_alcms_dev.yml', $this->rootDir . '/../app/config/config_alcms_dev.yml', true);
        $fs->copy($this->rootDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/environments/config/routing_alcms.yml', $this->rootDir . '/../app/config/routing_alcms.yml', true);
        $fs->copy($this->rootDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/environments/config/routing_alcms_dev.yml', $this->rootDir . '/../app/config/routing_alcms_dev.yml', true);
        $fs->mkdir($this->rootDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/public/uploads/assets/media');
        $fs->mkdir($this->rootDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/public/uploads/assets/js');
        $fs->mkdir($this->rootDir . '/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCmsBundle/Resources/public/uploads/assets/css');
        $fs->mkdir($this->rootDir . '/../src/AlphaLemon/Block');
        $fs->mkdir($this->rootDir . '/../src/AlphaLemon/Theme');
    }
    
    private function writeConfigurations()
    {
        
        $section = "\nalpha_lemon_frontend:\n";
        $section .= "    deploy_bundle: $this->deployBundle\n\n";    
        /*$section .= "alpha_lemon_theme_engine:\n";
        $section .= "    base_template: AlphaLemonFrontendBundle:Theme:base.html.twig\n\n";
        $section .= "assetic:\n";
        $section .= "    filters:\n";
        $section .= "        cssrewrite: ~\n";
        $section .= "        yui_css:\n";
        $section .= "            jar: %kernel.root_dir%/Resources/java/yuicompressor.jar\n";
        $section .= "        yui_js:\n";
        $section .= "            jar: %kernel.root_dir%/Resources/java/yuicompressor.jar";*/
        $this->writeConfigFile($this->rootDir . '/../app/config/config.yml', '/alpha_lemon_frontend/is', $section);
        
        $section = "\n\npropel:\n";
        $section .= "    path:       \"%kernel.root_dir%/../vendor/propel/propel1\"\n";
        $section .= "    phing_path: \"%kernel.root_dir%/../vendor/phing/phing\"\n\n";            
        $section .= "    dbal:\n";
        $section .= "        driver:               $this->driver\n";
        $section .= "        user:                 $this->user\n";
        $section .= "        password:             $this->password\n";
        $section .= "        dsn:                  $this->dsn\n";
        $section .= "        options:              {}\n";
        $section .= "        attributes:           {}\n";
        $section .= "        default_connection:   default\n\n";
        $this->writeConfigFile($this->rootDir . '/../app/config/config_alcms.yml', '/propel/is', $section);
    }
    
    private function writeConfigFile($configFile, $sectionRegex, $sectionContents)
    {
        $contents = file_get_contents($configFile);
        preg_match($sectionRegex, $contents, $match);        
        if(empty($match)) {
            file_put_contents($configFile, $contents . $sectionContents);
        }
    }
    
    private function writeRoutes()
    {
        $configFile = $this->rootDir . '/../app/config/routing.yml';
        $contents = file_get_contents($configFile);
        preg_match("/_$this->deployBundle/", $contents, $match);
        
        if(empty($match))
        {
            $config = "_$this->deployBundle:\n";
            $config .= "    resource: \"@$this->deployBundle/Resources/config/site_routing.yml\"\n\n";

            file_put_contents($configFile, $config . $contents);

            $siteRoutingFile = $this->rootDir . "/../src/$this->companyName/$this->bundleName/Resources/config/site_routing.yml";
            $fs = new Filesystem();
            $fs->touch($siteRoutingFile);
        }
    }

    private function connectDb($dsn = null)
    {
        try
        {
            $dsn = (null === $dsn) ? $this->dsn : $dsn;
            
            return new \PropelPDO($dsn, $this->user, $this->password);
        }
        catch(Exception $ex)
        {
            echo "\nERROR: An error occoured when trying to connect the database with the given parameters. The server returned the following error:\n\n" . $ex->getMessage() . "\n\nCheck your configuration parameters into the bin/config.php file and be sure that the database name given is the same exposed by the dsn\n\n";
            die;
        }
    }
    
    private function createDb()
    {
        try
        {
            $connection = $this->connectDb($this->shortDsn);

            $query = 'CREATE DATABASE ' . $this->database;
            $statement = $connection->prepare($query);
            $statement->execute();
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }
    
    protected static function executeCommand($appDir, $cmd)
    {
        $phpFinder = new PhpExecutableFinder;
        $php = escapeshellarg($phpFinder->find());
        $console = realpath($appDir.'/check.php');
        
        $process = new Process($php.' '.$console.' '.$cmd);
        $process->run(function ($type, $buffer) { echo $buffer; });
    }
    
    private function setup()
    {/*
        $appDir = $this->rootDir . '/../app';
        $this->executeCommand($appDir, 'cache:clear'); echo (is_dir($appDir)) ? "AAA" : "BBB";exit;*/
        
        $kernel = new \AppKernel('alcms_dev', true);
        $kernel->boot();
        $cmd = sprintf('alphalemon:populate %s --user=%s --password=%s', $this->dsn, $this->user, $this->password);

        $symlink = (in_array(strtolower(PHP_OS), array('unix', 'linux'))) ? ' --symlink' : ''; 
        \AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit::executeCommand($kernel, array('propel:build',
                                                                     'propel:insert-sql --force',
                                                                     'assets:install ' . $this->rootDir . '/../web' . $symlink,
                                                                     $cmd,
                                                                     'assetic:dump',
                                                                     'cache:clear',
            ));
    }
}