<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\EventSystem\Event\Cms;

use RedKiteCms\Configuration\ConfigurationHandler;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CmsBootEvent is the object deputed to handle the base properties to generate a new cms boot event
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event\Cms
 */
abstract class CmsBootEventBase extends Event
{
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    private $configurationHandler;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configurationHandler = $configurationHandler;
    }

    /**
     * returns the handled ConfigurationHandler
     *
     * @return \RedKiteCms\Configuration\ConfigurationHandler
     */
    public function getConfigurationHandler()
    {
        return $this->configurationHandler;
    }
}