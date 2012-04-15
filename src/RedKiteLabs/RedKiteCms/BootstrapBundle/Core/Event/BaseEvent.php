<?php
/*
 * This file is part of the AlphaLemonBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 */

namespace AlphaLemon\BootstrapBundle\Core\Event;

use Symfony\Component\EventDispatcher\Event;
use AlphaLemon\BootstrapBundle\Core\Json\JsonAutoloader;

/**
 * Defines the BaseEvent event dispatched by the Bootstrap bundle
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
abstract class BaseEvent extends Event
{
    private $autoloader;
    
    /**
     * Contructor
     * 
     * @param JsonAutoloader $autoloader 
     */
    public function __construct(JsonAutoloader $autoloader)
    {
        $this->autoloader = $autoloader;
    }
    
    public function getAutoloader()
    {
        return $this->autoloader;
    }
}
