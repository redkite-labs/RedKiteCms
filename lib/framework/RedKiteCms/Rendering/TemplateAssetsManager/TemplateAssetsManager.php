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

namespace RedKiteCms\Rendering\TemplateAssetsManager;

use Assetic\Asset\AssetCache;
use Assetic\Cache\FilesystemCache;
use RedKiteCms\Bridge\Assetic\AsseticFactoryBuilder;
use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Plugin\Plugin;

/**
 * TemplateAssetsManager is the object deputed to collect assets parsing RedKite
 * CMS blocks and themes in use in the current website
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @method TemplateAssetsManager getExternalStylesheets() Returns the handled external stylesheets
 * @method TemplateAssetsManager getInternalStylesheets() Returns the handled internal stylesheets
 * @method TemplateAssetsManager getExternalJavascripts() Returns the handled external javascripts
 * @method TemplateAssetsManager getInternalJavascripts() Returns the handled internal javascripts
 */
class TemplateAssetsManager
{
    /**
     * @type null|string
     */
    protected $template = null;
    /**
     * @type array
     */
    protected $availableBlocks = array();
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    private $configurationHandler;
    /**
     * @type null|\RedKiteCms\Bridge\Assetic\AsseticFactoryBuilder
     */
    private $assetic = null;
    /**
     * @type array
     */
    private $assets = null;
    /**
     * @type string
     */
    private $webDir;
    /**
     * @type string
     */
    private $cacheDir;
    /**
     * @type string
     */
    private $type = "";

    /**
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     * @param \RedKiteCms\Bridge\Assetic\AsseticFactoryBuilder $assetic
     */
    public function __construct(ConfigurationHandler $configurationHandler, AsseticFactoryBuilder $assetic)
    {
        $this->configurationHandler = $configurationHandler;
        $this->assetic = $assetic;
    }

    /**
     * Boots the TemplateAssetsManager object basing on the give type
     * @param string $type
     *
     * @return $this
     */
    public function boot($type = "cms")
    {
        $this->type = $type;
        $this->assets = array(
            'getExternalStylesheets' => array(),
            'getExternalJavascripts' => array(),
            'getInternalStylesheets' => array(),
            'getInternalJavascripts' => array(),
        );

        $this->webDir = $this->configurationHandler->webDir();
        $this->cacheDir = $this->configurationHandler->cacheDir() . '/assetic/redkitecms/' . $type;
        $assets = $this->configurationHandler->getAssetsByType($type);
        $this->assets = array_merge($this->assets, $assets);

        return $this;
    }

    /**
     * Add assets to the collection
     *
     * @param array $assets
     */
    public function add(array $assets)
    {
        foreach ($assets as $type => $asset) {
            if ($asset instanceof Plugin) {
                $this->parse($asset);

                continue;
            }

            $this->assets[$type] = array_merge($this->assets[$type], $asset);
        }
    }

    /**
     * Creates magic methods
     *
     * @param  string $name   the method name
     * @param  mixed  $params the values to pass to the called method
     * @return mixed  Depends on method called
     */
    public function __call($name, $arguments)
    {
        if (null === $this->assets) {
            return null;
        }

        if (array_key_exists($name, $this->assets)) {
            $assets = $this->assets[$name];
            if ((strpos(strtolower($name), 'internal') !== false)) {
                return implode("\n", $this->assets[$name]);
            }

            $filters = array();
            if (!empty($arguments)) {
                $filters = $arguments[0];
            }

            $filename = '';
            switch ($name) {
                case 'getExternalStylesheets':
                    $filename = '/redkitecms/assets-' . $this->type . '.css';
                    break;
                case 'getExternalJavascripts':
                    $filename = '/redkitecms/assets-' . $this->type . '.js';
                    break;

            }
            $this->compressAndSave($this->webDir . $filename, $assets, $filters);

            return array(
                $filename,
            );
        }

        throw new \RuntimeException('TemplateAssetsManager does not support the method: ' . $name);
    }

    private function parse(Plugin $plugin)
    {
        $methods = array(
            'getExternalStylesheets',
            'getExternalJavascripts',
            'getInternalStylesheets',
            'getInternalJavascripts',
        );

        $name = $plugin->getName();
        foreach ($methods as $method) {
            $blockAssets = $plugin->$method();
            if (null === $blockAssets) {
                continue;
            }

            $webDir = $this->webDir;

            $assets = array();
            foreach ($blockAssets as $asset) {
                $assets[] = sprintf($webDir . '/plugins/%s/%s', strtolower($name), $asset);
            }

            $this->assets[$method] = array_merge($this->assets[$method], $assets);
        }
    }

    private function compressAndSave($targetFile, $assets, $filters)
    {
        $assetsFactory = $this->assetic->build();
        $asset = $assetsFactory->createAsset(
            $assets,
            $filters
        );

        if (null === $this->cacheDir) {
            $this->saveFile($targetFile, $asset->dump(), false);

            return;
        }

        $assetCache = new AssetCache(
            $asset,
            new FilesystemCache($this->cacheDir)
        );

        $this->saveFile($targetFile, $assetCache->dump(), true);
    }

    private function saveFile($targetFile, $content, $hasCache)
    {
        if ($hasCache && file_exists($targetFile)) {
            $savedContents = file_get_contents($targetFile);
            if ($content === $savedContents) {
                return;
            }
        }

        $baseDir = dirname($targetFile);
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        file_put_contents($targetFile, $content);
    }
}
