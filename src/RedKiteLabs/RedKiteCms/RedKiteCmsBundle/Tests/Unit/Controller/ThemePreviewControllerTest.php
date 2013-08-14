<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Controller;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Controller\ThemePreviewController;


/**
 * ThemePreviewControllerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ThemePreviewControllerTest extends TestCase
{
    private $kernel;
    private $pageManager;
    private $factoryRepository;
    private $activeTheme;
    private $blockRepository;
    private $pageRepository;
    private $blocksFactory;
    private $templating;

    protected function setUp()
    {
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        
        $this->themes = 
            $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->blockRepository =
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->blockRepository
             ->expects($this->any())
             ->method('getRepositoryObjectClassName')
             ->will($this->returnValue('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock'))
        ;
        
        $this->factoryRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepository')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->factoryRepository
             ->expects($this->at(0))
             ->method('createRepository')
             ->with('Block')
             ->will($this->returnValue($this->blockRepository))
        ;
        
        $this->blocksFactory = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->activeTheme = 
            $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlActiveTheme')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->templating
             ->expects($this->once())
             ->method('renderResponse')
        ;
        
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        
        $this->controller = new ThemePreviewController();
        $this->controller->setContainer($this->container);
    }

    /**
     * @dataProvider previewProvider
     */
    public function testThemePreview($templateName, $slotArguments)
    {
        $blocksSequence = 0;
        $slots = array();
        foreach ($slotArguments as $slotArgument) {
            $type = $slotArgument['type'];
            $content = $slotArgument['content'];
            $slots[] = $this->initSlot($type, $content);
            
            $this->blocksFactory
                ->expects($this->at($blocksSequence))
                ->method('createBlockManager')
                ->with($type)
                ->will($this->returnValue($this->initBlockManager($content)))
            ;
            
            $blocksSequence++;
        }
        
        $template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
             ->disableOriginalConstructor()
             ->getMock()
        ;
        
        $template
            ->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue($slots)) 
        ;
                
        $method = ($templateName == 'none') ? 'getHomeTemplate' : 'getTemplate';
        
        $theme = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme')
             ->disableOriginalConstructor()
             ->getMock()
        ;
        
        $theme
            ->expects($this->once())
            ->method('getTemplates')
            ->will($this->returnValue(array('home' => null, 'internal' => null)))
        ;
        
        $theme
            ->expects($this->once())
            ->method($method)
            ->will($this->returnValue($template))
        ;
        
        $themeName = 'BootbusinessThemeBundle';
        $this->themes 
             ->expects($this->at(0))
             ->method('getTheme')
             ->with($themeName)
             ->will($this->returnValue($theme))
        ;        
        
        $this->initContainer();
                
        $this->controller->previewThemeAction('en', 'index', $themeName, $templateName);
    }
    
    public function previewProvider()
    {
        return array(
            array(
                'none',
                array(
                    array(
                        'type' => 'Text',
                        'content' => 'content 1',
                    ),
                ),
            ),
            array(
                'home',
                array(
                    array(
                        'type' => 'Text',
                        'content' => 'content 1',
                    ),
                ),
            ),
            array(
                'home',
                array(
                    array(
                        'type' => 'Text',
                        'content' => 'content 1',
                    ),
                    
                    array(
                        'type' => 'Menu',
                        'content' => 'content 2',
                    ),
                )
            ),
            array(
                'home',
                array(
                    array(
                        'type' => 'Text',
                        'content' => 'content 1',
                    ),
                    
                    array(
                        'type' => 'Menu',
                        'content' => null,
                    ),
                )
            ),
        );
    }    
    
    protected function initSlot($type, $content)
    {
        $slot = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot')
             ->disableOriginalConstructor()
             ->getMock()
        ;
        
        $slot
            ->expects($this->at(0))
            ->method('getSlotName')
        ;
        
        $slot
            ->expects($this->at(1))
            ->method('getBlockType')
            ->will($this->returnValue($type))
        ;
        
        $slot
            ->expects($this->at(2))
            ->method('getContent')
            ->will($this->returnValue($content))
        ;
        
        return $slot;
    }
    
    protected function initBlockManager($content)
    {
        $blockManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\ServiceBlock\AlBlockManagerService')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        if (null === $content) {
            $blockManager 
                 ->expects($this->once())
                 ->method('getDefaultValue')
                 ->will($this->returnValue(array('Content' => 'default content')));
            ;
        }
        
        return $blockManager;
    }
        
    protected function initContainer()
    {
        $this->container
             ->expects($this->at(0))
             ->method('get')
             ->with('kernel')
             ->will($this->returnValue($this->kernel));

        $this->container
             ->expects($this->at(1))
             ->method('get')
             ->with('red_kite_labs_theme_engine.themes')
             ->will($this->returnValue($this->themes));

        $this->container
             ->expects($this->at(2))
             ->method('get')
             ->with('alpha_lemon_cms.factory_repository')
             ->will($this->returnValue($this->factoryRepository));
        
        $this->container
             ->expects($this->at(3))
             ->method('get')
             ->with('alpha_lemon_cms.block_manager_factory')
             ->will($this->returnValue($this->blocksFactory));
        
        $this->container
             ->expects($this->at(4))
             ->method('get')
             ->with('red_kite_labs_theme_engine.active_theme')
             ->will($this->returnValue($this->activeTheme));
        
        $templateManager =
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $templateManager
             ->expects($this->once())
             ->method('setTemplate')
             ->will($this->returnSelf())
        ;
        
        $themesCollectionWrapper =
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $themesCollectionWrapper
             ->expects($this->once())
             ->method('getTemplateManager')
             ->will($this->returnValue($templateManager))
        ;
        
        $this->container
             ->expects($this->at(5))
             ->method('get')
             ->with('alpha_lemon_cms.themes_collection_wrapper')
             ->will($this->returnValue($themesCollectionWrapper));
        
        $session =
            $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request
            ->expects($this->once())
            ->method('getBaseUrl')
            ->will($this->returnValue(''))
        ;
        
        $this->container->expects($this->at(13))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request))
        ;
        
        $this->configuration = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Configuration\AlConfigurationInterface');
        $this->container->expects($this->at(15))
            ->method('get')
            ->with('alpha_lemon_cms.configuration')
            ->will($this->returnValue($this->configuration))
        ;
        
        $this->container
             ->expects($this->at(16))
             ->method('get')
             ->with('templating')
             ->will($this->returnValue($this->templating))
        ;
    }
}