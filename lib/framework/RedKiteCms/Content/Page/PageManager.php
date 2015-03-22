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

namespace RedKiteCms\Content\Page;

use RedKiteCms\Bridge\Dispatcher\Dispatcher;
use RedKiteCms\Bridge\Monolog\DataLogger;
use RedKiteCms\Content\PageCollection\PageCollectionBase;
use RedKiteCms\EventSystem\Event\Page\PermalinkChangedEvent;
use RedKiteCms\EventSystem\Event\Page\PageApprovedEvent;
use RedKiteCms\EventSystem\Event\Page\PageApprovingEvent;
use RedKiteCms\EventSystem\Event\Page\PageEditedEvent;
use RedKiteCms\EventSystem\Event\Page\PageEditingEvent;
use RedKiteCms\EventSystem\Event\Page\PageHidEvent;
use RedKiteCms\EventSystem\Event\Page\PageHidingEvent;
use RedKiteCms\EventSystem\Event\Page\PagePublishedEvent;
use RedKiteCms\EventSystem\Event\Page\PagePublishingEvent;
use RedKiteCms\EventSystem\Event\Page\SluggingPermalinkEvent;
use RedKiteCms\EventSystem\PageEvents;
use RedKiteCms\Exception\Publish\PageNotPublishedException;
use RedKiteCms\Tools\FilesystemTools;
use RedKiteCms\Tools\Utils;

/**
 * Class PageManager is the object deputed to handle the page's seo attributes
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Seo
 */
class PageManager extends PageCollectionBase
{
    /**
     * @type array
     */
    private $changedPermalink = array();

    /**
     * Returns the changed permalink
     *
     * @return array
     */
    public function getChangedPermalink()
    {
        return $this->changedPermalink;
    }

    /**
     * Edits the seo for the given page
     * @param string $pageName
     * @param array $values
     *
     * @return string The json seo content
     */
    public function edit($pageName, array $values)
    {
        $pageDir = $this->pagesDir . '/' . $pageName . '/' . $values["language"];
        $seoFile = (null !== $this->username) ? $pageDir . '/' . $this->username . '.json' : $pageDir . '/seo.json';
        $currentSeo = json_decode(FilesystemTools::readFile($seoFile), true);

        $values = $this->slugifyPermalink($values);
        $encodedSeo = json_encode($values);
        $event = Dispatcher::dispatch(PageEvents::PAGE_EDITING, new PageEditingEvent($seoFile, $encodedSeo));
        $encodedSeo = $event->getFileContent();
        FilesystemTools::writeFile($seoFile, $encodedSeo);

        if ($currentSeo["permalink"] != $values["permalink"]) {
            $this->changedPermalink = array(
                'old' => $currentSeo["permalink"],
                'new' => $values["permalink"],
            );
            Dispatcher::dispatch(
                PageEvents::PERMALINK_CHANGED,
                new PermalinkChangedEvent($currentSeo["permalink"], $values["permalink"])
            );
        }
        Dispatcher::dispatch(PageEvents::PAGE_EDITED, new PageEditedEvent($seoFile, $encodedSeo));
        DataLogger::log(
            sprintf('Page SEO attributes "%s" for language "%s" were edited', $pageName, $values["language"])
        );

        return $encodedSeo;
    }

    /**
     * Approves the page in production
     * @param $pageName
     * @param $languageName
     *
     * @return string The json seo content
     */
    public function approve($pageName, $languageName)
    {
        $this->contributorDefined();

        $baseDir = $this->pagesDir . '/' . $pageName . '/' . $languageName;
        $sourceFile = $baseDir . '/' . $this->username . '.json';
        $targetFile = $baseDir . '/seo.json';
        if (!file_exists($targetFile)) {
            throw new PageNotPublishedException('exception_page_not_published');
        }

        $values = json_decode(FilesystemTools::readFile($sourceFile), true);
        if (!empty($values["current_permalink"])) {
            $values["changed_permalinks"][] = $values["current_permalink"];
            $values["current_permalink"] = "";
        }

        $encodedSeo = json_encode($values);
        $event = Dispatcher::dispatch(PageEvents::PAGE_APPROVING, new PageApprovingEvent($sourceFile, $encodedSeo));
        $encodedSeo = $event->getFileContent();

        FilesystemTools::writeFile($sourceFile, $encodedSeo);
        FilesystemTools::writeFile($targetFile, $encodedSeo);

        Dispatcher::dispatch(PageEvents::PAGE_APPROVED, new PageApprovedEvent($sourceFile, $encodedSeo));
        DataLogger::log(sprintf('Page SEO attributes "%s" for language "%s" were approved', $pageName, $languageName));

        return $encodedSeo;
    }

    /**
     * Publish the current seo
     * @param $pageName
     * @param $languageName
     */
    public function publish($pageName, $languageName)
    {
        $this->contributorDefined();

        $baseDir = $this->pagesDir . '/' . $pageName . '/' . $languageName;
        $sourceFile = $baseDir . '/' . $this->username . '.json';
        $targetFile = $baseDir . '/seo.json';

        Dispatcher::dispatch(PageEvents::PAGE_PUBLISHING, new PagePublishingEvent());
        copy($sourceFile, $targetFile);

        Dispatcher::dispatch(PageEvents::PAGE_PUBLISHED, new PagePublishedEvent());
        DataLogger::log(sprintf('Page "%s" for language "%s" was published in production', $pageName, $languageName));
    }

    /**
     * Hides the current seo
     * @param $pageName
     * @param $languageName
     */
    public function hide($pageName, $languageName)
    {
        $this->contributorDefined();

        $baseDir = $this->pagesDir . '/' . $pageName . '/' . $languageName;
        $sourceFile = $baseDir . '/seo.json';

        Dispatcher::dispatch(PageEvents::PAGE_HIDING, new PageHidingEvent());
        unlink($sourceFile);

        Dispatcher::dispatch(PageEvents::PAGE_HID, new PageHidEvent());
        DataLogger::log(sprintf('Page "%s" for language "%s" was hidden from production', $pageName, $languageName));
    }

    private function slugifyPermalink(array $values)
    {
        $sluggedPermalink = Utils::slugify($values["permalink"]);
        if ($sluggedPermalink != $values["permalink"]) {
            $event = Dispatcher::dispatch(
                PageEvents::SLUGGING_PERMALINK,
                new SluggingPermalinkEvent($values["permalink"], $sluggedPermalink)
            );
            $sluggedText = $event->getChangedText();
            if ($sluggedText != $sluggedPermalink) {
                $sluggedPermalink = $sluggedText;
            }
            $values["permalink"] = $sluggedPermalink;
        }

        return $values;
    }
} 