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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\EventsHandler;

use RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandler;

/**
 * Implements the AlEventsHandler to hanled events for Content objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlContentEventsHandler extends AlEventsHandler
{
    /**
     * {@inheritdoc}
     */
    protected function configureMethods()
    {
        return array(
            "setContentManager",
            "setValues",
        );
    }
}
