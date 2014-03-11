<?php

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\DependencyInjection;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\RedKiteLabsThemeEngineBundle;

/**
 * RedKiteLabsThemeEngineExtensionTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class RedKiteLabsThemeEngineBundleTest extends TestCase
{
    public function testGetAlias()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->expects($this->exactly(2))
            ->method('addCompilerPass')
        ;
        
        $bundle = new RedKiteLabsThemeEngineBundle($container);
        $bundle->build($container);
    }
    
}
