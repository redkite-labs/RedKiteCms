<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\ConfigurationRepositoryPropel;

/**
 * ConfigurationRepositoryTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ConfigurationRepositoryTest extends TestCase
{
    private $configurationRepository;
    private $pdo;

    protected function setUp()
    {
        parent::setUp();

        $this->pdo = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel\Pdo\MockPDO');
        $this->configurationRepository = new ConfigurationRepositoryPropel($this->pdo);
    }

    public function testGetRepositoryObjectClassName()
    {
        $this->assertEquals('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Configuration', $this->configurationRepository->getRepositoryObjectClassName());
    }
    
    /**
     * @expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage exception_only_propel_configuration_objects_are_accepted
     */
    public function testModelObjectInjectedBySettersIsInvalid()
    {
        $modelObject = $this->getMock('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $this->configurationRepository->setRepositoryObject($modelObject);
    }

    public function testModelObjectInjectedBySetters()
    {
        $modelObject = $this->getMock('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Configuration');
        $this->assertEquals($this->configurationRepository, $this->configurationRepository->setRepositoryObject($modelObject));
        $this->assertEquals($modelObject, $this->configurationRepository->getModelObject());
    }
}