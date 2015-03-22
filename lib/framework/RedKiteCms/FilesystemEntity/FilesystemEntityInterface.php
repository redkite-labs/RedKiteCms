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
 * Interface FilesystemEntityInterface represents a class that configures a generic filesystem entity
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\FilesystemEntity
 */
interface FilesystemEntityInterface
{
    /**
     * Initializes the filesystem entity
     *
     * @param string $sourceDir
     * @param array $options
     * @param string $username
     *
     * @return \RedKiteCms\FilesystemEntity\FilesystemEntity
     */
    public function init($sourceDir, array $options, $username = null);

    /**
     * Returns the production directory
     *
     * @return string
     */
    public function getProductionDir();

    /**
     * Returns the contributor directory
     *
     * @return string
     */
    public function getContributorDir();

    /**
     * Returns the archive directory. When $targetDir argument is null, returns the directory is use in the specific context,
     * backend or frontend
     *
     * @param null|string $targetDir
     *
     * @return string
     */
    public function getArchiveDir($targetDir = null);

    /**
     * Returns the directory in use in the specific context, backend or frontend
     *
     * @return string
     */
    public function getDirInUse();
}