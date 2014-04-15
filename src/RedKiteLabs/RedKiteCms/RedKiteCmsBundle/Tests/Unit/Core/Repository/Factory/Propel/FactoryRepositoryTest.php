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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Repository\Factory\Propel;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepository;

/**
 * FactoryRepositoryTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class FactoryRepositoryTest extends TestCase
{
    private $factoryRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->factoryRepository = new FactoryRepository('Propel');
    }

    /**
     *@expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\Exception\RepositoryNotFoundException
     */
    public function testAnExceptionIsThrownWhenTheRepositoryClassDoesNotExist()
    {
        $this->factoryRepository->createRepository('Fake');
    }

    public function testARepositoryIsCreated()
    {
        $repository = $this->factoryRepository->createRepository('Block');
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\BlockRepositoryPropel', $repository);
    }

    public function testARepositoryPlacedOnACusyomNamespaceAndWithoutAlPefixed()
    {
        $repository = $this->factoryRepository->createRepository('Test', '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Repository');
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel\TestRepositoryPropel', $repository);
    }
}
