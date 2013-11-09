<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Tests\DependencyInjection;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\DependencyInjection\RedKiteCmsBaseBlocksExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * RedKiteCmsBaseBlocksExtensionTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class RedKiteCmsBaseBlocksExtensionTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
    }

    public function testAlias()
    {
        $extension = new RedKiteCmsBaseBlocksExtension();
        $this->assertEquals('red_kite_cms_base_blocks', $extension->getAlias());
    }
    
    public function testDefaultConfiguration()
    {
        $extension = new RedKiteCmsBaseBlocksExtension();
        $extension->load(array(array()), $this->container);
        $this->assertEquals('@RedKiteCmsBaseBlocksBundle/Resources/public/languages_menu/images', $this->container->getParameter('red_kite_cms.flags_folder'));
    }
    
    public function testChangeFlagsFolderConfiguration()
    {
        $configValue = "/web/flags";
        $extension = new RedKiteCmsBaseBlocksExtension();
        $extension->load(array(array('flags_folder' => $configValue)), $this->container);
        $this->assertEquals($configValue, $this->container->getParameter('red_kite_cms.flags_folder'));
    }
}
