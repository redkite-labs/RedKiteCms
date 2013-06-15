<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Configuration;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepository;

use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\General\InvalidArgumentException;

/**
 * Implements the AlConfigurationInterface to manage a set of configration parameters 
 * from a database
 * 
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlConfigurationManager implements AlConfigurationInterface
{
    protected $factoryRepository;
    protected $configurationRepository;
    private $cachedValues = array();


    /**
     * Constructor
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepository $factoryRepository
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
     * @return \AlphaLemon\AlphaLemonCmsBundle\Model\AlConfiguration
     * @throws \InvalidArgumentException
     */
    protected function fetchConfiguration($parameter)
    {
        $configuration = $this->configurationRepository->fetchParameter($parameter);        
        if (null === $configuration) {
            $exception = array(
                'message' => 'The configuration parameter %parameter% does not exist',
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
