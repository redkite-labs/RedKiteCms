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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Language;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page;

/**
 * Defines the object to format an url to be used when the CMS editor is active or for
 * the stage environment
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class UrlManagerStage extends UrlManager
{
    /**
     * {@inheritdoc}
     */
    protected function generateRoute(Language $language, Page $page)
    {
        return '_stage' . parent::generateRoute($language, $page);
    }
}
