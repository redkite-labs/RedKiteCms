<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory;

/**
 * AlFactoryRepository object instantiates repository objects according with the orm
 * and the repository type
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlFactoryRepository implements AlFactoryRepositoryInterface
{
    private $orm = null;

    /**
     * Constructor
     *
     * @param string $orm
     */
    public function __construct($orm)
    {
        $this->orm = ucfirst($orm);
    }

    /**
     * {@inheritdoc}
     */
    public function createRepository($repository)
    {
        $repository = ucfirst($repository);
        $class = sprintf('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\%s\Al%sRepository%s', $this->orm, $repository, $this->orm);
        if(!class_exists($class)) {
            echo $class;exit;
        }

        return new $class();
    }
}
