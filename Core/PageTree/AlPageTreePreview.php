<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\PageTree;

use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface;

/**
 * Extends the AlphaLemonCms AlPageTree object to display the page in preview mode
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
     * @param  type                                                                       $slotName
     * @param  \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface $blockManager
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTreePreview
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
     * @return array
     *
     * @api
     */
    public function getBlockManagers($slotName)
    {
        return $this->blockManagers[$slotName];
    }
}
