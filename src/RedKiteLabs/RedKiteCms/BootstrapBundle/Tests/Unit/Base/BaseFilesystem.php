<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\BootstrapBundle\Tests\Unit\Base;

use RedKiteLabs\BootstrapBundle\Tests\TestCase;
use RedKiteLabs\BootstrapBundle\Core\Autoloader\BundlesAutoloader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;

/**
 * BundlesAutoloaderTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BaseFilesystem extends TestCase
{
    protected function createFile($path, $contents = null)
    {
        file_put_contents(vfsStream::url($path), $contents);
    }

    protected function createFolder($folder, $permissions = 0777)
    {
        mkdir(vfsStream::url($folder), $permissions, true);
    }

    protected function createBundle($bundleFolder, $bundleName, $autoload = null, $namespace = null)
    {
        $namespace = (null === $namespace) ? 'RedKiteLabs\Block' : $namespace;
        $this->createFolder($bundleFolder);
        $class = '<?php' . PHP_EOL;
        $class .= sprintf('namespace %s\%s;', $namespace, $bundleName) . PHP_EOL;
        $class .= 'use Symfony\Component\HttpKernel\Bundle\Bundle;' . PHP_EOL;
        $class .= sprintf('class %s extends Bundle', $bundleName) . PHP_EOL;
        $class .= '{' . PHP_EOL;
        $class .= '}';
        $this->addClass($bundleFolder . $bundleName . '.php', $class);

        if(null === $autoload && false !== $autoload) {
            $autoload = '{' . PHP_EOL;
            $autoload .= '    "bundles" : {' . PHP_EOL;
            $autoload .= sprintf('        "RedKiteLabs\\\\Block\\\\%s\\\\%s" : {', $bundleName, $bundleName) . PHP_EOL;
            $autoload .= '           "environments" : ["all"]' . PHP_EOL;
            $autoload .= '        }' . PHP_EOL;
            $autoload .= '    }' . PHP_EOL;
            $autoload .= '}';
        }
        if(is_string($autoload)) $this->createFile($bundleFolder . 'autoload.json', $autoload);
    }

    protected function addClass($classFile, $classContent)
    {
        $this->createFile($classFile, $classContent);

        require_once vfsStream::url($classFile);
    }

    protected function addClassManager($classFolder, $classFileName, $bundleName)
    {
        $this->createFolder($classFolder);

        $classContent = '<?php' . PHP_EOL;
        $classContent .= sprintf('namespace RedKiteLabs\Block\%s\Core\ActionManager;', $bundleName) . PHP_EOL;
        $classContent .= 'use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManager;' . PHP_EOL;
        $classContent .= 'class ActionManagerBusinessCarousel extends ActionManager {}' . PHP_EOL;
        $classFile = $classFolder . $classFileName;

        $this->addClass($classFile, $classContent);
    }


    protected function createAutoloadNamespacesFile($autoloadNamespaces = null)
    {
        if(null === $autoloadNamespaces) {
            $autoloadNamespaces = '<?php' . PHP_EOL;
            $autoloadNamespaces .= '$vendorDir = dirname(__DIR__);' . PHP_EOL;
            $autoloadNamespaces .= '$baseDir = dirname($vendorDir);' . PHP_EOL;
            $autoloadNamespaces .= 'return array(' . PHP_EOL;
            $autoloadNamespaces .= '    \'RedKiteLabs\\Block\\BusinessCarouselFakeBundle\' => $vendorDir . \'/alphalemon/app-business-carousel-bundle/\',' . PHP_EOL;
            $autoloadNamespaces .= '    \'RedKiteLabs\\Block\\BusinessDropCapFakeBundle\' => $vendorDir . \'/alphalemon/app-business-dropcap-bundle/\',' . PHP_EOL;
            $autoloadNamespaces .= '    \'RedKiteLabs\\RedKiteLabsCms\\RedKiteLabsCmsFakeBundle\' => $vendorDir . \'/alphalemon/alphalemon-cms-bundle/\',' . PHP_EOL;
            $autoloadNamespaces .= ');' . PHP_EOL;
        }

        $this->createFile('root/vendor/composer/autoload_namespaces.php', $autoloadNamespaces);
    }
}