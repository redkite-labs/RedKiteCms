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

/**
 * AlFactoryRepositoryTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlPropelRepositoryTest extends TestCase
{
    private $propelRepository;
    private $pdo;

    protected function setUp()
    {
        parent::setUp();

        $this->pdo = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel\Pdo\MockPDO');
        $this->propelRepository = new TestRepositoryPropel($this->pdo);
        $this->modelObject = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
    }

    public function testPdoConnectionInjectedBySetters()
    {
        $pdo = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel\Pdo\MockPDO');
        $this->assertEquals($this->propelRepository, $this->propelRepository->setConnection($pdo));
        $this->assertEquals($pdo, $this->propelRepository->getConnection());
        $this->assertNotSame($this->pdo, $this->propelRepository->getConnection());
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage AlPropelRepository accepts only objects derived from propel \BaseObject
     */
    public function testModelObjectRequiresABaseObject()
    {
        $this->propelRepository->setRepositoryObject($this->pdo);
    }

    public function testModelObjectInjectedBySetters()
    {
        $modelObject = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $this->assertEquals($this->propelRepository, $this->propelRepository->setRepositoryObject($modelObject));
        $this->assertEquals($modelObject, $this->propelRepository->getModelObject());
        $this->assertNotSame($this->modelObject, $this->propelRepository->getModelObject());
    }

    public function testSaveOperationFails()
    {
        $values = array('Fake');

        $this->pdo->expects($this->once())
                         ->method('beginTransaction');

        $this->pdo->expects($this->never())
                         ->method('commit');

        $this->pdo->expects($this->once())
                         ->method('rollback');

        $this->setFromArrayExpectation($values);

        $this->modelObject->expects($this->once())
                          ->method('save')
                          ->will($this->returnValue(0));

        $this->modelObject->expects($this->once())
                          ->method('isModified')
                          ->will($this->returnValue(true));

        $this->assertFalse($this->propelRepository->save($values, $this->modelObject));
    }

    public function testSaveOperationChangesNothing()
    {
        $values = array('Fake');

        $this->pdo->expects($this->once())
                         ->method('beginTransaction');

        $this->pdo->expects($this->once())
                         ->method('commit');

        $this->pdo->expects($this->never())
                         ->method('rollback');

        $this->setFromArrayExpectation($values);

        $this->modelObject->expects($this->once())
                          ->method('save')
                          ->will($this->returnValue(0));

        $this->modelObject->expects($this->once())
                          ->method('isModified')
                          ->will($this->returnValue(false));

        $this->assertNull($this->propelRepository->save($values, $this->modelObject));
    }

    /**
     * @expectedException \PropelException
     */
    public function testAnUnexpectedExceptionIsThrownByTheOrm()
    {
        $values = array('Fake');

        $this->pdo->expects($this->once())
                         ->method('beginTransaction');

        $this->pdo->expects($this->never())
                         ->method('commit');

        $this->pdo->expects($this->once())
                         ->method('rollback');

        $this->setFromArrayExpectation($values);

        $this->modelObject->expects($this->once())
                          ->method('save')
                          ->will($this->throwException(new \PropelException()));

        $this->propelRepository->save($values, $this->modelObject);
    }

    public function testSaveOperationSucceded()
    {
        $values = array('Fake');

        $this->pdo->expects($this->once())
                         ->method('beginTransaction');

        $this->pdo->expects($this->once())
                         ->method('commit');

        $this->pdo->expects($this->never())
                         ->method('rollback');

        $this->setFromArrayExpectation($values);

        $this->modelObject->expects($this->once())
                          ->method('save')
                          ->will($this->returnValue(1));

        $this->assertTrue($this->propelRepository->save($values, $this->modelObject));
        $this->assertEquals(1, $this->propelRepository->getAffectedRecords());
    }

    /**
     * @expectedException \PropelException
     */
    public function testAnUnexpectedExceptionIsThrownDeletingARecord()
    {
        $this->pdo->expects($this->once())
                         ->method('beginTransaction');

        $this->pdo->expects($this->never())
                         ->method('commit');

        $this->pdo->expects($this->once())
                         ->method('rollback');

        $this->modelObject->expects($this->once())
                          ->method('save')
                          ->will($this->throwException(new \PropelException()));

        $this->assertTrue($this->propelRepository->delete($this->modelObject));
    }

    public function testDeleteARecord()
    {
        $this->pdo->expects($this->once())
                         ->method('beginTransaction');

        $this->pdo->expects($this->once())
                         ->method('commit');

        $this->pdo->expects($this->never())
                         ->method('rollback');

        $this->modelObject->expects($this->once())
                          ->method('save')
                          ->will($this->returnValue(1));

        $this->assertTrue($this->propelRepository->delete($this->modelObject));
    }

    public function testExecuteRawQuery()
    {
        $this->pdoStatement = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel\Pdo\MockPDOStatement');
        $this->pdoStatement->expects($this->once())
                  ->method('execute')
                  ->will($this->returnValue(1));

        $this->pdo->expects($this->once())
                  ->method('prepare')
                  ->will($this->returnValue($this->pdoStatement));
        $this->assertEquals(1, $this->propelRepository->executeQuery('query'));
    }

    private function setFromArrayExpectation($values)
    {
        $this->modelObject->expects($this->once())
                          ->method('fromArray')
                          ->with($values);
    }
}
