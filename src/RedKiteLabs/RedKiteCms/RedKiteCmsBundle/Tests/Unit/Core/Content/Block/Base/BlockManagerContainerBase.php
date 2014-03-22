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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Base\ContentManagerBase;

/**
 * BlockManagerContainerBase instantiates a test for a block manager that injects the Contaione
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BlockManagerContainerBase extends ContentManagerBase
{
    protected $kernel;
    protected $validator;
    protected $blockRepository;
    protected $factoryRepository;
    protected $container;
    protected $translator;

    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');

        $this->validator = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\BlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->initRepository();
        
        $this->translator = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Translator\TranslatorInterface');

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
    }

    public function bootstrapVersionsProvider()
    {
        return array(
            array(
                "2.x"
            ),
            array(
                "3.x"
            ),
        );
    }

    protected function initContainer()
    {
        $this->container->expects($this->at(0))
                        ->method('get')
                        ->with('red_kite_cms.events_handler')
                        ->will($this->returnValue($this->eventsHandler));
        
        $this->container->expects($this->at(1))
                        ->method('get')
                        ->with('red_kite_cms.factory_repository')
                        ->will($this->returnValue($this->factoryRepository));
        
        $this->container->expects($this->at(2))
                        ->method('get')
                        ->with('red_kite_cms.translator')
                        ->will($this->returnValue($this->translator));
    }
    
    protected function initRepository()
    {
        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));
    }

    protected function initBootstrapversion($bootstrapVersion)
    {
        $activeTheme = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\ActiveThemeInterface');
        $activeTheme->expects($this->once())
            ->method('getThemeBootstrapVersion')
            ->will($this->returnValue($bootstrapVersion));
        $this->container->expects($this->at(3))
            ->method('get')
            ->with('red_kite_cms.active_theme')
            ->will($this->returnValue($activeTheme));
    }

    protected function doSave($block, array $params)
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent');
        $this->setUpEventsHandler($event, 2);

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));

         $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block);

        $result = $this->blockManager->set($block)
                                     ->save($params);
        $this->assertEquals(true, $result);
    }
}