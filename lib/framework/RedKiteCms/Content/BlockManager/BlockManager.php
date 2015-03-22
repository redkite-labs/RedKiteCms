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

use JMS\Serializer\SerializerInterface;
use RedKiteCms\Content\Block\BlockFactoryInterface;
use RedKiteCms\FilesystemEntity\FilesystemEntity;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class BlockManager is the base object deputed to handle a block
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\BlockManager
 */
class BlockManager extends FilesystemEntity
{
    /**
     * @type bool
     */
    protected $optionsResolved = false;
    /**
     * @type \RedKiteCms\Content\Block\BlockFactoryInterface
     */
    protected $blockFactory;

    /**
     * @param \JMS\Serializer\SerializerInterface                $serializer
     * @param \RedKiteCms\Content\Block\BlockFactoryInterface    $blockFactory
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function __construct(SerializerInterface $serializer, BlockFactoryInterface $blockFactory, OptionsResolver $resolver)
    {
        parent::__construct($serializer, $resolver);

        $this->blockFactory = $blockFactory;
        $this->filesystem = new Filesystem();
    }

    /**
     * Archives a removed file
     *
     * @param       string $sourceDir
     * @param       string $removedFile
     * @param       array $options
     */
    public function archiveRemovedFile($sourceDir, $removedFile, array $options)
    {
        $targetDir = $sourceDir . '/removed';
        $targetFilename = sprintf('%s/%s/%s.json', $targetDir, $options['blockname'], date("Y-m-d-H.i.s"));
        $this->removeArchivedFiles($sourceDir, $targetDir, $options['blockname']);
        $this->filesystem->copy($removedFile, $targetFilename, true);
    }

    /**
     * Removes the given archived files folder
     *
     * @param string $sourceDir
     * @param string $targetDir
     * @param string $blockName
     */
    protected function removeArchivedFiles($sourceDir, $targetDir, $blockName)
    {
        $archiveDir = $sourceDir . '/archive/' . $blockName;
        if (!is_dir($archiveDir)) {
            return;
        }
        $targetDir .= '/' . $blockName;
        $this->filesystem->mirror($archiveDir, $targetDir);
        $this->filesystem->remove($archiveDir);
    }

    /**
     * Defines the common required options by a block manager
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
                'blockname',
            )
        );

        parent::resolveOptions($options);
        $this->optionsResolved = true;
    }

    /**
     * Creates the contributor folder
     *
     * @param string $sourceDir
     * @param array $options
     * @param string $username
     */
    protected function createContributorDir($sourceDir, array $options, $username)
    {
        if (null === $username) {
            return;
        }

        $this->init($sourceDir, $options, $username);
        if (is_dir($this->contributorDir)) {
            return;
        }

        $this->filesystem->copy($this->productionDir . '/slot.json', $this->contributorDir . '/slot.json', true);
        $this->filesystem->mirror($this->productionDir . '/blocks', $this->contributorDir . '/blocks');
    }

    /**
     * Adds a default block to the given slot
     *
     * @param string $dir
     * @param array $options
     *
     * @return string
     */
    protected function addBlockToSlot($dir, array $options)
    {
        $slot = $this->getSlotDefinition($dir);
        $next = $slot["next"];
        $blocks = $slot["blocks"];
        $position = $options["position"];
        $blockName = sprintf('block%s', $next);
        array_splice($blocks, $position, 0, $blockName);
        $next++;
        $slot["next"] = $next;
        $slot["blocks"] = $blocks;
        $this->saveSlotDefinition($dir, $slot);

        return $blockName;
    }

    /**
     * Fetches the slot definition
     *
     * @param string $dir
     *
     * @return array
     */
    protected function getSlotDefinition($dir)
    {
        $slotsFilename = $this->getSlotDefinitionFile($dir);

        return json_decode(FilesystemTools::readFile($slotsFilename), true);
    }

    /**
     * Gets the slot file
     *
     * @param string $dir
     *
     * @return string
     */
    protected function getSlotDefinitionFile($dir)
    {
        return sprintf('%s/slot.json', $dir);
    }

    /**
     * Saves the slot definition
     *
     * @param string $dir
     * @param array $slot
     */
    protected function saveSlotDefinition($dir, array $slot)
    {
        $slotsFilename = $this->getSlotDefinitionFile($dir);

        FilesystemTools::writeFile($slotsFilename, json_encode($slot), $this->filesystem);
    }

    /**
     * Removes a block from the slot files and returns back the block name
     *
     * @param array $options
     * @param string $targetDir
     *
     * @return string
     */
    protected function removeBlockFromSlotFile(array $options, $targetDir = null)
    {
        $targetDir = $this->workDirectory($targetDir);

        $slot = $this->getSlotDefinition($targetDir);
        $blockName = $options["blockname"];

        $tmp = array_flip($slot["blocks"]);
        unset($tmp[$blockName]);
        $slot["blocks"] = array_keys($tmp);

        $this->saveSlotDefinition($targetDir, $slot);

        return $blockName;
    }

    /**
     * Archives the given block
     *
     * @param string $blockName
     * @param string $targetDir
     */
    protected function archiveBlock($blockName, $targetDir = null)
    {
        $targetDir = $this->workDirectory($targetDir);
        $filename = $targetDir . '/blocks/' . $blockName . '.json';
        if (!file_exists($filename)) {
            return;
        }

        $archiveFilename = sprintf('%s/%s/%s.json', $this->getArchiveDir($targetDir), $blockName, date("Y-m-d-H.i.s"));
        $this->filesystem->copy($filename, $archiveFilename, true);
    }
}