<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 * 
 */

namespace AlphaLemon\ThemeEngineBundle\Tests\Functional\AlphaLemon\ThemeEngineBundle\Core\Autoloader\Base;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Autoloader\Base\BundlesAutoloader;

/**
 * A wrapper class useful for testing
 */
class TestAutoloader extends BundlesAutoloader
{
    private $params;
    
    public function __construct(array $params)
    {
        $this->params = $params;
        
        parent::__construct();
    }
    
    protected function configure()
    {
        return $this->params;
    }
}

class BundlesAutoloaderTest extends TestCase 
{    
    /**
     * @expectedException \InvalidArgumentException
     */    
    public function testPassingAnInvalidOption()
    {
        $autoloader = new TestAutoloader(array('foo' => 'bar'));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */    
    public function testPassingJustOneValidOption()
    {
        $autoloader = new TestAutoloader(array('pathToSeek' => __DIR__));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */    
    public function testPassingAnInvalidValueForPathToSeekOption()
    {
        $autoloader = new TestAutoloader(array('pathToSeek' => null, 'nameSpace' => 'Themes'));
    }
        
    /**
     * @expectedException \InvalidArgumentException
     */   
    public function testPassingAnInvalidValueForNameSpaceOption()
    {
        $autoloader = new TestAutoloader(array('pathToSeek' => __DIR__ . '/../../../../../Themes', 'nameSpace' => 'fake'));
        $autoloader->getBundles();
    }
    
    public function testBundlesAutoloaded()
    {
        $autoloader = new TestAutoloader(array('pathToSeek' => __DIR__ . '/../../../../../Themes', 'nameSpace' => 'Themes'));
        $this->assertNotEquals(0, count($autoloader->getBundles()));
    }
}