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

use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Exception\General\RuntimeException;
use Symfony\Component\Finder\Finder;

/**
 * This is the object deputed to create blocks
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Block
 */
class BlockFactory implements BlockFactoryInterface
{
    /**
     * @type array
     */
    private $blocks = array();
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    private $redKiteCmsConfig;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $redKiteCmsConfig
     */
    public function __construct(ConfigurationHandler $redKiteCmsConfig)
    {
        $this->redKiteCmsConfig = $redKiteCmsConfig;
    }

    /**
     * Boots the factory
     */
    public function boot()
    {
        $pluginDirs = $this->redKiteCmsConfig->pluginFolders();

        foreach ($pluginDirs as $pluginDir) {
            $this->blocks += $this->parse($pluginDir);
        }
    }

    /**
     * Returns the available blocks
     *
     * @return array
     */
    public function getAvailableBlocks()
    {
        return array_keys($this->blocks);
    }

    /**
     * {@inheritdoc}
     */
    public function createBlock($type)
    {
        if (!array_key_exists($type, $this->blocks)) {
            throw new RuntimeException(
                sprintf('The plugin %s is not registered: the block has not been created', $type)
            );
        }

        $class = $this->blocks[$type];

        return $this->instantiateBlock($class);
    }

    /**
     * {@inheritdoc}
     */
    public function createAllBlocks()
    {
        $blocks = array();
        foreach ($this->blocks as $blockClass) {
            $blocks[] = $this->instantiateBlock($blockClass);
        }

        return $blocks;
    }

    private function instantiateBlock($class)
    {
        $reflectionClass = new \ReflectionClass($class);

        return $reflectionClass->newInstance();
    }

    private function parse($pluginDir)
    {
        $blocks = array();
        $blocksDir = $pluginDir . '/Block';
        $finder = new Finder();
        $folders = $finder->directories()->depth(0)->in($blocksDir);
        foreach ($folders as $folder) {
            $blockName = basename($folder);
            $blocks[$blockName] = sprintf('RedKiteCms\Block\%s\Core\%sBlock', $blockName, $blockName);
        }

        return $blocks;
    }
} 