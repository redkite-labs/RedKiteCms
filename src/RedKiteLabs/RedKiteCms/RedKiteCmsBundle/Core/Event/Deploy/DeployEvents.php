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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\Deploy;

/**
 * Defines the names for the deploy events
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
final class DeployEvents
{
    // alcms.event_listener
    const BEFORE_LOCAL_DEPLOY = 'deploy.before_local_deploy';
    const AFTER_LOCAL_DEPLOY = 'deploy.after_local_deploy';
}
