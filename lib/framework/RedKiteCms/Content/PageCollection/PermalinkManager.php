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

namespace RedKiteCms\Content\PageCollection;

use RedKiteCms\Configuration\ConfigurationHandler;

/**
 * Class PermalinkManager is the object deputed to handle the blocks which contain one or more permalinks.
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Page
 */
class PermalinkManager
{
    /**
     * @type array|mixed
     */
    private $permalinks = array();
    /**
     * @type string
     */
    private $permalinksFile = "";
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    private $configurationHandler;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configurationHandler = $configurationHandler;
        $this->permalinksFile = $this->configurationHandler->siteDir() . '/permalinks.json';
        if (file_exists($this->permalinksFile)) {
            $this->permalinks = json_decode(file_get_contents($this->permalinksFile), true);
        }
    }

    /**
     * returns the information for the requested permalink
     * @param $permalink
     *
     * @return array
     */
    public function getPermalink($permalink)
    {
        if (!array_key_exists($permalink, $this->permalinks)) {
            return array();
        }

        return $this->permalinks[$permalink];
    }

    /**
     * Adds a block to permalinks list
     *
     * @param string $blockFile
     * @param string $blockContent
     *
     * @return $this
     */
    public function add($blockFile, $blockContent)
    {
        $this->removeBlock($blockFile);
        $blockPermalinks = $this->fetchPermalinksFromBlock($blockContent, $blockFile);
        if (!empty($blockPermalinks)) {
            $this->permalinks = array_merge_recursive($this->permalinks, $blockPermalinks);
        }

        return $this;
    }

    /**
     * Removes the given block from permalins list
     *
     * @param $blockFile
     *
     * @return $this
     */
    public function removeBlock($blockFile)
    {
        foreach ($this->permalinks as $permalink => $associatedBlocks) {
            $tmp = array_flip($associatedBlocks);
            unset($tmp[$blockFile]);
            if (empty($tmp)) {
                unset($this->permalinks[$permalink]);

                continue;
            }

            $this->permalinks[$permalink] = array_flip($tmp);
        }

        return $this;
    }

    /**
     * Updates a permalink
     *
     * @param $previousPermalink
     * @param $newPermalink
     *
     * @return $this
     */
    public function update($previousPermalink, $newPermalink)
    {
        $blocks = $this->permalinks[$previousPermalink];
        $this->remove($previousPermalink);
        $this->permalinks[$newPermalink] = $blocks;

        return $this;
    }

    /**
     * Removes a permalink from the list
     * @param string $permalink
     *
     * @return $this
     */
    public function remove($permalink)
    {
        if (array_key_exists($permalink, $this->permalinks)) {
            unset($this->permalinks[$permalink]);
        }

        return $this;
    }

    /**
     * Saves the permalinks
     *
     * @return $this
     */
    public function save()
    {
        file_put_contents($this->permalinksFile, json_encode($this->permalinks));

        return $this;
    }

    private function fetchPermalinksFromBlock($htmlBlock, $blockFile)
    {
        if (!preg_match_all('/href[^"]+"([^"]+)"/is', $htmlBlock, $matches)) {
            return array();
        }

        $permalinks = array();
        $links = $matches[1];
        foreach ($links as $link) {
            if ($this->checkEmptyLink($link)) {
                continue;
            }

            if ($this->checkLinkProtocol($link)) {
                continue;
            }

            $permalinks[$link][] = $blockFile;
        }

        return $permalinks;
    }

    private function checkEmptyLink($link)
    {
        if (empty($link) || $link == "#") {
            return true;
        }

        return false;
    }

    private function checkLinkProtocol($link)
    {
        $protocols = array(
            "http:",
            "https:",
            "mailto:",
            "afs:",
            "cid:",
            "file:",
            "ftp:",
            "mid:",
            "news:",
            "x-exec:",
            "javascript:",
        );

        foreach ($protocols as $protocol) {
            if (strpos($link, $protocol) !== false) {
                return true;
            }
        }

        return false;
    }
}