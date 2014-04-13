<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Deploy\Base;

use Symfony\Component\EventDispatcher\Event;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\DeployerInterface;

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
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\DeployerInterface $deployer
     *
     * @api
     */
    public function __construct(DeployerInterface $deployer)
    {
        $this->deployer = $deployer;
    }

    /**
     * Returns the deployer object
     *
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\DeployerInterface
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
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\DeployerInterface $deployer
     *
     * @api
     */
    public function setDeployer(DeployerInterface $deployer)
    {
        $this->deployer = $deployer;
    }
}
