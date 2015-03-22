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

namespace RedKiteCms\Bridge\ElFinder;

use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Exception\General\InvalidArgumentException;

/**
 * The object deputed to handle a base elFinder connector
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Bridge\ElFinder
 */
abstract class ElFinderConnector implements ElFinderConnectorInterface
{
    /**
     * @type null|ConfigurationHandler
     */
    protected $configurationHandler = null;
    /**
     * @type array
     */
    protected $options = array();
    /**
     * @type bool
     */
    private $connectorLoaded = false;

    /**
     * Constructor
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configurationHandler = $configurationHandler;
        $this->loadConnectors();
        $this->options = $this->configure();

        if (null === $this->options) {
            throw new InvalidArgumentException(
                sprintf(
                    "The configure method cannot return a null value. Check the value returned by the configure method in the %className% object",
                    \get_class($this)
                )
            );
        }

        if (!is_array($this->options)) {
            throw new InvalidArgumentException(
                sprintf(
                    "The configure method must return an array. Check the value returned by the configure method in the %className% object",
                    \get_class($this)
                )
            );
        }
    }

    private function loadConnectors()
    {
        if ($this->connectorLoaded) {
            return;
        }

        $webDir = $this->configurationHandler->webDir();
        require_once $webDir . '/components/redkitecms/elfinder/php/elFinderConnector.class.php';
        require_once $webDir . '/components/redkitecms/elfinder/php/elFinder.class.php';
        require_once $webDir . '/components/redkitecms/elfinder/php/elFinderVolumeDriver.class.php';
        require_once $webDir . '/components/redkitecms/elfinder/php/elFinderVolumeLocalFileSystem.class.php';

        $this->connectorLoaded = true;
    }

    /**
     * Starts the elFinder connector
     */
    public function connect()
    {
        $connector = new \elFinderConnector(new \elFinder($this->options));
        $connector->run();
    }

    /**
     * Generates the elFinder options
     *
     * @param string $folder
     * @param string $rootAlias
     * @return array
     */
    protected function generateOptions($folder, $rootAlias)
    {
        $assetsPath = $this->configurationHandler->uploadAssetsDir() . '/' . $folder;
        if (!is_dir($assetsPath)) {
            @mkdir($assetsPath);
        }

        $options = array(
            'locale' => '',
            'roots' => array(
                array(
                    'driver' => 'LocalFileSystem',
                    // driver for accessing file system (REQUIRED)
                    'path' => $assetsPath,
                    // path to files (REQUIRED)
                    'URL' => $this->configurationHandler->absoluteUploadAssetsDir() . '/' . $folder,
                    // URL to files (REQUIRED)
                    'accessControl' => 'access',
                    // disable and hide dot starting files (OPTIONAL)
                    'rootAlias' => $rootAlias
                    // disable and hide dot starting files (OPTIONAL)
                )
            )
        );

        return $options;
    }
}
