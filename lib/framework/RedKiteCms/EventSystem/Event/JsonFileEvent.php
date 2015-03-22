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

use RedKiteCms\Bridge\Monolog\DataLogger;

/**
 * Class JsonFileEvent is the object deputed to handle the base properties for an event which involves a json file management
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event
 */
abstract class JsonFileEvent extends Event
{
    /**
     * @type null|string
     */
    protected $fileContent;
    /**
     * @type null|string
     */
    protected $filePath;

    /**
     * Constructor
     *
     * @param null $filePath
     * @param null $fileContent
     */
    public function __construct($filePath = null, $fileContent = null)
    {
        $this->filePath = $filePath;
        $this->fileContent = $fileContent;
    }

    /**
     * Returns the json file path
     *
     * @return null|string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Sets the json file path
     * @param $filePath
     *
     * @return $this
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Returns the json file contents
     *
     * @return null|string
     */
    public function getFileContent()
    {
        return $this->fileContent;
    }

    /**
     * Sets the json file contents
     * @param $fileContent
     *
     * @return $this
     */
    public function setFileContent($fileContent)
    {
        // Accapts only a json value
        if (null === json_decode($fileContent)) {
            DataLogger::log(
                sprintf(
                    'Event "%s" discharged the "%s" content because it is not a valid json',
                    get_class($this),
                    $fileContent
                ),
                DataLogger::WARNING
            );

            return $this;
        }

        $this->fileContent = $fileContent;

        return $this;
    }
}