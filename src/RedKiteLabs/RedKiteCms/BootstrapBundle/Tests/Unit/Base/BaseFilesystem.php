<?php
/*
 * This file is part of the AlphaLemonBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace AlphaLemon\BootstrapBundle\Tests\Unit\Base;

use AlphaLemon\BootstrapBundle\Tests\TestCase;
use AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;

/**
 * BundlesAutoloaderTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
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

    protected function createBundle($bundleFolder, $bundleName, $autoload = null)
    {
        $this->createFolder($bundleFolder);
        $class = '<?php' . PHP_EOL;
        $class .= sprintf('namespace AlphaLemon\Block\%s;', $bundleName) . PHP_EOL;
        $class .= 'use Symfony\Component\HttpKernel\Bundle\Bundle;' . PHP_EOL;
        $class .= sprintf('class %s extends Bundle', $bundleName) . PHP_EOL;
        $class .= '{' . PHP_EOL;
        $class .= '}';
        $this->addClass($bundleFolder . $bundleName . '.php', $class);

        if(null === $autoload && false !== $autoload) {
            $autoload = '{' . PHP_EOL;
            $autoload .= '    "bundles" : {' . PHP_EOL;            
            $autoload .= sprintf('        "AlphaLemon\\\\Block\\\\%s\\\\%s" : {', $bundleName, $bundleName) . PHP_EOL;
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
        $classContent .= sprintf('namespace AlphaLemon\Block\%s\Core\ActionManager;', $bundleName) . PHP_EOL;
        $classContent .= 'use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManager;' . PHP_EOL;
        $classContent .= 'class ActionManagerBusinessCarousel extends ActionManager {}' . PHP_EOL;
        $classFile = $classFolder . $classFileName;

        $this->addClass($classFile, $classContent);
    }
}