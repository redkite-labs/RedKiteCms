<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Menu;

use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Menu\BlockManagerMenu;

/**
 * BlockManagerMenu handles a vertical menu block
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagerMenuVertical extends BlockManagerMenu
{
    protected $blocksTemplate = 'RedKiteCmsBaseBlocksBundle:Content:Menu/menu_vertical.html.twig';
}
