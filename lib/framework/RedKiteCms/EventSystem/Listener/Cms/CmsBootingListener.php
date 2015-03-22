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

namespace RedKiteCms\EventSystem\Listener\Cms;


use RedKiteCms\EventSystem\Event\Cms\CmsBootingEvent;
use RedKiteCms\Plugin\PluginManager;

/**
 * Class CmsBootingListener listens to CmsBootingEvent to install plugin assets
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Cms
 */
class CmsBootingListener
{
    /**
     * @type \RedKiteCms\Plugin\PluginManager
     */
    private $pluginManager;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Plugin\PluginManager $pluginManager
     */
    public function __construct(PluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
    }

    /**
     * Install plugins assets
     *
     * @param \RedKiteCms\EventSystem\Event\Cms\CmsBootingEvent $event
     */
    public function onCmsBooting(CmsBootingEvent $event)
    {
        $this->pluginManager->installAssets();
    }
}