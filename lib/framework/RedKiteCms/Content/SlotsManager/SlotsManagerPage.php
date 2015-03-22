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

use Symfony\Component\Finder\Finder;

/**
 * Class SlotsManagerPage is the object deputed to cretae a slot repeated at page level
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\SlotsManager
 */
class SlotsManagerPage extends SlotsManager
{
    /**
     *{@inheritdoc}
     */
    public function addSlot($slotName, $blocks = array(), $username = null)
    {
        $pagesDir = $this->siteDir . '/pages/pages';
        $sitePages = $this->fetchPages($pagesDir);
        foreach ($sitePages as $pageName) {
            foreach ($this->siteInfo["languages"] as $languageName) {
                $pageDir = $pagesDir . '/' . $pageName . '/' . $languageName . '/' . $slotName;
                $this->generateSlot($pageDir, $blocks, $username);
            }
        }
    }

    private function fetchPages($dir)
    {
        $pages = array();
        $finder = new Finder();
        $folders = $finder->directories()->depth(0)->in($dir);
        foreach ($folders as $folder) {
            $pages[] = basename((string)$folder);
        }

        return $pages;
    }
} 