<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\EventsHandler;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\EventsHandler;

/**
 * Implements the EventsHandler to hanled events for Content objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class ContentEventsHandler extends EventsHandler
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
