<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Event\Content\Base;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Deploy\Base\BaseDeployEvent;

class DeployEventTester extends BaseDeployEvent
{
}

/**
 * DeployEventTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class DeployEventTest extends TestCase
{
    private $deployer;

    public function testDeployerProperty()
    {
        $this->deployer = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlDeployerInterface');

        $this->event = new DeployEventTester($this->deployer);
        $deployer = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlDeployerInterface');
        $this->event->setDeployer($deployer);
        $this->assertSame($deployer, $this->event->getDeployer());        
        $this->assertNotSame($this->deployer, $this->event->getDeployer());
    }
}

