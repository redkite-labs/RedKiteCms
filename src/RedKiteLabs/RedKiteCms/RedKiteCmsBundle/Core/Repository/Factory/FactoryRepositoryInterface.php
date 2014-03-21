<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory;

/**
 * Defines the methods to create a repository object
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface FactoryRepositoryInterface
{
    /**
     * Creates the repository
     *
     * @param  string                                                                       $repository
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\RepositoryInterface
     */
    public function createRepository($repository);
}
