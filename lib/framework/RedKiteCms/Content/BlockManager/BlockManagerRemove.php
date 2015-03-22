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
use RedKiteCms\EventSystem\Event\Block\BlockRemovedEvent;
use RedKiteCms\EventSystem\Event\Block\BlockRemovingEvent;
use RedKiteCms\Tools\FilesystemTools;
use RedKiteCms\Tools\JsonTools;

/**
 * Class BlockManagerRemove is the object deputed to remove a block froma slot
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\BlockManager
 */
class BlockManagerRemove extends BlockManager
{
    /**
     * Removes the block from the given slot
     *
     * @param $sourceDir
     * @param array $options
     * @param $username
     */
    public function remove($sourceDir, array $options, $username)
    {
        $dir = $this
            ->init($sourceDir, $options, $username)
            ->getDirInUse();

        $blockName = $options["blockname"];
        $blocksDir = $dir . '/blocks';
        $filename = sprintf('%s/%s.json', $blocksDir, $blockName);
        $options["block"] = JsonTools::jsonDecode(FilesystemTools::readFile($filename));
        Dispatcher::dispatch(BlockEvents::BLOCK_REMOVING, new BlockRemovingEvent($this->serializer, $filename));

        $this->archiveRemovedFile($dir, $filename, $options);
        $this->filesystem->remove($filename);
        $this->removeBlockFromSlotFile($options, $dir);

        Dispatcher::dispatch(BlockEvents::BLOCK_REMOVED, new BlockRemovedEvent($this->serializer, $filename));
        DataLogger::log(
            sprintf(
                'Block "%s" has been removed from the "%s" slot on page "%s" for the "%s_%s" language',
                $options["blockname"],
                $options["slot"],
                $options["page"],
                $options["language"],
                $options["country"]
            )
        );
    }
}