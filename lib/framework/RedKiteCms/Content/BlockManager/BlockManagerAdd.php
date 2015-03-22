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
use RedKiteCms\EventSystem\Event\Block\BlockAddedEvent;
use RedKiteCms\EventSystem\Event\Block\BlockAddingEvent;
use RedKiteCms\Tools\FilesystemTools;

/**
 * Class BlockManagerAdd is the object deputed to add a new block to the given slot
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\BlockManager
 */
class BlockManagerAdd extends BlockManager
{
    /**
     * Adds the block to the slot for the given language and page
     *
     * @param string $sourceDir
     * @param array $options
     * @param string $username
     *
     * @return string The saved content
     */
    public function add($sourceDir, array $options, $username)
    {
        $this->resolveOptions($options);
        $this->createContributorDir($sourceDir, $options, $username);

        $dir = $this
            ->init($sourceDir, $options, $username)
            ->getDirInUse();
        $blockName = $this->addBlockToSlot($dir, $options);
        $blockContent = $this->addBlock($dir, $options, $blockName);

        DataLogger::log(
            sprintf(
                'Block "%s" has been added to the "%s" slot on page "%s" for the "%s_%s" language',
                $blockName,
                $options["slot"],
                $options["page"],
                $options["language"],
                $options["country"]
            )
        );

        return $blockContent;
    }

    /**
     * Defines the options required by the add method
     *
     * @param array $options
     */
    protected function resolveOptions(array $options)
    {
        if ($this->optionsResolved) {
            return;
        }

        $this->optionsResolver->setRequired(
            array(
                'page',
                'language',
                'country',
                'slot',
                'blockname',
                'type',
                'position',
                'direction',
            )
        );

        $this->optionsResolver->resolve($options);
        $this->optionsResolved = true;
    }

    private function addBlock($dir, array $options, $blockName)
    {
        $filename = sprintf('%s/blocks/%s.json', $dir, $blockName);
        $block = $this->blockFactory->createBlock($options["type"]);
        $block->setName($blockName);
        $block->setSlotName($options["slot"]);
        $blockClass = get_class($block);
        $encodedBlock = $this->serializer->serialize($block, 'json');

        $event = Dispatcher::dispatch(
            BlockEvents::BLOCK_ADDING,
            new BlockAddingEvent($this->serializer, $filename, $encodedBlock, $blockClass)
        );
        $blockContent = $event->getFileContent();

        FilesystemTools::writeFile($filename, $blockContent);

        Dispatcher::dispatch(
            BlockEvents::BLOCK_ADDED,
            new BlockAddedEvent($this->serializer, $filename, $encodedBlock, $blockClass)
        );

        return $blockContent;
    }
}