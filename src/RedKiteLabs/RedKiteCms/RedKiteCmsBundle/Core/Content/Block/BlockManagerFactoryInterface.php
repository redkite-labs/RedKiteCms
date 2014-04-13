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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block;

/**
 * BlockManagerFactory creates a BlockManager object
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
interface BlockManagerFactoryInterface
{
    /**
     * Creates an instance of an BlockManager object
     *
     * @param mixed string | \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block $block
     *
     * @return null|BlockManagerInterface
     *
     * @api
     */
    public function createBlockManager($block);
}
