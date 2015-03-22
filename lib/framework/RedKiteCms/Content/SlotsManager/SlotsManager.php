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

namespace RedKiteCms\Content\SlotsManager;


use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class SlotsManager is the object deputed to manage page slots
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\SlotsManager
 */
abstract class SlotsManager
{
    /**
     * @type string
     */
    protected $dataDir;
    /**
     * @type string
     */
    protected $siteDir;
    /**
     * @type string
     */
    protected $pagesDir;
    /**
     * @type array
     */
    protected $siteInfo;
    /**
     * @type string
     */
    protected $siteName;
    /**
     * @type string
     */
    protected $filesystem;
    /**
     * @type bool
     */
    private $override = false;

    /**
     * Constructor
     *
     * @param $dataDir
     * @param $siteName
     */
    public function __construct($dataDir, $siteName)
    {
        $this->dataDir = $dataDir;
        $this->siteName = $siteName;
        $this->siteDir = $this->dataDir . '/' . $this->siteName;
        $this->filesystem = new Filesystem();
        $this->siteInfo = json_decode(FilesystemTools::readFile($this->siteDir . '/site.json'), true);
    }

    /**
     * Adds a slot
     *
     * @param $slotName
     * @param array $blocks
     * @param null $username
     *
     * @return mixed
     */
    abstract public function addSlot($slotName, $blocks = array(), $username = null);

    /**
     * Overrides the slot when exists
     *
     * @return $this
     */
    public function override()
    {
        $this->override = true;

        return $this;
    }

    /**
     * Generates a new slot
     *
     * @param string $path
     * @param array $blocks
     * @param string|null $username
     */
    protected function generateSlot($path, $blocks = array(), $username = null)
    {
        if (is_dir($path) && !$this->override) {
            return;
        }

        $folders = array();
        $activeDir = $path . '/active';
        $contributorsDir = $path . '/contributors';
        $folders[] = $activeDir . '/blocks';
        $folders[] = $activeDir . '/archive';
        $folders[] = $contributorsDir;

        $targetDir = $activeDir;
        $blocksDir = $activeDir . '/blocks';
        if (null !== $username) {
            $targetDir = $contributorsDir . '/' . $username;
            $blocksDir = $targetDir . '/blocks';
            $folders[] = $targetDir;
            $folders[] = $targetDir . '/archive';
            $folders[] = $blocksDir;
        }

        $this->filesystem->mkdir($folders);
        $this->generateBlocks($blocks, $blocksDir, $targetDir);
    }

    /**
     * Generate blocks for the current slot
     * @param array $blocks
     * @param string $blocksDir
     * @param string $targetDir
     */
    protected function generateBlocks(array $blocks, $blocksDir, $targetDir)
    {
        $c = 1;
        $generatedBlocks = array();
        foreach ($blocks as $block) {
            $blockName = 'block' . $c;
            $fileName = sprintf('%s/%s.json', $blocksDir, $blockName);
            $generatedBlocks[] = $blockName;

            $value = $block;
            if (is_array($value)) {
                $value = json_encode($block);
            }

            FilesystemTools::writeFile($fileName, $value);
            $c++;
        }

        $slotDefinition = array(
            'next' => $c,
            'blocks' => $generatedBlocks,
            'revision' => 1,
        );
        FilesystemTools::writeFile($targetDir . '/slot.json', json_encode($slotDefinition));
    }
} 