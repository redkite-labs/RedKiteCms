<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Core\Installer\Configurator;

use Symfony\Component\Yaml\Yaml;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Installer\Base\BaseOptions;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Generator\ConfigurationGenerator;

/**
 * Implements the base object to prepare RedKite CMS configuration
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class Configurator extends BaseOptions
{
    private $generator;
    private $kernel;
    
    public function __construct(\Symfony\Component\HttpKernel\KernelInterface $kernel, array $options = array())
    {
        parent::__construct($kernel->getRootDir(), $options);
        
        $this->kernel = $kernel;
        $this->generator = new ConfigurationGenerator($this->kernelDir, $this->options);
    }
    
    /**
     * Prepares the RedKite Cms configuration
     */
    public function configure()
    {
        if ( ! $this->checkWritePermissions()) {
            return -1;
        }

        $this->checkPrerequisites();        
        $this->dsnBuilder->testConnection();
        $this->manipulateAppKernel();
        $this->writeConfigurationParameters();
        $this->writeConfigurationFiles();
        $this->writeRoutes();
        $this->setUpEnvironments();
    }
    
    private function backUpFile($fileName)
    {
        $backupFile = $fileName . '.bak';

        // Have I already installed?
        if (file_exists($backupFile)) {
            
            // Restore original file
            unlink($fileName);
            $this->filesystem->copy($backupFile, $fileName);
            
            return;
        }
        
        $this->filesystem->copy($fileName, $backupFile);
    }

    private function manipulateAppKernel()
    {
        $updateFile = false;
        $kernelFile = $this->kernelDir . '/AppKernel.php';
        $this->backUpFile($kernelFile);
        $contents = file_get_contents($kernelFile);

        if( ! preg_match('/\/\/ RedKiteCms Active Theme(.*?)\/\/ End RedKiteCms Active Theme/is', $contents))
        {
            $cmsBundles = PHP_EOL . PHP_EOL . '        // RedKiteCms Active Theme';
            $cmsBundles .= PHP_EOL . '        $bundles[] = new RedKiteLabs\ThemeEngineBundle\RedKiteLabsThemeEngineBundle();';
            $cmsBundles .= PHP_EOL . '        $bundles[] = new RedKiteLabs\ModernBusinessThemeBundle\ModernBusinessThemeBundle();';
            $cmsBundles .= PHP_EOL . '        // End RedKiteCms Active Theme';
            $cmsBundles .= PHP_EOL . PHP_EOL . '        return $bundles;';

            $contents = preg_replace('/[\s]+return \$bundles;/s', $cmsBundles, $contents);

            $updateFile = true;
        }

        if ($updateFile) {
            file_put_contents($kernelFile, $contents);
        }
        
        $this->generator->generateApplication();

        return;
    }

    private function setUpEnvironments()
    {
        $this->generator->generateFrontcontrollers();

        $this->filesystem->mkdir($this->vendorDir . '/../web/uploads/assets');
        $this->filesystem->mkdir($this->vendorDir . '/redkite-cms/redkite-cms-bundle/RedKiteLabs/RedKiteCmsBundle/Resources/public/uploads/assets/media');
        $this->filesystem->mkdir($this->vendorDir . '/redkite-cms/redkite-cms-bundle/RedKiteLabs/RedKiteCmsBundle/Resources/public/uploads/assets/js');
        $this->filesystem->mkdir($this->vendorDir . '/redkite-cms/redkite-cms-bundle/RedKiteLabs/RedKiteCmsBundle/Resources/public/uploads/assets/css');
        $this->filesystem->mkdir($this->kernelDir . '/propel/sql');
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
        $configFile = $this->kernelDir . '/config/config.yml';
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

            $siteRoutingFile = $this->kernel->locateResource("@" . $this->deployBundle) . '/Resources/config/site_routing.yml' ;
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