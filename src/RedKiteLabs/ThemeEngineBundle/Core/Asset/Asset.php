<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\ThemeEngineBundle\Core\Asset;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * The Asset object extracts the asset's full path and the absolute path to the
 * web/bundle's folder
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class Asset
{
    protected $kernel;
    protected $asset = null;
    protected $realPath = null;
    protected $absolutePath = null;
    protected $webPath = null;

    /**
     * Constructor
     *
     * @param KernelInterface $kernel
     * @param string          $asset    The asset
     * @param string          $webPath  Defines the folders path inside the web folder: web/my/webfolder -> my/webfolder
     */
    public function __construct(KernelInterface $kernel, $asset, $webPath = '')
    {
        $this->kernel = $kernel;
        $this->asset = $asset;
        $this->webPath = $webPath;
        if ( ! empty($this->webPath)) {
            $this->webPath .= '/';
        }

        $this->setUp();
    }

    /**
     * Returns the asset
     *
     * @return string
     */
    public function getAsset()
    {
        return $this->asset;
    }

    /**
     * Returns the asset's full path
     *
     * @return string|null
     */
    public function getRealPath()
    {
        if (null === $this->asset || empty($this->asset)) {
            return null;
        }

        return $this->normalizePath($this->realPath($this->realPath));
    }

    /**
     * Returns the asset's absolute path to web/bundle's folder
     *
     * @return string|null
     */
    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    /**
     * Returns the asset's real path to web/bundle's folder
     *
     * @param string $webFolder
     * @return string
     */
    public function getWebFolderRealPath($webFolder = 'web')
    {
        return $this->normalizePath($this->realPath($this->kernel->getRootDir() . '/../' .  $webFolder . '/' . $this->absolutePath));
    }

    /**
     * Sets up the asset information
     */
    protected function setUp()
    {
        if (empty($this->asset)) {
            return;
        }

        $this->asset = $this->normalizePath($this->asset);
        $this->realPath = $this->locateResource();

        // The asset has not been located, so the full path is the asset itself
        if (null === $this->realPath) {
            $this->realPath = $this->asset;
        }
        $this->absolutePath = $this->retrieveBundleWebFolder();
    }

    /**
     * Retrieves the web bundle folder from the current asset
     *
     * @return null|string
     */
    protected function retrieveBundleWebFolder()
    {
        $asset = $this->asset;
        
        $namespacesFile = $this->kernel->getRootDir() . '/../vendor/composer/autoload_namespaces.php';
        if (file_exists($namespacesFile)) {
            $map = require $namespacesFile;
            foreach ($map as $namespace => $paths) {
                // @codeCoverageIgnoreStart
                if ( ! is_array($paths)) {
                    $paths = array($paths);
                }
                // @codeCoverageIgnoreEnd

                foreach ($paths as $path) {
                    if (strpos($this->asset, $path) !== false) {
                        preg_match('/Bundle(.*)/', $this->asset, $matches);
                        $asset = str_replace("\\", "", $namespace) . $matches[1];
                        break;
                    }
                }
            }
        }

        preg_match('/([^@\/][\w]+Bundle)\/(Resources\/public)?\/(.*)/', $asset, $matches);
        if (!empty($matches) && count($matches) == 4) {
            return sprintf('bundles/%s/%s', preg_replace('/bundle$/', '', strtolower($matches[1])), $matches[3]);
        }

        preg_match('/[\/]?(bundles.*)/', strtolower($asset), $matches);
        if (!empty($matches)) {
            return $matches[1];
        }
        
        $asset = str_replace("@", "", strtolower($asset));
        $bundleDir = preg_replace('/bundle$/', '', $asset);
        
        return ($bundleDir !== $asset) ? $this->webPath . 'bundles/' . $bundleDir : null;
    }

    /**
     * Locates a resource defined by a relative path
     *
     * @return null!string
     */
    protected function locateResource($asset = null)
    {
        if (null === $asset) {
            $asset = $this->asset;
        }

        $asset = $this->normalizePath($asset);
        if(\substr($asset, 0, 1) != '@') $asset = '@' . $asset;

        try {
            // Fetches the relative resource to locate from asset
            preg_match('/(@[^\/]+)?([\w\/\.\-_]+)?/', $asset, $match);
            if (empty($match[1])) {
                return null;
            }

            $resource = $this->kernel->locateResource($match[1]);

            $resourceLength = strlen($resource) - 1;
            if (substr($resource, $resourceLength, 1) == '/') $resource = substr($resource, 0, $resourceLength);
            return (isset($match[2])) ? $resource . $match[2] : $resource;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Normalize a path as a unix path
     *
     * @param  string $path
     * @return string
     */
    protected function normalizePath($path)
    {
        return preg_replace('/\\\/', '/', $path);
    }

    private function realPath($path)
    {
        $realPath = realpath($path);
        if (false === $realPath) {
            $realPath = $path;
        }

        return $realPath;
    }
}
