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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy;

use RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\PageTreeCollection;

/**
 * Defines the mehods to deploy a website
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
interface DeployerInterface
{
    /**
     * Deploys all the website's pages
     *
     * @api
     */
    public function deploy(PageTreeCollection $pageTreeCollection, Theme $theme, array $options);
}
