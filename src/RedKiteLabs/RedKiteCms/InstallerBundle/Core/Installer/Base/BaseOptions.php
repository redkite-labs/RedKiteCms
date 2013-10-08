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

namespace RedKiteCms\InstallerBundle\Core\Installer\Base;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Implements a base class to define the base options required to install RedKite Cms
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseOptions
{
    protected $vendorDir;
    protected $bundleName;
    protected $database;
    protected $driver;
    protected $host;
    protected $port;
    protected $user;
    protected $password;
    protected $dsnBuilder;
    protected $prerequisitesVerified = null;
    
    /**
     * Contructor
     * 
     * @param array $options
     */
    public function __construct($vendorDir, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $options = $resolver->resolve($options);
        
        $this->vendorDir = $this->normalizePath($vendorDir);
        $this->companyName = $options["company"];
        $this->bundleName = $options["bundle"];        
        $this->deployBundle = $this->companyName . $this->bundleName;
        if (empty($this->deployBundle) || !preg_match('/.*?Bundle$/', $this->deployBundle)) { 
            throw new \InvalidArgumentException("Something was wrong with the values you entered to define the deploy bundle. Please refer to http://redkite-labs.com/how-to-install-redkite-cms#the-deploy-bundle to learn more about this topic.");
        }
        $this->driver = $options["driver"];
        $this->host = $options["host"];
        $this->port = (int)$options["port"];
        $this->database = $options["database"];
        $this->user = $options["user"];
        $this->password = $options["password"];
        $this->websiteUrl = $options["website-url"];
        
        $dsnBuilderClassName = '\RedKiteCms\InstallerBundle\Core\DsnBuilder\GenericDsnBuilder';
        $specificDsnBuilderClassName = '\RedKiteCms\InstallerBundle\Core\DsnBuilder\\' . ucfirst($this->driver) . 'DsnBuilder';
        if (class_exists($specificDsnBuilderClassName)) {
            $dsnBuilderClassName = $specificDsnBuilderClassName;
        }
        $this->dsnBuilder = new $dsnBuilderClassName($options);
        
        $this->filesystem = new Filesystem();
    }
    
    
    /**
     * Checks that RedKite CMS prerequisites are satisfied
     * 
     * @throws \RuntimeException
     */
    protected function checkPrerequisites()
    {
        if (null !== $this->prerequisitesVerified) {
            return;
        }
        
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
        
        $this->prerequisitesVerified = true;
    }
    
    /**
     * Defines the required/optional options
     * 
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('company'));
        $resolver->setRequired(array('bundle'));
        $resolver->setRequired(array('host'));
        $resolver->setRequired(array('driver'));
        $resolver->setRequired(array('port'));
        $resolver->setRequired(array('database'));
        $resolver->setRequired(array('user'));
        $resolver->setOptional(array('password'));
        $resolver->setRequired(array('website-url'));
    }

    /**
     * Normalize a path as a unix path
     *
     * @param   string      $path
     * @return  string
     */
    private function normalizePath($path)
    {
        return preg_replace('/\\\/', '/', $path);
    }
    
    private function checkClass($libraryName, $className)
    {
        if(!class_exists($className))
        {
            $message = "\nAn error occoured. RedKite CMS requires the " . $libraryName . " library. Please install that library then run the script again.\n";

            throw new \RuntimeException($message);
        }
    }

    private function checkFolder($dirName)
    {
        if(!is_dir($dirName))
        {
            $message = "\nAn error occoured. RedKite CMS requires " . basename($dirName) . " installed into " . dirname($dirName) . " folder. Please install the required library then run the script again.\n";

            throw new \RuntimeException($message);
        }
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
