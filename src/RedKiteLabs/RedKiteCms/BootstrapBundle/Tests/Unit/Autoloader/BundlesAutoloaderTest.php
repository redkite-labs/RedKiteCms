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

namespace AlphaLemon\BootstrapBundle\Tests\Unit\Autoloader;

use AlphaLemon\BootstrapBundle\Tests\TestCase;
use AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader;
//use org\bovigo\vfs\vfsStream;

/**
 * BundlesAutoloaderTest
 * 
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class BundlesAutoloaderTest extends TestCase
{    
    private $bundlesAutoloader = null;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->pakegesBootstrapper = $this->getMock('AlphaLemon\BootstrapBundle\Core\PackagesBootstrapper\PackagesBootstrapperInterface');
        
        $folders = array('app' => array(),
                         'vendor' => array(),
                        );
        $this->root = vfsStream::setup('root', null, $folders);
        
        $this->bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->pakegesBootstrapper);
        print_r(vfsStream::inspect($this->root->getStructure()));
    }
    
    public function testAAA()
    {echo "\nT\n";exit;
        //$this->bundlesAutoloader->getBundles();
        //print_r(vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure());
    }
    
}