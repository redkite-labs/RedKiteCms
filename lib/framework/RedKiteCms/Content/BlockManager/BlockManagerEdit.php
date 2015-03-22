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
 * Class BlockManagerEdit is the object deputed to edit a block
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\BlockManager
 */
class BlockManagerEdit extends BlockManager
{
    /**
     * Edits the given block
     *
     * @param string $sourceDir
     * @param array $options
     * @param string $username
     * @param array $values
     */
    public function edit($sourceDir, array $options, $username, $values)
    {
        $this->resolveOptions($options);
        $this->init($sourceDir, $options, $username);
        $this->createContributorDir($sourceDir, $options, $username);
        $this->archiveBlock($options["blockname"]);

        $filename = sprintf('%s/blocks/%s.json', $this->getDirInUse(), $options["blockname"]);
        $currentBlock = $options["baseBlock"] = JsonTools::jsonDecode(FilesystemTools::readFile($filename));

        $values = $this->parseChildren($values);

        $block = JsonTools::join($currentBlock, $values);
        // Block's history is not saved because it is injected when the block is rendered
        $block["history"] = array();
        $block["history_name"] = "";
        $encodedBlock = $this->serializer->serialize($block, 'json');

        $blockClass = Utils::blockClassFromType($block["type"]);
        $event = Dispatcher::dispatch(
            BlockEvents::BLOCK_EDITING,
            new BlockEditingEvent($this->serializer, $filename, $encodedBlock, $blockClass)
        );
        $blockContent = $event->getFileContent();

        FilesystemTools::writeFile($filename, $blockContent);

        Dispatcher::dispatch(
            BlockEvents::BLOCK_EDITED,
            new BlockEditedEvent($this->serializer, $filename, $encodedBlock, $blockClass)
        );
        DataLogger::log(
            sprintf(
                'Block "%s" has been edited on the "%s" slot on page "%s" for the "%s_%s" language',
                $options["blockname"],
                $options["slot"],
                $options["page"],
                $options["language"],
                $options["country"]
            )
        );
    }

    private function parseChildren($json)
    {
        $values = json_decode($json, true);
        if (!array_key_exists("children", $values)) {
            return $json;
        }

        $parsedChildren = array();
        $children = $values["children"];
        foreach ($children as $child) {
            if (!array_key_exists("type", $child)) {
                continue;
            }

            $block = $this->blockFactory->createBlock($child["type"]);
            $encodedBlock = $this->serializer->serialize($block, 'json');

            $updatedBlock = JsonTools::join($encodedBlock, $child);
            $block = $this->serializer->deserialize(json_encode($updatedBlock), get_class($block), 'json');
            $block->updateSource();

            $parsedChildren[] = json_decode($this->serializer->serialize($block, 'json'), true);
        }

        $values["children"] = $parsedChildren;

        return json_encode($values);
    }

}