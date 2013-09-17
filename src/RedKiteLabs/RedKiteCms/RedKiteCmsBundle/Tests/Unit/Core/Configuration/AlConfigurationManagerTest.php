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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Configuration;

use RedKiteLabs\RedKiteCmsBundle\Core\Configuration\AlConfigurationManager;

/**
 * AlConfigurationManagerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlConfigurationManagerTest extends \RedKiteLabs\RedKiteCmsBundle\Tests\TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->configurationRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\ConfigurationRepositoryInterface');

        $factoryRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepository')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $factoryRepository->expects($this->at(0))
            ->method('createRepository')
            ->with('Configuration')
            ->will($this->returnValue($this->configurationRepository));

        $this->configurationManager = new AlConfigurationManager($factoryRepository);
    }
    

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException
     * @expectedExceptionMessage {"message":"exception_parameter_does_not_exist","parameters":{"%parameter%":"language"}}
     */
    public function testReadParameterThrownAnExceptionWhenrequestedParameterDoesNotExist()
    {
        $requiredParam = "language";
        $this->configurationRepository
            ->expects($this->once())
            ->method('fetchParameter')
            ->with($requiredParam)
            ->will($this->returnValue(null))
        ;

        $result = $this->configurationManager->read($requiredParam);
    }

    public function testReadParameter()
    {
        $expectedValue = 'en';
        $repository = $this->getMock("RedKiteLabs\RedKiteCmsBundle\Model\AlConfiguration");
        $repository
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($expectedValue))
        ;

        $requiredParam = "language";
        $this->configurationRepository
            ->expects($this->once())
            ->method('fetchParameter')
            ->with($requiredParam)
            ->will($this->returnValue($repository))
        ;

        $result = $this->configurationManager->read($requiredParam);
        $this->assertEquals($expectedValue, $result);

        return $this->configurationManager;
    }

    /**
     * @depends testReadParameter
     */
    public function testCachedParameter($configurationManager)
    {
        $expectedValue = 'en';
        $requiredParam = "language";
        $this->configurationRepository
            ->expects($this->never())
            ->method('fetchParameter')
        ;

        $result = $configurationManager->read($requiredParam);
        $this->assertEquals($expectedValue, $result);
    }

    public function testWriteParameter()
    {
        $newValue = 'it';
        $repository = $this->getMock("RedKiteLabs\RedKiteCmsBundle\Model\AlConfiguration");
        $repository
            ->expects($this->once())
            ->method('setValue')
            ->with($newValue)
        ;

        $repository
            ->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true))
        ;

        $requiredParam = "language";
        $this->configurationRepository
            ->expects($this->once())
            ->method('fetchParameter')
            ->with($requiredParam)
            ->will($this->returnValue($repository))
        ;

        $result = $this->configurationManager->write($requiredParam, $newValue);
        $this->assertTrue($result);
    }
}