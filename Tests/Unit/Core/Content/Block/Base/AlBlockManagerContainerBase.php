<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Base\AlContentManagerBase;

/**
 * AlBlockManagerContainerBase instantiates a test for a block manager that injects the Contaione
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class AlBlockManagerContainerBase extends AlContentManagerBase
{
    protected $kernel;
    protected $validator;
    protected $blockRepository;
    protected $factoryRepository;
    protected $container;

    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');

        $this->validator = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
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
    }

    protected function doSave($block, array $params)
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent');
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