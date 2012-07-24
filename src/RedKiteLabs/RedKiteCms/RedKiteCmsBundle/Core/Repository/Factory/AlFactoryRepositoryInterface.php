<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory;

/**
 * Defines the methods to create a repository object
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlFactoryRepositoryInterface
{
    /**
     * Creates the repository
     *
     * @param string $repository
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\RepositoryInterface
     */
    public function createRepository($repository);
}