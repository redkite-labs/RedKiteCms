<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
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
            ->with('red_kite_labs_theme_engine.themes')
            ->will($this->returnValue(false));
        
        $this->compiler->process($builder, 'theme');
    }
   
    public function testThemeDefinitionHasBeenProcessed()
    {
        $themeDefinition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $themeDefinition->expects($this->atLeastOnce())
            ->method('addMethodCall')
            ->with('addTheme', array(new Reference('business_website.theme')))
        ;
        
        $builder = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $at = 0;
        $builder->expects($this->at($at))
            ->method('hasDefinition')
            ->with('red_kite_labs_theme_engine.themes')
            ->will($this->returnValue(true));
        
        $at++;
        $builder->expects($this->at($at))
            ->method('getDefinition')
            ->will($this->returnValue($themeDefinition));
        
        $at++;
        $themeServices = array('business_website.theme' => array(array()));        
        $builder->expects($this->at($at))
            ->method('findTaggedServiceIds')
            ->with('red_kite_labs_theme_engine.themes.theme')
            ->will($this->returnValue($themeServices));
        
        $at++;
        $themeSlotsDefinitionId = 'business_website.theme_slots';
        $builder->expects($this->at($at))
            ->method('hasDefinition')
            ->with($themeSlotsDefinitionId)
            ->will($this->returnValue(true));
        
        
        $themeSlotsDefinition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $themeSlotsDefinition->expects($this->once())
            ->method('addMethodCall')
            ->with('addSlot', array(new Reference('business_website.theme.template.slots.slot_name')))
        ;
        
        $at++;
        $builder->expects($this->at($at))
            ->method('getDefinition')
            ->will($this->returnValue($themeSlotsDefinition));
        
        $slotsDefinition = array(
            "business_website.theme.template.slots.slot_name" => array(
                array (),
            ),
        );
        
        $at++;
        $builder->expects($this->at($at))
            ->method('findTaggedServiceIds')
            ->with('business_website.theme.template.slot')
            ->will($this->returnValue($slotsDefinition));
        
        
        $templateDefinition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $templateDefinition->expects($this->atLeastOnce())
            ->method('addMethodCall')
            ->with('addTemplate', array(new Reference('business_website.theme.template.template_name')))
        ;
        
        $at++;
        $builder->expects($this->at($at))
            ->method('getDefinition')
            ->will($this->returnValue($templateDefinition));
        
        $templateDefinition = array(
            "business_website.theme.template.template_name" => array(
                array (),
            ),
        );
        $at++;
        $builder->expects($this->at($at))
            ->method('findTaggedServiceIds')
            ->with('business_website.theme.template')
            ->will($this->returnValue($templateDefinition));
        
        $this->compiler->process($builder);
    }
}
