<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlConfiguration;

/**
 * Defines the methods used to fetch configuration records
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface ConfigurationRepositoryInterface
{
    /**
     * Fetches the given parameter
     *
     * @param  string          $parameter The parameter name
     * @return AlConfiguration The configuration instance
     */
    public function fetchParameter($parameter);
}
