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

namespace RedKiteCms\InstallerBundle\Core\Installer\Environments;

use RedKiteCms\InstallerBundle\Core\Installer\Base\BaseOptions;

/**
 * Implements the base object to prepare RedKite CMS environments
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class Environments extends BaseOptions
{
    /**
     * Sets up RedKite Cms environments
     */
    public function setUp()
    {
        $this->checkPrerequisites();
        $this->setUpEnvironments();
        $this->manipulateAppKernel();
    }

    private function manipulateAppKernel()
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

    private function setUpEnvironments()
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
}
