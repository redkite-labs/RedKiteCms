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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\Deploy\Base;

use Symfony\Component\EventDispatcher\Event;
use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlDeployerInterface;

/**
 * Defines the base event raised when the website is deployed 
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
abstract class BaseDeployEvent extends Event
{
    protected $deployer;

    /**
     * Constructor
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlDeployerInterface $deployer
     * 
     * @api
     */
    public function __construct(AlDeployerInterface $deployer)
    {
        $this->deployer = $deployer;
    }

    /**
     * Returns the deployer object
     *
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlDeployerInterface
     * 
     * @api
     */
    public function getDeployer()
    {
        return $this->deployer;
    }
    
    /**
     * Sets the deployer
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlDeployerInterface $deployer
     * 
     * @api
     */
    public function setDeployer(AlDeployerInterface $deployer)
    {
        $this->deployer = $deployer;
    }
}
