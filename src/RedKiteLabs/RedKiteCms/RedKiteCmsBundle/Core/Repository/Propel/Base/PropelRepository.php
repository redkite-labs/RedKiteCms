<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\RepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the RepositoryInterface to define the base class any propel model must inherit
 *
 *  @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class PropelRepository extends PropelOrm implements RepositoryInterface
{
    protected $modelObject = null;

    /**
     * {@inheritdoc}
     *
     * @param  BaseObject                                                                   $object
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\Base\PropelRepository
     * @throws General\InvalidArgumentTypeException
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof \BaseObject) {
            throw new InvalidArgumentTypeException ('PropelRepository accepts only objects derived from propel \BaseObject');
        }

        $this->modelObject = $object;

        return $this;
    }

    /**
     * Returns the current model object
     *
     * @return PropelRepository
     */
    public function getModelObject()
    {
        return $this->modelObject;
    }
}
