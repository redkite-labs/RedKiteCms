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

namespace RedKiteCms\EventSystem\Listener\Page;


use RedKiteCms\Bridge\Monolog\DataLogger;
use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Content\PageCollection\PermalinkManager;
use RedKiteCms\EventSystem\Event\Page\PermalinkChangedEvent;
use RedKiteCms\Tools\FilesystemTools;

/**
 * Class PermalinkChangedListener listens to PermalinkChangedEvent to update permalinks on the blocks where the permalink
 * was used
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Seo
 */
class PermalinkChangedListener
{
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    private $configurationHandler;
    /**
     * @type \RedKiteCms\Content\PageCollection\PermalinkManager
     */
    private $permalinkManager;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     * @param \RedKiteCms\Content\PageCollection\PermalinkManager $permalinkManager
     */
    public function __construct(ConfigurationHandler $configurationHandler, PermalinkManager $permalinkManager)
    {
        $this->configurationHandler = $configurationHandler;
        $this->permalinkManager = $permalinkManager;
    }

    /**
     * Update permalinks on the blocks where the permalink was used
     *
     * @param \RedKiteCms\EventSystem\Event\Page\PermalinkChangedEvent $event
     */
    public function onPermalinkChanged(PermalinkChangedEvent $event)
    {
        $previousPermalink = $event->getOriginalText();
        $newPermalink = $event->getChangedText();

        $this->updatePermalinkOnBlocks($previousPermalink, $newPermalink);
        $this->updateHomepagePermalink($previousPermalink, $newPermalink);
    }

    private function updatePermalinkOnBlocks($previousPermalink, $newPermalink)
    {
        $blockFiles = $this->permalinkManager->getPermalink($previousPermalink);
        if (empty($blockFiles)) {
            return;
        }

        foreach ($blockFiles as $blockFile) {
            $blockContent = file_get_contents($blockFile);
            $updatedBlockContent = str_replace($previousPermalink, $newPermalink, $blockContent);
            $result = @file_put_contents($blockFile, $updatedBlockContent);
            $not = '';
            if (!$result) {
                $not = 'not ';
            }

            DataLogger::log(
                sprintf(
                    'Permalink "%s" has %sbeen updated for the block stored into the filename "%s"',
                    $newPermalink,
                    $not,
                    realpath($blockFile)
                )
            );
        }

        $this->permalinkManager
            ->update($previousPermalink, $newPermalink)
            ->save();
    }

    private function updateHomepagePermalink($previousPermalink, $newPermalink)
    {
        if ($this->configurationHandler->homepagePermalink() == $previousPermalink) {
            $siteFile = $this->configurationHandler->siteDir() . '/site.json';
            $siteInfo = json_decode(FilesystemTools::readFile($siteFile), true);
            $siteInfo["homepage_permalink"] = $newPermalink;
            FilesystemTools::writeFile($siteFile, json_encode($siteInfo));
        }
    }
}