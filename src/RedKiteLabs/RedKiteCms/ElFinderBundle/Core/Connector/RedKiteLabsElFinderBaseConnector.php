<?php
/*
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteLabs <webmaster@RedKiteLabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://RedKiteLabs.com
 * 
 * @license    MIT License
 */

namespace RedKiteLabs\ElFinderBundle\Core\Connector;

use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerInterface;

// elfinder configuration
error_reporting(0);

include_once dirname(__FILE__).'/../../Resources/public/vendor/ElFinder/php/elFinderConnector.class.php';
include_once dirname(__FILE__).'/../../Resources/public/vendor/ElFinder/php/elFinder.class.php';
include_once dirname(__FILE__).'/../../Resources/public/vendor/ElFinder/php/elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).'/../../Resources/public/vendor/ElFinder/php/elFinderVolumeLocalFileSystem.class.php';

/**
 * Instantiates the elFinder connector
 *
 * @author RedKiteLabs
 */
abstract class RedKiteLabsElFinderBaseConnector
{
    protected $options = array();
    protected $container = array();

    /**
     * Returns an array of options to configure the elFinder library
     * 
     * @return array 
     */
    abstract protected function configure();

    /**
     * The constructor
     * 
     * @param ContainerInterface $container 
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->options = $this->configure();
        
        if(null === $this->options)
        {
            throw new InvalidConfigurationException(sprintf("The configure method cannot return a null value. Check the value returned by the configure method in the %className% object", \get_class($this)));
        }

        if(!is_array($this->options))
        {
            throw new InvalidConfigurationException(sprintf("The configure method must return an array. Check the value returned by the configure method in the %className% object", \get_class($this)));
        }
    }

    /**
     * Starts the elFinder
     */
    public function connect()
    {   
        $connector = new \elFinderConnector(new \elFinder($this->options));
        $connector->run();
    }
}
