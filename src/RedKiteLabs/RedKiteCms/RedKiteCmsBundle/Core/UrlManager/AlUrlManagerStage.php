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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlPage;

/**
 * Defines the object to format an url to be used when the CMS editor is active or for
 * the stage environment
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlUrlManagerStage extends AlUrlManager
{
    /**
     * {@inheritdoc}
     */
    protected function generateRoute(AlLanguage $language, AlPage $page)
    {
        return '_stage' . parent::generateRoute($language, $page);
    }
}
