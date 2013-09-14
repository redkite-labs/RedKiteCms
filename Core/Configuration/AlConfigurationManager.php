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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Configuration;

use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepository;

use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;

/**
 * Implements the AlConfigurationInterface to manage a set of configration parameters 
 * from a database
 * 
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlConfigurationManager implements AlConfigurationInterface
{
    protected $factoryRepository;
    protected $configurationRepository;
    private $cachedValues = array();


    /**
     * Constructor
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepository $factoryRepository
     */
    public function __construct(AlFactoryRepository $factoryRepository)
    {
        $this->factoryRepository = $factoryRepository;
        $this->configurationRepository = $this->factoryRepository->createRepository('Configuration');
        
    }   
    
    /**
     * {@inheritdoc}
     */
    public function read($parameter)
    {
        if (array_key_exists($parameter, $this->cachedValues)) {
            return $this->cachedValues[$parameter];
        }
        
        $value = $this->fetchConfiguration($parameter)->getValue();
        $this->cachedValues[$parameter] = $value;
        
        return $value;
    } 
    
    /**
     * {@inheritdoc}
     */
    public function write($parameter, $value)
    {
        unset($this->cachedValues[$parameter]);
        
        $configuration = $this->fetchConfiguration($parameter); 
        $configuration->setValue($value);
        
        return $configuration->save();
    }
    
    /**
     * Fetches the configuration record for the requested parameter
     * 
     * @param string $parameter
     * @return \RedKiteLabs\RedKiteCmsBundle\Model\AlConfiguration
     * @throws \InvalidArgumentException
     */
    protected function fetchConfiguration($parameter)
    {
        $configuration = $this->configurationRepository->fetchParameter($parameter);        
        if (null === $configuration) {
            $exception = array(
                'message' => 'exception_parameter_does_not_exist',
                'parameters' => array(
                    '%parameter%' => $parameter,
                ),
                'domain' => 'exceptions',
            );
            throw new InvalidArgumentException(json_encode($exception));
        }
        
        return $configuration;
    }
}
