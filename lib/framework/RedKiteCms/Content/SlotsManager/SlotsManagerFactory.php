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

namespace RedKiteCms\Content\SlotsManager;

use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Exception\General\RuntimeException;

/**
 * Class SlotsManagerFactory is the factory object deputed to build a slot manager from repeat status
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\SlotsManager
 */
class SlotsManagerFactory implements SlotsManagerFactoryInterface
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
     *{@inheritdoc}
     */
    public function createSlotManager($repeat)
    {
        $repeatedNormalized = ucfirst($repeat);
        $slotsManager = 'RedKiteCms\Content\SlotsManager\SlotsManager' . $repeatedNormalized;
        if (!class_exists($slotsManager)) {
            throw new RuntimeException(
                sprintf("SlotsManagerFactory cannot instantiate the SlotsManager%s object", $repeatedNormalized)
            );
        }

        $dataDir = $this->configurationHandler->dataDir();
        $siteName = $this->configurationHandler->siteName();

        return new $slotsManager($dataDir, $siteName);
    }
}
