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
class AlPropelOrm implements OrmInterface
{
    protected static $connection = null;
    
    public function __construct(\PropelPDO $connection = null)
    {
        self::$connection = (null === $connection) ? \Propel::getConnection() : $connection;
    }

    public function setConnection($connection)
    {
        self::$connection = $connection;
    }
    
    public function getConnection()
    {
        return self::$connection;
    }
    
    public function startTransaction()
    {
        self::$connection->beginTransaction();
    }
    
    public function commit()
    {
        self::$connection->commit();
    }
    
    public function rollBack()
    {
        self::$connection->rollBack();
    }
    
    public function save(array $values, $modelObject = null)
    {
        try {
            if(null !== $modelObject) $this->modelObject = $modelObject;
            
            $this->startTransaction();        
            $this->modelObject->fromArray($values);
            $result = $this->modelObject->save();
            $success = ($this->modelObject->isModified() && $result == 0) ? false : true;
            
            if ($success) {
                $this->commit();
            }
            else {
                $this->rollBack();
            }

            return $success;
        }
        catch(\Exception $ex) {
            $this->rollBack();
            
            throw $ex;
        }
    }
    
    public function delete($modelObject = null)
    {
        try {
            $values = array('ToDelete' => 1); 
            
            return $this->save($values, $modelObject);
        }
        catch(\Exception $ex) { echo $ex->getMessage();
            
            throw $ex;
        }
    }
} 
