<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository;

/**
 * Defines the methods used to fetch configuration records
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface ConfigurationRepositoryInterface
{
    /**
     * Fetches the given parameter
     *
     * @param string       The parameter name
     * @return object The fetched object
     */
    public function fetchParameter($parameter);
}
