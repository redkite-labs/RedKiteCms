<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Rendering\Compiler;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Compiler\AlThemesCollectionCompilerPass;
use Symfony\Component\DependencyInjection\Reference;

/**
 * BasePageRenderingListenerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlThemesCollectionCompilerPassTest extends TestCase
{
    private $compiler;
    
    protected function setup()
    {
        $this->compiler = new AlThemesCollectionCompilerPass();
    }
    
    public function testEventsDispatcherDefinitionDoesNotExist()
    {
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $definition->expects($this->never())
            ->method('addMethodCall');
        
        $builder = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $builder->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(false));
        
        $this->compiler->process($builder, 'theme');
    }
    
    /**
     * @dataProviderx eventsSubscriberProvider $services, $results
     */
    public function testAnExceptionIsThrownWhenRenderSlotContentsMethodDoesNotReturnAnArray()
    {
        $themeDefinition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $themeDefinition->expects($this->atLeastOnce())
            ->method('addMethodCall')
            ->with('addTheme', array(new Reference('business_website.theme')))
        ;
        
        $templateDefinition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $templateDefinition->expects($this->atLeastOnce())
            ->method('addMethodCall')
            ->with('addTemplate', array(new Reference('business_website.theme.template.home')))
        ;
        
        $baseSlotDefinition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $baseSlotDefinition->expects($this->atLeastOnce())
            ->method('addMethodCall')
            ->with('addSlot', array(new Reference('business_website.theme.template.base.slots')))
        ;
        
        $homeSlotDefinition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $homeSlotDefinition->expects($this->atLeastOnce())
            ->method('addMethodCall')
            ->with('addSlot', array(new Reference('business_website.theme.template.home.slots')))
        ;
        
        $builder = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $builder->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(true));
        
        $builder->expects($this->at(1))
            ->method('getDefinition')
            ->will($this->returnValue($themeDefinition));
        
        $themeServices = array('business_website.theme' => array(array()));        
        $builder->expects($this->at(2))
            ->method('findTaggedServiceIds')
            ->will($this->returnValue($themeServices));
        
        $builder->expects($this->at(3))
            ->method('getDefinition')
            ->will($this->returnValue($templateDefinition));
        
        $templateServices = array('business_website.theme.template.home' => array(array()));      
        $builder->expects($this->at(4))
            ->method('findTaggedServiceIds')
            ->will($this->returnValue($templateServices));
        
        $builder->expects($this->at(5))
            ->method('getDefinition')
            ->will($this->returnValue($baseSlotDefinition));
        
        $baseSlotServices = array('business_website.theme.template.base.slots' => array(array()));      
        $builder->expects($this->at(6))
            ->method('findTaggedServiceIds')
            ->will($this->returnValue($baseSlotServices));
        
        $builder->expects($this->at(7))
            ->method('getDefinition')
            ->will($this->returnValue($homeSlotDefinition));
        
        $homeSlotServices = array('business_website.theme.template.home.slots' => array(array()));      
        $builder->expects($this->at(8))
            ->method('findTaggedServiceIds')
            ->will($this->returnValue($homeSlotServices));
        
        
        $this->compiler->process($builder);
    }
}
