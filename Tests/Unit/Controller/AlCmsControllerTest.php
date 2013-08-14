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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Controller;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Controller\AlCmsController;


/**
 * DeployControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlCmsControllerTest extends TestCase
{
    private $request;

    protected function setUp()
    {
        $this->request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        
        $this->languageRepository =
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->pageRepository =
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->seoRepository =
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->factoryRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepository')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->templating
             ->expects($this->once())
             ->method('renderResponse')
             ->will($this->returnValue($response))
        ;
        
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');


        $this->controller = new AlCmsController();
        $this->controller->setContainer($this->container);
    }

    public function testAFlashMessageIsSetWhenPageTreeIsNull()
    {
        $this->initContainer(null);
        $this->initFactoryRepository();        
        
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translator->expects($this->once())
            ->method('trans')
            ->with('It seems that the "%page%" does not exist for the "%language%" language')
            ->will($this->returnValue('It seems that the "index" does not exist for the en "language"'))
        ;
        
        
        $flashBag = $this->getMock('Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface');
        $flashBag
            ->expects($this->once())
            ->method('add')
            ->with('notice', 'It seems that the "index" does not exist for the en "language"')
        ;
        
        $session =
            $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $session
            ->expects($this->once())
            ->method('getFlashBag')
            ->will($this->returnValue($flashBag))
        ;
        
        $this->container->expects($this->at(9))
            ->method('get')
            ->with('translator')
            ->will($this->returnValue($translator));
        
        $this->container->expects($this->at(10))
            ->method('get')
            ->with('session')
            ->will($this->returnValue($session));
        
        $this->container->expects($this->at(11))
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($this->templating));
        
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->container->expects($this->at(12))
            ->method('get')
            ->with('event_dispatcher')
            ->will($this->returnValue($dispatcher));
        
        $this->controller->showAction();        
    }
    
    public function testAFlashMessageIsSetWhenTemplateDoesNotExist()
    {
        $pageTree = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $pageTree
            ->expects($this->any())
            ->method('__call')
            ->will($this->returnValue(array()));
        ;
        
        $template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $pageTree
            ->expects($this->once())
            ->method('getTemplate')
            ->will($this->returnValue($template));
        ;
        
        $this->initContainer($pageTree);
        $this->initFactoryRepository();        
        
        $flashBag = $this->getMock('Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface');
        $flashBag
            ->expects($this->once())
            ->method('add')
            ->with('notice', 'The template assigned to this page does not exist. This appens when you change a theme with a different number of templates from the active one. To fix this issue you shoud activate the previous theme again and change the pages which cannot be rendered by this theme')
        ;
        
        $session =
            $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $session
            ->expects($this->once())
            ->method('getFlashBag')
            ->will($this->returnValue($flashBag))
        ;
        
        
        
        $blockManagerFactory = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $blockManagerFactory
            ->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array()))
        ;
        
        $templateSlots = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\ThemeChanger\AlTemplateSlots')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $templateSlots
            ->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue(array()))
        ;
        
        $templateSlots
            ->expects($this->once())
            ->method('run')
            ->will($this->returnSelf())
        ;
        
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translator->expects($this->once())
            ->method('trans')
            ->with('The template assigned to this page does not exist. This appens when you change a theme with a different number of templates from the active one. To fix this issue you shoud activate the previous theme again and change the pages which cannot be rendered by this theme')
            ->will($this->returnValue('The template assigned to this page does not exist. This appens when you change a theme with a different number of templates from the active one. To fix this issue you shoud activate the previous theme again and change the pages which cannot be rendered by this theme'))
        ;
        
        $this->container->expects($this->at(9))
            ->method('get')
            ->with('alpha_lemon_cms.template_slots')
            ->will($this->returnValue($templateSlots));
        
        $this->container->expects($this->at(10))
            ->method('get')
            ->with('translator')
            ->will($this->returnValue($translator))
        ;
        
        $this->container->expects($this->at(11))
            ->method('get')
            ->with('session')
            ->will($this->returnValue($session));
        
        $this->container->expects($this->at(13))
            ->method('get')
            ->with('alpha_lemon_cms.block_manager_factory')
            ->will($this->returnValue($blockManagerFactory));
                
        $this->container->expects($this->at(14))
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($this->templating));
        
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->container->expects($this->at(15))
            ->method('get')
            ->with('event_dispatcher')
            ->will($this->returnValue($dispatcher));
        
        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array()));
        
        $this->pageRepository->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array()));
        
        $this->controller->showAction();        
    }

    private function initContainer($pageTree)
    {
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($this->request));
        
        $this->container->expects($this->at(1))
            ->method('get')
            ->with('kernel')
            ->will($this->returnValue($this->kernel));
        
        $this->container->expects($this->at(2))
            ->method('get')
            ->with('alpha_lemon_cms.page_tree')
            ->will($this->returnValue($pageTree));

        $this->container->expects($this->at(3))
            ->method('get')
            ->with('security.context')
            ->will($this->returnValue($this->securityContext));
        
        $this->container->expects($this->at(4))
            ->method('get')
            ->with('alpha_lemon_cms.factory_repository')
            ->will($this->returnValue($this->factoryRepository));
        
        $this->configuration = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Configuration\AlConfigurationInterface');
        $this->configuration
            ->expects($this->once())
            ->method('read')
            ->with('language')
        ;
        
        $this->container->expects($this->at(5))
            ->method('get')
            ->with('alpha_lemon_cms.configuration')
            ->will($this->returnValue($this->configuration));
    }
    
    private function initFactoryRepository()
    {
        $this->factoryRepository->expects($this->at(0))
            ->method('createRepository')
            ->with('Language')
            ->will($this->returnValue($this->languageRepository));
        
        $this->factoryRepository->expects($this->at(1))
            ->method('createRepository')
            ->with('Page')
            ->will($this->returnValue($this->pageRepository));
        
        $this->factoryRepository->expects($this->at(2))
            ->method('createRepository')
            ->with('Seo')
            ->will($this->returnValue($this->seoRepository));
    }
}