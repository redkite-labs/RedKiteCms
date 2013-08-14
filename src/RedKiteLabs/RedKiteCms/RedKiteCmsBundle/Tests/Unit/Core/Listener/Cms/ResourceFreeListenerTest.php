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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\Cms;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Listener\Cms\ResourceFreeListener;

/**
 * ResourceFreeListenerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ResourceFreeListenerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->securityContext = 
            $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->resourcesLocker = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\ResourcesLocker\AlResourcesLocker')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;

        $this->event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
                            ->disableOriginalConstructor()
                            ->getMock();        
    }

    public function testResourceIsNotLockedWhenTheSecurityContextIsNotInstantiated()
    {
        $this->initSecurityContext(null);
        
        $this->resourcesLocker
             ->expects($this->never())
             ->method('lockResource')
        ;   
        
        $testListener = $this->initTestListener();
        $testListener->onKernelRequest($this->event);
    }
    
    public function testResourceIsNotLockedWhenTheUserIsNotInstantiated()
    {
        $token = $this->initToken();
        $this->initSecurityContext($token);
        
        $this->resourcesLocker
             ->expects($this->never())
             ->method('lockResource')
        ;   
        
        $testListener = $this->initTestListener();
        $testListener->onKernelRequest($this->event);
    }
    
    public function testSomethingGoesWrongUnlockingTheExpiredResources()
    {
        $user = $this->initUser();
        $token = $this->initToken($user);        
        $this->initSecurityContext($token);
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('unlockExpiredResources')
             ->will($this->throwException(new \PropelException('Unknown propel error')))
        ;
        
        $this->initEvent();
        
        $testListener = $this->initTestListener();
        $testListener->onKernelRequest($this->event);
    }
    
    public function testSomethingGoesWrongUnlockingTheResource()
    {
        $user = $this->initUser();
        $token = $this->initToken($user);        
        $this->initSecurityContext($token);
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('unlockExpiredResources')
        ;
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('unlockUserResource')
             ->will($this->throwException(new \PropelException('Unknown propel error')))
        ;
        
        $this->initEvent();
        
        $testListener = $this->initTestListener();
        $testListener->onKernelRequest($this->event);
    }
    
    public function testResourceIsNotLockedWhenLockedParameterIsNull()
    {
        $user = $this->initUser();
        $token = $this->initToken($user);        
        $this->initSecurityContext($token);
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('unlockExpiredResources')
        ;
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('unlockUserResource')
        ;
        
        $request = $this->initRequest();
        $this->event
             ->expects($this->once())
             ->method('getRequest')
             ->will($this->returnValue($request))
        ;  
        
        $this->initEvent(0);
        
        $this->resourcesLocker
             ->expects($this->never())
             ->method('lockResource')
        ; 
        
        $testListener = $this->initTestListener();
        $testListener->onKernelRequest($this->event);
    }
    
    /**
     * @dataProvider exceptionsProvider 
     */
    public function testLockResourcesException($exception)
    {
        $user = $this->initUser();
        $token = $this->initToken($user);        
        $this->initSecurityContext($token);
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('unlockExpiredResources')
        ;
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('unlockUserResource')
        ;
        
        $request = $this->initRequest('idBlock');
        $this->event
             ->expects($this->once())
             ->method('getRequest')
             ->will($this->returnValue($request))
        ;  
        
        $this->initEvent(1);
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('lockResource')
             ->will($this->throwException($exception))
        ; 
        
        $testListener = $this->initTestListener();
        $testListener->onKernelRequest($this->event);
    }
    
    /**
     * @dataProvider lockedValueProvider
     */
    public function testResourceIsLocked($lockedParam, $lockedValue, $getUriTimes)
    {
        $user = $this->initUser();
        $token = $this->initToken($user);        
        $this->initSecurityContext($token);
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('unlockExpiredResources')
        ;
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('unlockUserResource')
        ;
        
        $request = $this->initRequest($lockedParam, $lockedValue);
        $request
            ->expects($this->exactly($getUriTimes))
            ->method('getUri')
        ;
        
        $this->event
             ->expects($this->once())
             ->method('getRequest')
             ->will($this->returnValue($request))
        ;  
        
        $this->initEvent(0);
        
        $this->resourcesLocker
             ->expects($this->once())
             ->method('lockResource')
        ; 
        
        $testListener = $this->initTestListener();
        $testListener->onKernelRequest($this->event);
    }
    
    public function exceptionsProvider()
    {
        return array(
            array(new \PropelException('Unknown propel error')),
            array(new \RedKiteLabs\RedKiteCmsBundle\Core\ResourcesLocker\Exception\ResourceNotFreeException('The resource you requested is locked by another user. Please retry in a couple of minutes')),
            array(new \RuntimeException('Unespected error')),
        );
    }
    
    public function lockedValueProvider()
    {
        return array(
            array('idBlock', 12, 0),
            array('blocks,idBlock', 13, 0),
            array('locked', null, 1),
        );
    }
    
    private function initSecurityContext($token)
    {
        $this->securityContext
             ->expects($this->once())
             ->method('getToken')
             ->will($this->returnValue($token))
        ;
    }
    
    private function initToken($user = null)
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user))
        ;
        
        return $token;
    }
    
    private function initUser()
    {
        $user = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlUser');
        $user
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1))
        ;
        
        return $user;
    }
    
    private function initTestListener()
    {
        return new ResourceFreeListener($this->securityContext, $this->resourcesLocker);
    }
    
    private function initEvent($times = 1)
    {
        $this->event
             ->expects($this->exactly($times))
             ->method('setResponse')
        ;  
        
        $this->event
             ->expects($this->exactly($times))
             ->method('stopPropagation')
        ;  
    }
    
    private function initRequest($lockedParam = null, $lockedValue = null)
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request
            ->expects($this->at(0))
            ->method('get')
            ->with('locked')
            ->will($this->returnValue($lockedParam))
        ;
        
        if (null !== $lockedValue) {
            
            $rules = explode(',', $lockedParam);
            if (isset($rules[1])) {
                $lockedParam = $rules[1];
            }
            
            $request
                ->expects($this->at(1))
                ->method('get')
                ->with($lockedParam)
                ->will($this->returnValue($lockedValue))
            ;
        }
        
        return $request;
    }
}