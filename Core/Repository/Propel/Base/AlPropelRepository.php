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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\Base;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\RepositoryInterface;

/**
 *  Implements the RepositoryInterface to define the base class any propel model must inherit
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlPropelRepository extends AlPropelOrm implements RepositoryInterface
{
    protected $modelObject = null;

    /**
     * {@inheritdoc}
     *
     * @param  BaseObject                                                                     $object
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\Base\AlPropelRepository
     * @throws General\InvalidParameterTypeException
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof \BaseObject) {
            throw new General\InvalidParameterTypeException('AlPropelRepository accepts only objects derived from propel \BaseObject');
        }

        $this->modelObject = $object;

        return $this;
    }

    /**
     * Returns the current model object
     *
     * @return AlPropelRepository
     */
    public function getModelObject()
    {
        return $this->modelObject;
    }
}
