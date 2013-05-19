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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Controller;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Controller\ThemePreviewController;


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
            $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->blockRepository =
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->blockRepository
             ->expects($this->any())
             ->method('getRepositoryObjectClassName')
             ->will($this->returnValue('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock'))
        ;
        
        $this->factoryRepository = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepository')
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
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->activeTheme = 
            $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveTheme')
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
        
        $template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
             ->disableOriginalConstructor()
             ->getMock()
        ;
        
        $template
            ->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue($slots)) 
        ;
                
        $method = ($templateName == 'none') ? 'getHomeTemplate' : 'getTemplate';
        
        $theme = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Theme\AlTheme')
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
        $slot = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot')
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
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\ServiceBlock\AlBlockManagerService')
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
             ->with('alpha_lemon_theme_engine.themes')
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
             ->with('alpha_lemon_theme_engine.active_theme')
             ->will($this->returnValue($this->activeTheme));
        
        $session =
            $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $session
            ->expects($this->once())
            ->method('setFlash')
            ->with('message', 'The template assigned to this page does not exist. This appens when you change a theme with a different number of templates from the active one. To fix this issue you shoud activate the previous theme again and change the pages which cannot be rendered by this theme')
        ;
                
        $this->container->expects($this->at(9))
            ->method('get')
            ->with('session')
            ->will($this->returnValue($session));
        
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request
            ->expects($this->once())
            ->method('getBaseUrl')
            ->will($this->returnValue(''))
        ;
        
        $this->container->expects($this->at(13))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request));
        
        $this->container
             ->expects($this->at(15))
             ->method('get')
             ->with('templating')
             ->will($this->returnValue($this->templating));
    }
}