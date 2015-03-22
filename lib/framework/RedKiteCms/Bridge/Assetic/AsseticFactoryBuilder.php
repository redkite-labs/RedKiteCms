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

namespace RedKiteCms\Bridge\Assetic;

use Assetic\AssetManager;
use Assetic\Factory\AssetFactory;
use Assetic\Filter\FilterInterface;
use Assetic\FilterManager;
use RedKiteCms\Configuration\ConfigurationHandler;

/**
 * This object is deputed to build an AssetFactory object
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Bridge\Assetic
 */
class AsseticFactoryBuilder
{
    /**
     * @type ConfigurationHandler
     */
    private $configurationHandler;
    /**
     * @type array
     */
    private $filters = array();

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configurationHandler = $configurationHandler;
    }

    /**
     * Builds the AssetFactory object
     *
     * @return \Assetic\Factory\AssetFactory
     */
    public function build()
    {
        $assetManager = new AssetManager();
        $filterManager = new FilterManager();
        foreach ($this->filters as $filterName => $filter) {
            $filterManager->set($filterName, $filter);
        }
        $assetsFactory = new AssetFactory($this->configurationHandler->webDir());
        $assetsFactory->setAssetManager($assetManager);
        $assetsFactory->setFilterManager($filterManager);

        return $assetsFactory;
    }

    /**
     * Adds a filter to the AssetFactory object
     *
     * @param string                          $filterName
     * @param \Assetic\Filter\FilterInterface $filter
     * @return \RedKiteCms\Bridge\Assetic\AsseticFactoryBuilder
     */
    public function addFilter($filterName, FilterInterface $filter)
    {
        $this->filters[$filterName] = $filter;

        return $this;
    }
}