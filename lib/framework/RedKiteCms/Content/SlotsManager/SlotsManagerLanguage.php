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

/**
 * Class SlotsManagerLanguage is the object deputed to cretae a slot repeated at language level
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\SlotsManager
 */
class SlotsManagerLanguage extends SlotsManager
{
    /**
     *{@inheritdoc}
     */
    public function addSlot($slotName, $blocks = array(), $username = null)
    {
        $slotsDir = $this->siteDir . '/slots/' . $slotName;
        foreach ($this->siteInfo["languages"] as $languageName) {
            $languageDir = $slotsDir . '/' . $languageName;
            $this->generateSlot($languageDir, $blocks, $username);
        }
    }
} 