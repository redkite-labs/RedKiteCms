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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Repository\Propel;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepository;


/**
 * AlFactoryRepositoryTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPropelRepositoryTest extends TestCase
{
    private $propelRepository;
    private $pdo;

    protected function setUp()
    {
        parent::setUp();

        $this->pdo = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Repository\Propel\Pdo\MockPDO');
        $this->propelRepository = new TestRepositoryPropel($this->pdo);
        $this->modelObject = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
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
    }
    
    private function setFromArrayExpectation($values)
    {
        $this->modelObject->expects($this->once())
                          ->method('fromArray')
                          ->with($values);
    }
}