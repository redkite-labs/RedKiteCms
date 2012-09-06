<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\Base;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;

/**
 * AlBlockManagerContainerBase instantiates a test for a block manager that injects the Contaione
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerContainerBase extends TestCase
{
    protected $dispatcher;
    protected $kernel;
    protected $validator;
    protected $blockRepository;
    protected $factoryRepository;
    protected $container;

    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
    }

    protected function initContainer()
    {
        $this->container->expects($this->exactly(2))
                        ->method('get')
                        ->will($this->onConsecutiveCalls($this->dispatcher,$this->factoryRepository));
    }
}
