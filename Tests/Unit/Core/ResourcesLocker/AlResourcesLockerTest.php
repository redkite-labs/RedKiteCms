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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\PageTree;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\ResourcesLocker\AlResourcesLocker;

/**
 * AlResourcesLockerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlResourcesLockerTest extends TestCase
{
    private $resourcesLocker;
    private $lockedResourceRepository;
    private $userId = 1;
    private $resourceName = 'aa00aa00aa00aa00aa00aa00aa00aa00';
    
    protected function setUp()
    {
        parent::setUp();

        $this->lockedResourceRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLockedResourceRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $factoryRepository
             ->expects($this->once())
             ->method('createRepository')
             ->with('LockedResource')
             ->will($this->returnValue($this->lockedResourceRepository))
        ;
        
        $this->resourcesLocker = new AlResourcesLocker($factoryRepository);
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\ResourcesLocker\Exception\ResourceNotFreeException
     * @expectedExceptionMessage The resource you requested is locked by another user. Please retry in a couple of minutes
     */
    public function testAnExceptionIsThrownWhenTheRequestedResourceIsNotFree()
    {        
        $this->lockedResourceByUser(null);
        $this->lockedResource($this->initResource());
        
        $this->resourcesLocker->lockResource($this->userId, $this->resourceName);
    }
    
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The resource has not been locked due to an error occoured during the saving operation
     */
    public function testSomethingGoesWrongWhenSavingToDatabase()
    {
        $this->lockedResourceByUser(null);
        $this->lockedResource(null);
        $this->getRepositoryObject();
        
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('setRepositoryObject')
             ->will($this->returnSelf())
        ;
        
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('save')
             ->will($this->returnValue(false))
        ;
        
        $this->resourcesLocker->lockResource($this->userId, $this->resourceName);
    }
    
    /**
     * @dataProvider lockedResultProvider
     */
    public function testAFreeResourceHasBeenLocked($expectedResult)
    {
        $this->lockedResourceByUser(null);
        $this->lockedResource(null);
        $this->getRepositoryObject();
        
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('setRepositoryObject')
             ->will($this->returnSelf())
        ;
        
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('save')
             ->will($this->returnValue($expectedResult))
        ;
        
        $this->resourcesLocker->lockResource($this->userId, $this->resourceName);
    }
    
    /**
     * @dataProvider lockedResultProvider
     */
    public function testTheResourceTimeIsUpdatedWhenExistsForTheLockerUser()
    {
        $this->lockedResourceByUser($this->initResource());
        
        $this->lockedResourceRepository
             ->expects($this->never())
             ->method('fromResourceName')
        ;
        
        $this->lockedResourceRepository
             ->expects($this->never())
             ->method('getRepositoryObjectClassName')
        ;
        
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('setRepositoryObject')
             ->will($this->returnSelf())
        ;
        
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('save')
             ->will($this->returnValue(true))
        ;
        
        $this->resourcesLocker->lockResource($this->userId, $this->resourceName);
    }
    
    /**
     * @expectedException \PropelException
     */
    public function testUnlockUserFailsWhenAnUnexpectedExceptionHadThrown()
    {
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('freeUserResource')
             ->will($this->throwException(new \PropelException))
        ;
        
        $this->resourcesLocker->unlockUserResource($this->userId);
    }
    
    /**
     * @dataProvider unlockedResultProvider
     */
    public function testUnlockUser($result)
    {
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('freeUserResource')
             ->will($this->returnValue($result))
        ;
        
        $this->resourcesLocker->unlockUserResource($this->userId);
    }
    
    /**
     * @expectedException \PropelException
     */
    public function testUnlockResourceFailsWhenAnUnexpectedExceptionHadThrown()
    {
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('freeLockedResource')
             ->will($this->throwException(new \PropelException))
        ;
        
        $this->resourcesLocker->unlockResource($this->resourceName);
    }
    
    /**
     * @dataProvider unlockedResultProvider
     */
    public function testUnlockResource($result)
    {
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('freeLockedResource')
             ->will($this->returnValue($result))
        ;
        
        $this->resourcesLocker->unlockResource($this->resourceName);
    }
    
    /**
     * @expectedException \PropelException
     */
    public function testUnlockExpiredResourceFailsWhenAnUnexpectedExceptionHadThrown()
    {
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('removeExpiredResources')
             ->will($this->throwException(new \PropelException))
        ;
        
        $this->resourcesLocker->unlockExpiredResources($this->resourceName);
    }
    
    /**
     * @dataProvider unlockedResultProvider
     */
    public function testUnlockExpiredResource($result)
    {
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('removeExpiredResources')
             ->will($this->returnValue($result))
        ;
        
        $this->resourcesLocker->unlockExpiredResources($this->resourceName);
    }
    
    public function lockedResultProvider()
    {
        return array(
            array(true),
            array(null),
        );
    }
    
    public function unlockedResultProvider()
    {
        return array(
            array(0),
            array(1),
        );
    }
    
    private function initResource()
    {
        return $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Model\AlLockedResource');
    }
    
    private function lockedResourceByUser($returnValue)
    {
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('fromResourceNameByUser')
             ->with($this->userId, $this->resourceName)
             ->will($this->returnValue($returnValue))
        ;
    }
    
    private function lockedResource($returnValue)
    {
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('fromResourceName')
             ->with($this->resourceName)
             ->will($this->returnValue($returnValue))
        ;
    }
    
    private function getRepositoryObject()
    {
        $this->lockedResourceRepository
             ->expects($this->once())
             ->method('getRepositoryObjectClassName')
             ->will($this->returnValue('\RedKiteLabs\RedKiteCmsBundle\Model\AlLockedResource'))
        ;
    }
}