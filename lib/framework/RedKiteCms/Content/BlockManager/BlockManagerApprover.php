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
use RedKiteCms\EventSystem\Event\Block\BlockApprovedEvent;
use RedKiteCms\EventSystem\Event\Block\BlockApprovedRemovalEvent;
use RedKiteCms\EventSystem\Event\Block\BlockApprovingEvent;
use RedKiteCms\EventSystem\Event\Block\BlockApprovingRemovalEvent;
use RedKiteCms\Tools\FilesystemTools;
use RedKiteCms\Tools\JsonTools;

/**
 * Class BlockManagerApprover is the object deputed to approve a block contribution
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\BlockManager
 */
class BlockManagerApprover extends BlockManager
{
    /**
     * Approves a contribution
     *
     * @param string $sourceDir
     * @param array $options
     * @param string $username
     *
     * @return array The approved block
     */
    public function approve($sourceDir, array $options, $username)
    {
        $this->init($sourceDir, $options, $username);

        $sourceFilename = sprintf('%s/blocks/%s.json', $this->contributorDir, $options['blockname']);
        $targetFilename = sprintf('%s/blocks/%s.json', $this->productionDir, $options['blockname']);

        Dispatcher::dispatch(
            BlockEvents::BLOCK_APPROVING,
            new BlockApprovingEvent($this->serializer, $sourceFilename, $targetFilename)
        );

        $blockValues = JsonTools::jsonDecode(FilesystemTools::readFile($sourceFilename));
        $blockValues["history"] = array();
        $this->archiveBlock($options['blockname'], $this->productionDir);
        FilesystemTools::writeFile($targetFilename, json_encode($blockValues));

        $slotDefinitionContribution = $this->getSlotDefinition($this->getContributorDir());
        $this->saveSlotDefinition($this->productionDir, $slotDefinitionContribution);

        Dispatcher::dispatch(
            BlockEvents::BLOCK_APPROVED,
            new BlockApprovedEvent($this->serializer, $sourceFilename, $targetFilename)
        );

        DataLogger::log(
            sprintf(
                'Block "%s" has been approved on the "%s" slot on page "%s" for the "%s_%s" language',
                $options["blockname"],
                $options["slot"],
                $options["page"],
                $options["language"],
                $options["country"]
            )
        );

        return $blockValues;
    }

    /**
     * Approves the removal of the given contribution
     *
     * @param string $sourceDir
     * @param array $options
     * @param string $username
     */
    public function approveRemoval($sourceDir, array $options, $username)
    {
        $this->init($sourceDir, $options, $username);
        $targetFilename = sprintf('%s/blocks/%s.json', $this->productionDir, $options['blockname']);
        if (!file_exists($targetFilename)) {
            return;
        }

        Dispatcher::dispatch(
            BlockEvents::BLOCK_APPROVING_REMOVAL,
            new BlockApprovingRemovalEvent($this->serializer, $targetFilename)
        );

        $this->archiveRemovedFile($this->productionDir, $targetFilename, $options);
        $this->filesystem->remove($targetFilename);

        $slotDefinition = $this->getSlotDefinition($this->productionDir);
        $blocks = $slotDefinition["blocks"];
        $key = array_search($options['blockname'], $blocks);
        unset($blocks[$key]);
        $slotDefinition["blocks"] = $blocks;
        $this->saveSlotDefinition($this->productionDir, $slotDefinition, $username);

        Dispatcher::dispatch(
            BlockEvents::BLOCK_APPROVED_REMOVAL,
            new BlockApprovedRemovalEvent($this->serializer, $targetFilename)
        );

        DataLogger::log(
            sprintf(
                'Block "%s" has been approved for removal on the "%s" slot on page "%s" for the "%s_%s" language',
                $options["blockname"],
                $options["slot"],
                $options["page"],
                $options["language"],
                $options["country"]
            )
        );
    }
}