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
use RedKiteCms\EventSystem\Event\Block\BlockRestoredEvent;
use RedKiteCms\EventSystem\Event\Block\BlockRestoringEvent;

/**
 *
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagerRestore extends BlockManager
{
    public function restore($sourceDir, array $options, $username, $archiveFile)
    {
        $this->createContributorDir($sourceDir, $options, $username);
        $this->archiveBlock($options["blockname"]);
        $archiveDir = $this->getArchiveDir() . '/' . $options["blockname"];

        $filename = $this->contributorDir . '/blocks/' . $options["blockname"] . '.json';
        $activeBlockArchiveFilename = sprintf('%s/%s.json', $archiveDir, date("Y-m-d-H.i.s"));
        $archiveFilename = sprintf('%s/%s.json', $archiveDir, $archiveFile);

        Dispatcher::dispatch(
            BlockEvents::BLOCK_RESTORING,
            new BlockRestoringEvent($this->serializer, $filename, $archiveFilename)
        );

        $this->filesystem->copy($filename, $activeBlockArchiveFilename, true);
        $this->filesystem->copy($archiveFilename, $filename, true);
        $this->filesystem->remove($archiveFilename);

        Dispatcher::dispatch(
            BlockEvents::BLOCK_RESTORED,
            new BlockRestoredEvent($this->serializer, $filename, $archiveFilename)
        );
        DataLogger::log(
            sprintf(
                'Block "%s" has been restored as "%s" on the slot "%s" on "%s" page for "%s_%s" language',
                $archiveFilename,
                $options["blockname"],
                $options["slot"],
                $options["page"],
                $options["language"],
                $options["country"]
            )
        );
    }
}