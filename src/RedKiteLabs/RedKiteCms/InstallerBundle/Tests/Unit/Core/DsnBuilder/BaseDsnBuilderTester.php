<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Tests\Unit\Core\DsnBuilder;

use RedKiteLabs\RedKiteCms\InstallerBundle\Tests\TestCase;


/**
 * BaseDsnBuilderTester
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseDsnBuilderTester extends TestCase
{
    public abstract function dsnProvider();
    protected abstract function setUpDsnBuilder(array $options);

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnExceptionIsThrownWhenOptionsArrayIsEmpty()
    {
        $this->setUpDsnBuilder(array());
    }
    
    /**
     * @dataProvider dsnProvider
     */
    public function testDsnGeneration(array $options, $baseDsn, $dsn, $parametrizedDsn)
    {
        $dsnBuilder = $this->setUpDsnBuilder($options);
        $this->assertEquals($baseDsn, $dsnBuilder->getBaseDsn());
        $this->assertEquals($dsn, $dsnBuilder->getDsn());
        $this->assertEquals($parametrizedDsn, $dsnBuilder->getParametrizedDsn());
    }
}