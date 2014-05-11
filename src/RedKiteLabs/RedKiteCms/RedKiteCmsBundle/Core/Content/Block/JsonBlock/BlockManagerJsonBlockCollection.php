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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock;

/**
 * BlockManagerJsonBlockCollection is the base object deputed to handle a json content
 * which defines a collection of objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class BlockManagerJsonBlockCollection extends BlockManagerJsonBlockCollectionBase
{
    /**
     * {@inheritdoc}
     *
     * Extends the base edit method to manage a json collection of objects
     *
     * @api
     */
    protected function edit(array $values)
    {
        $values = $this->manageCollection($values);

        if (false === $values) {
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }

        return parent::edit($values);
    }
}
