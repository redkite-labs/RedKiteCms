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

namespace RedKiteCms\Content\BlockManager;

use RedKiteCms\Bridge\Dispatcher\Dispatcher;
use RedKiteCms\Bridge\Monolog\DataLogger;
use RedKiteCms\EventSystem\BlockEvents;
use RedKiteCms\EventSystem\Event\Block\BlockEditedEvent;
use RedKiteCms\EventSystem\Event\Block\BlockEditingEvent;
use RedKiteCms\Tools\FilesystemTools;
use RedKiteCms\Tools\JsonTools;
use RedKiteCms\Tools\Utils;

/**
 * Class BlockManagerArchive is the object deputed to archive a block after it is changed
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\BlockManager
 */
class BlockManagerArchive extends BlockManager
{
    /**
     * Archives the given block
     *
     * @param string $sourceDir
     * @param array $options
     * @param string $username
     * @param array $values
     */
    public function archive($sourceDir, array $options, $username, $values)
    {
        $block = json_decode($values, true);
        $block["history"] = array();
        $this->resolveOptions($options);
        $this->init($sourceDir, $options, $username);
        $dirname = sprintf('%s/archive/%s', $this->getDirInUse(), $options["blockname"]);
        $filename = sprintf('%s/%s.json', $dirname, $block["history_name"]);

        if (!is_dir($dirname)) {
            mkdir($dirname);
        }
        FilesystemTools::writeFile($filename, json_encode($block));
    }
}