<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\FilesystemEntity;

/**
 * Class Entity is the object defines the base methods for a page or slot filesystem entity
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\FilesystemEntity
 */
abstract class Entity extends FilesystemEntity implements EntityInterface
{
    /**
     * @type array
     */
    protected $productionEntities = array();
    /**
     * @type array
     */
    protected $contributorEntities = array();
    /**
     * @type array
     */
    protected $contributes = array();

    /**
     * {@inheritdoc}
     */
    public function getProductionEntities()
    {
        return $this->productionEntities;
    }

    /**
     * {@inheritdoc}
     */
    public function getContributorEntities()
    {
        return $this->contributorEntities;
    }

    /**
     * Shortcut that returns the entities in use in the specific context, backend or frontend
     *
     * @return array|null
     */
    public function getEntitiesInUse()
    {
        switch ($this->workMode) {
            case "contributor":
                return $this->contributorEntities;

            case "production":
                return $this->productionEntities;

            default:
                return null;
        }
    }
}