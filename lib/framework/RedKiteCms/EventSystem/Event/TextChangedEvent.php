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

namespace RedKiteCms\EventSystem\Event;

/**
 * Class TextChangedEvent is the object deputed to handle the base properties for an event that involves a text changed,
 * i.e. a page name
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event
 */
abstract class TextChangedEvent extends Event
{
    /**
     * @type string
     */
    protected $originalText;
    /**
     * @type string
     */
    protected $changedText;

    /**
     * Constructor
     *
     * @param $originalText
     * @param $changedText
     */
    public function __construct($originalText, $changedText)
    {
        $this->originalText = $originalText;
        $this->changedText = $changedText;
    }

    /**
     * Returns the original text
     *
     * @return string
     */
    public function getOriginalText()
    {
        return $this->originalText;
    }

    /**
     * Returns the changed text
     *
     * @return string
     */
    public function getChangedText()
    {
        return $this->changedText;
    }

    /**
     * Sets a new changed text
     *
     * @return string
     */
    public function setChangedText($value)
    {
        $this->changedText = $value;

        return $this;
    }
}