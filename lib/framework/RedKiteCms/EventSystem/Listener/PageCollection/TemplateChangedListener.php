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

namespace RedKiteCms\EventSystem\Listener\PageCollection;


use RedKiteCms\Content\Theme\Theme;
use RedKiteCms\EventSystem\Event\PageCollection\TemplateChangedEvent;

/**
 * Class TemplateChangedListener listens to TemplateChangedEvent to add the slots for the new template to the page
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Page
 */
class TemplateChangedListener
{
    private $theme;

    public function __construct(Theme $theme)
    {
        $this->theme = $theme;
    }

    public function onTemplateChanged(TemplateChangedEvent $event)
    {
        $newTemplate = $event->getChangedText();

        $this->theme->addTemplateSlots($newTemplate, $event->getUsername());
    }
}