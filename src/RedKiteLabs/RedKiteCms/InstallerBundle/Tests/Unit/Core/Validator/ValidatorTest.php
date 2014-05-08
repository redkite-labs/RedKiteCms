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

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Tests\Unit\Core\Validator;

use RedKiteLabs\RedKiteCms\InstallerBundle\Core\BowerBuilder\BowerBuilder;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Validator\Validator;
use RedKiteLabs\RedKiteCms\InstallerBundle\Tests\TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * ValidatorTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ValidatorTest extends TestCase
{
    private $validator;

    protected function setUp()
    {
        parent::setUp();

        $this->validator = new Validator();
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The bundle name must end with Bundle
     */
    public function testInvalidBundleName()
    {
        $this->validator->validateBundleName('Foo');
    }

    public function testBundleName()
    {
        $bundleName = "FooBundle";
        $this->assertEquals($bundleName, $this->validator->validateBundleName($bundleName));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage RedKite CMS requires an existing bundle to work with. You enter as working bundle the following: FooBundle but, the bundle is not enable in AppKernel.php file. Please add the bundle or enable it ther run the script again.
     */
    public function testDeployBundleIsNotRegistered()
    {
        $appKernelContents = '
        <?php

        class AppKernel extends Kernel
        {
            public function registerBundles()
            {
                $bundles = array(
                    new Acme\BarBundle(),
            );
        }';
        $this->buildStructure($appKernelContents);
        $this->validator->validateDeployBundle(vfsStream::url('root/app'), 'FooBundle');
    }

    public function testDeployBundle()
    {
        $appKernelContents = '
        <?php

        class AppKernel extends Kernel
        {
            public function registerBundles()
            {
                $bundles = array(
                    new Acme\FooBundle(),
            );
        }';
        $this->buildStructure($appKernelContents);
        $this->validator->validateDeployBundle(vfsStream::url('root/app'), 'FooBundle');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Driver value must be one of the following: [mysql, pgsql, sqlite]
     */
    public function testInvalidDriver()
    {
        $this->validator->validateDriver('oracle');
    }

    public function testDriver()
    {
        $driver = "mysql";
        $this->assertEquals($driver, $this->validator->validateDriver($driver));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The host value contains invalid characters
     */
    public function testInvalidHost()
    {
        $this->validator->validateHost('127.0.0.1@');
    }

    /**
     * @dataProvider hostsProvider
     */
    public function testHost($host)
    {
        $this->assertEquals($host, $this->validator->validateHost($host));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The given database name contains invalid characters: allowed characters are letters, numbers and the characters inside the square brackets [_-$£]
     */
    public function testInvalidDatabaseName()
    {
        $this->validator->validateDatabaseName('mydb(');
    }

    /**
     * @dataProvider databaseProvider
     */
    public function testDatabaseName($name)
    {
        $this->assertEquals($name, $this->validator->validateDatabaseName($name));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The port value contains invalid characters
     */
    public function testInvalidPort()
    {
        $this->validator->validatePort('3306A');
    }

    public function testPort()
    {
        $port = "3306";
        $this->assertEquals($port, $this->validator->validatePort($port));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The user value contains invalid characters
     */
    public function testInvalidUser()
    {
        $this->validator->validateUser('admin%');
    }

    public function testUser()
    {
        $user = "admin";
        $this->assertEquals($user, $this->validator->validateUser($user));
    }

    public function test()
    {
        $password = "mysecret";
        $this->assertEquals($password, $this->validator->validatePassword($password));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Website url must start with "http://" or "https://" and must end with "/"
     * @dataProvider invalidUrlProvider
     */
    public function testInvalidUrl($url)
    {
        $this->validator->validateUrl($url);
    }

    /**
     * @dataProvider validUrlProvider
     */
    public function testUrl($url)
    {
        $this->assertEquals($url, $this->validator->validateUrl($url));
    }

    public function invalidUrlProvider()
    {
        return array(
            array(
                'example.com',
            ),
            array(
                'example.com/',
            ),
            array(
                'htpp://example.com/',
            ),
            array(
                'http://example.com',
            ),
            array(
                'https://example.com'
            ),
        );
    }

    public function validUrlProvider()
    {
        return array(
           array(
                'http://example.com/',
            ),
            array(
                'https://example.com/'
            ),
        );
    }

    public function hostsProvider()
    {
        return array(
            array(
                'localhost',
            ),
            array(
                '127.0.0.1',
            ),
            array(
                'http://db.example.com'
            ),
        );
    }

    public function databaseProvider()
    {
        return array(
            array(
                'mydb',
            ),
            array(
                'mydb_',
            ),
            array(
                'mydb-'
            ),
            array(
                'mydb$'
            ),
            array(
                'mydb£'
            ),
        );
    }


    private function buildStructure($appKernelContents)
    {
        $structure =
            array(
                'app' => array(
                    'AppKernel.php' => $appKernelContents,
                ),
            )
        ;
        return vfsStream::setup('root', null, $structure);
    }
}