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

namespace AlphaLemon\ThemeEngineBundle\Tests;

require_once __DIR__.'/../../../../../app/AppKernel.php';

class TestCase extends \PHPUnit_Framework_TestCase {
 
    private $container = null;
    
    protected function setUp()
    {
        if(null === $this->container)
        {
            $this->app = new \AppKernel('test', true);
            $this->app->boot();
            $this->container = $this->app->getContainer(); 
        }
    }
    
    public function getContainer()
    {
        return $this->container;
    }
}