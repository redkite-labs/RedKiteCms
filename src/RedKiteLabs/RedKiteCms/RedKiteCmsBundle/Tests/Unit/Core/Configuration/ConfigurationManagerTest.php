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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Configuration;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Configuration\ConfigurationManager;

/**
 * ConfigurationManagerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ConfigurationManagerTest extends \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->configurationRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\ConfigurationRepositoryInterface');

        $factoryRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepository')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $factoryRepository->expects($this->at(0))
            ->method('createRepository')
            ->with('Configuration')
            ->will($this->returnValue($this->configurationRepository));

        $this->configurationManager = new ConfigurationManager($factoryRepository);
    }
    

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException
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
        $repository = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Configuration");
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
        $repository = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Configuration");
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