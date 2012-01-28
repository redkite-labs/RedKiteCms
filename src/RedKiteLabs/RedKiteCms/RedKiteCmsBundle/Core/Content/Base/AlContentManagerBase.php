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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

/**
 * The base class that defines a content manager object
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
abstract class AlContentManagerBase
{
    protected $container;
    protected $connection;

    /**
     * Container 
     * 
     * @param ContainerInterface $container 
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->connection =  \Propel::getConnection();
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    public function setConnection(Propel $v)
    {
        $this->connection = $v;
    }

    protected function checkEmptyParams(array $values)
    {
        if(empty($values))
        {
            throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, 'save() method requires at least one valid parameter, any one has been given'));
        }
    }
    
    protected function checkOnceValidParamExists(array $requiredParams, array $values)
    {
        $diff = array_diff_key($requiredParams, $values); 
        if(count($diff) != count($values))
        {
            throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, 'save() method requires the following parameters: %required%. You must give %diff% which is/are missing', array('%required%' => $this->doImplode($requiredParams), '%diff%' => $this->doImplode($diff))));
        }
    }
    
    protected function checkRequiredParamsExists(array $requiredParams, array $values)
    {
        $diff = array_diff_key($requiredParams, $values);
        if(count($diff) == count($requiredParams) || count($diff) > 0)
        {
            throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, 'save() method requires the following parameters: %required%. You must give %diff% which is/are missing', array('%required%' => $this->doImplode($requiredParams), '%diff%' => $this->doImplode($diff))));
        }
    }
    
    private function doImplode(array $params)
    {
        return implode(',', array_keys($params));
    }
}