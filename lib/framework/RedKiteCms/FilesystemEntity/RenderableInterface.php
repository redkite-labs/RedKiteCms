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
 * Interface RenderableInterface represents a class that configures a renderable filesystem entity
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\FilesystemEntity
 */
interface RenderableInterface
{
    /**
     * Renders the entiry
     *
     * @param string $sourceDir
     * @param array $options
     * @param null|string $username
     *
     * @return string
     */
    public function render($sourceDir, array $options, $username = null);
}