<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Controller;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Controller\AlCmsController;


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
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->pageRepository =
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->seoRepository =
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->factoryRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepository')
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
            ->with('cms_controller_page_not_exists_for_given_language')
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
        
        $configuration = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Configuration\AlConfigurationInterface');
        $configuration
            ->expects($this->once())
            ->method('read')
            ->with('language')
        ;
        
        $at = 7;
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('red_kite_cms.configuration')
            ->will($this->returnValue($configuration));
        $at++;
        
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('translator')
            ->will($this->returnValue($translator));
        $at++;
        
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('session')
            ->will($this->returnValue($session));
        $at++;
        
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($this->templating));
        $at++;
        
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('event_dispatcher')
            ->will($this->returnValue($dispatcher));
        
        $this->controller->showAction($this->request);
    }
    
    public function testAFlashMessageIsSetWhenTemplateDoesNotExist()
    {
        $pageTree = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree')
                 ->setMethods(array('getAlPage', 'getAlLanguage', 'getTemplate', 'getInternalStylesheets', 'getInternalJavascripts', 'getExternalStylesheets', 'getExternalJavascripts'))
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        
        $pageTree
            ->expects($this->once())
            ->method('getExternalJavascripts')
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
        
        $blockManagerFactory = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $blockManagerFactory
            ->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array()))
        ;
        
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translator->expects($this->once())
            ->method('trans')
            ->with('The template assigned to this page does not exist. This happens when you change a theme with a different number of templates from the active one. To fix this issue you shoud activate the previous theme again and change the pages which cannot be rendered by this theme')
            ->will($this->returnValue('The template assigned to this page does not exist. This appens when you change a theme with a different number of templates from the active one. To fix this issue you shoud activate the previous theme again and change the pages which cannot be rendered by this theme'))
        ;
        
        $configuration = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Configuration\AlConfigurationInterface');
        $configuration
            ->expects($this->once())
            ->method('read')
            ->with('language')
        ;
        
        $at = 7;
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('red_kite_cms.configuration')
            ->will($this->returnValue($configuration))
        ;
        $at++;
        
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('translator')
            ->will($this->returnValue($translator))
        ;
        $at++;
        
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('session')
            ->will($this->returnValue($session))
        ;
        $at += 2;
        
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('red_kite_cms.block_manager_factory')
            ->will($this->returnValue($blockManagerFactory))
        ;
        $at++;
                
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($this->templating))
        ;
        $at++;
        
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('event_dispatcher')
            ->will($this->returnValue($dispatcher))
        ;
        
        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array()));
        
        $this->pageRepository->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array()));
        
        $this->controller->showAction($this->request);
    }

    private function initContainer($pageTree)
    {
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('kernel')
            ->will($this->returnValue($this->kernel));
        
        $this->container->expects($this->at(1))
            ->method('get')
            ->with('red_kite_cms.page_tree')
            ->will($this->returnValue($pageTree));

        $this->container->expects($this->at(2))
            ->method('get')
            ->with('security.context')
            ->will($this->returnValue($this->securityContext));
        
        $this->container->expects($this->at(3))
            ->method('get')
            ->with('red_kite_cms.factory_repository')
            ->will($this->returnValue($this->factoryRepository));
        
        $activeTheme = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\AlActiveThemeInterface');
        $activeTheme->expects($this->once())
            ->method('getThemeBootstrapVersion')
            ->will($this->returnValue('2.x'));
        
        $this->container->expects($this->at(4))
            ->method('get')
            ->with('red_kite_cms.active_theme')
            ->will($this->returnValue($activeTheme));
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