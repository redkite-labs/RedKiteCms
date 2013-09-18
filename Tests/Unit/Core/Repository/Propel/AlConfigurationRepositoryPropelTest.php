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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlConfigurationRepositoryPropel;

/**
 * AlConfigurationRepositoryTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlConfigurationRepositoryTest extends TestCase
{
    private $configurationRepository;
    private $pdo;

    protected function setUp()
    {
        parent::setUp();

        $this->pdo = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel\Pdo\MockPDO');
        $this->configurationRepository = new AlConfigurationRepositoryPropel($this->pdo);
    }

    public function testGetRepositoryObjectClassName()
    {
        $this->assertEquals('\RedKiteLabs\RedKiteCmsBundle\Model\AlConfiguration', $this->configurationRepository->getRepositoryObjectClassName());
    }
    
    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage exception_only_propel_configuration_objects_are_accepted
     */
    public function testModelObjectInjectedBySettersIsInvalid()
    {
        $modelObject = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $this->configurationRepository->setRepositoryObject($modelObject);
    }

    public function testModelObjectInjectedBySetters()
    {
        $modelObject = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Model\AlConfiguration');
        $this->assertEquals($this->configurationRepository, $this->configurationRepository->setRepositoryObject($modelObject));
        $this->assertEquals($modelObject, $this->configurationRepository->getModelObject());
    }
}