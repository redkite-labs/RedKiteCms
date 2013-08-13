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
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Repository\Factory\Propel;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepository;

/**
 * AlFactoryRepositoryTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlFactoryRepositoryTest extends TestCase
{
    private $factoryRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->factoryRepository = new AlFactoryRepository('Propel');
    }

    /**
     *@expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\Exception\RepositoryNotFoundException
     */
    public function testAnExceptionIsThrownWhenTheRepositoryClassDoesNotExist()
    {
        $this->factoryRepository->createRepository('Fake');
    }

    public function testARepositoryIsCreated()
    {
        $repository = $this->factoryRepository->createRepository('Block');
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel', $repository);
    }

    public function testARepositoryPlacedOnACusyomNamespaceAndWithoutAlPefixed()
    {
        $repository = $this->factoryRepository->createRepository('Test', '\RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Repository');
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel\TestRepositoryPropel', $repository);
    }
}
