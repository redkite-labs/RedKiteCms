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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\Base;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\OrmInterface;

/**
 *  Adds some filters to the AlBlockQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlPropel implements OrmInterface
{
    protected $dispatcher = null;
    protected $modelObject = null;protected $connection;
    
    abstract public function setModelObject(\BaseObject $object);
    
    /**
     * Constructor
     * 
     * @param EventDispatcherInterface $v 
     */
    public function _constructor(EventDispatcherInterface $v)
    {
        $this->dispatcher = $v;
    }
    
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    public function startTransaction()
    {
        $this->connection->begintTransaction();
    }
    
    public function commit()
    {
        $this->connection->commit();
    }
    
    public function rollback()
    {
        $this->connection->rollback();
    }
    
    public function getModelObject()
    {
        return $this->modelObject;
    }
    
    /**
     * Sets the dispatcher
     * 
     * @param EventDispatcherInterface $v
     * @return AlBlockQuery 
     */
    public function setDispatcher(EventDispatcherInterface $v)
    {
        $this->dispatcher = $v;
        
        return $this;
    }
    
    public function save(array $values, $modelObject = null)
    {
        if(null !== $modelObject) $this->modelObject = $modelObject;
        if(!$this->isModelObjectSetted()) return false;
        
        $this->connection->beginTransaction();
        
        $this->modelObject->fromArray($values);
        $result = $this->modelObject->save();
        $rollBack = ($this->modelObject->isModified() && $result == 0) ? true : false;
        $success = !$rollBack;
        
        if ($success) {
            $this->connection->commit();
        }
        else {
            $this->connection->rollback();
        }
        
        return $success;
    }
    
    public function delete($modelObject = null)
    {
        if(null !== $modelObject) $this->modelObject = $modelObject;
        
    }
    
    private function isModelObjectSetted($object)
    {
        return (null === $this->modelObject && null === $object) ? false : true;            
    }
} 
