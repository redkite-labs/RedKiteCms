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

namespace RedKiteCms\Content\Block;

/**
 * Represents a class to define a factory able to generate a block
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Block
 */
interface BlockFactoryInterface
{
    /**
     * Creates a new block
     *
     * @param  string $type
     * @return BaseBlock
     */
    public function createBlock($type);

    /**
     * Creates all handled blocks
     *
     * @return array
     */
    public function createAllBlocks();
} 