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
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\ModelInterface;

/**
 *  Adds some filters to the AlBlockQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlPropelModel extends AlPropelOrm implements ModelInterface
{
    protected $dispatcher = null;
    protected $modelObject = null;
    
    /**
     * Constructor
     * 
     * @param EventDispatcherInterface $v 
     */
    public function __construct(EventDispatcherInterface $v, \PropelPDO $connection = null)
    {
        parent::__construct($connection);
        
        $this->dispatcher = $v;
    }
    
    public function setModelObject($object = null)
    {
        if (null !== $object && !$object instanceof \BaseObject) {
            throw new General\InvalidParameterTypeException('AlPropelModel accepts only objects derived from propel \BaseObject');
        }
        
        $this->modelObject = $object;
        
        return $this;
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
    
    private function isModelObjectSetted($object)
    {
        return (null === $this->modelObject && null === $object) ? false : true;            
    }
} 
