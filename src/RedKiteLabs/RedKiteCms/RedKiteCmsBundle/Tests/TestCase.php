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
namespace AlphaLemon\AlphaLemonCmsBundle\Tests;



use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;




class TestCase extends \PHPUnit_Framework_TestCase {

    protected $connection = null;

    protected function setUp()
    {
        $this->connection = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Tests\Pdo\MockPDO');
    }

    public static function setUpBeforeClass()
    {
        $config = array("datasources" => array (
            "default" => array (
                "adapter" => "mysql",
                "connection" => array
                (
                    "dsn" => "mysql:host=localhost;dbname=alphalemon_test",
                    "user" => "root",
                    "password" => "",
                    "classname" => "DebugPDO",
                    "options" => array(),
                    "attributes" => array (),
                    "settings" => array (),
                )
            )
        ));

        if (!\Propel::isInit()) {
            \Propel::setConfiguration($config);
            \Propel::initialize();
        }
    }

    protected static function callMethod($obj, $name, array $args = array())
    {
        $object = new \ReflectionClass($obj);
        $method = $object->getMethod($name);
        $method->setAccesible(true);

        return $method->invokeArgs($obj, $args);
    }
}