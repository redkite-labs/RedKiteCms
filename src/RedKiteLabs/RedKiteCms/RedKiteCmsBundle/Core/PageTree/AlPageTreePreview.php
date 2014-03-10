<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface;

/**
 * Extends RedKiteCms AlPageTree object to display the page in preview mode
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlPageTreePreview extends AlPageTree
{
    protected $blockManagers = array();

    /**
     * Adds a block manager
     *
     * @param  string                  $slotName
     * @param  AlBlockManagerInterface $blockManager
     * @return AlPageTreePreview
     *
     * @api
     */
    public function addBlockManager($slotName, AlBlockManagerInterface $blockManager)
    {
        $this->blockManagers[$slotName][] = $blockManager;

        return $this;
    }

    /**
     * Overrides the base method to return the Block Managers.
     *
     * @param  string                    $slotName
     * @return AlBlockManagerInterface[]
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
