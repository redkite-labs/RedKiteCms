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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerInterface;

/**
 * Extends RedKiteCms PageTree object to display the page in preview mode
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class PageTreePreview extends PageTree
{
    protected $blockManagers = array();

    /**
     * Adds a block manager
     *
     * @param  string                  $slotName
     * @param  BlockManagerInterface $blockManager
     * @return PageTreePreview
     *
     * @api
     */
    public function addBlockManager($slotName, BlockManagerInterface $blockManager)
    {
        $this->blockManagers[$slotName][] = $blockManager;

        return $this;
    }

    /**
     * Overrides the base method to return the Block Managers.
     *
     * @param  string                    $slotName
     * @return BlockManagerInterface[]
     *
     * @api
     */
    public function getBlockManagers($slotName)
    {
        if ( ! array_key_exists($slotName, $this->blockManagers)) {
            return array();
        }

        return $this->blockManagers[$slotName];
    }
}
