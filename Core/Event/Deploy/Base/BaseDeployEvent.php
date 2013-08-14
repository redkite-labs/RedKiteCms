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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Event\Deploy\Base;

use Symfony\Component\EventDispatcher\Event;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlDeployerInterface;

/**
 * Defines the base event raised when the website is deployed
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class BaseDeployEvent extends Event
{
    protected $deployer;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlDeployerInterface $deployer
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
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlDeployerInterface
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
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlDeployerInterface $deployer
     *
     * @api
     */
    public function setDeployer(AlDeployerInterface $deployer)
    {
        $this->deployer = $deployer;
    }
}
