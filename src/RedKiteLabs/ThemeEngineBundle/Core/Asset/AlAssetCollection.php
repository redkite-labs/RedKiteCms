<?php
/*
 * This file is part of the AlphaLemonPageTreeBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace AlphaLemon\ThemeEngineBundle\Core\Asset;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Finder\Finder;

/**
 * Class for iterating over a list of Assets elements
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlAssetCollection implements AlAssetsCollectionInterface
{
    protected $kernel;
    protected $assets = array();

    /**
     * Constructor
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param array $assets
     */
    public function __construct(KernelInterface $kernel, array $assets = array())
    {
        $this->kernel = $kernel;

        $this->addRange($assets);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->assets);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->assets);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->assets);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return reset($this->assets);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return (current($this->assets) !== false);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->assets);
    }

    /**
     * {@inheritdoc}
     */
    public function add($asset)
    {
        if(null !== $asset && $asset != "" && is_string($asset))
        {
            $assetName = basename($asset);
            // parses the first subfolder when all the subfolder's files are required
            if ($assetName == '*') {
                $asset = new AlAsset($this->kernel, substr($asset, 0, strlen($asset) - 2));

                $assetPath = $asset->getRealPath();
                $finder = new Finder();
                $subAssets = $finder->files()->depth(0)->in($assetPath);
                foreach ($subAssets as $subAsset) {
                    $this->addAsset((string)$subAsset);
                }

                return;
            }

            $this->addAsset($asset);
        }
    }

    protected function addAsset($asset)
    {
        $asset = new AlAsset($this->kernel, $asset);
        if (!is_file($asset->getRealPath())) {
            if (!in_array($asset, $this->assets)) {
                $this->assets[] = $asset;
            }

            return;
        }

        // Avoids assets duplication
        $key = basename($asset->getRealPath());
        if (!array_key_exists($key, $this->assets)) {
            $this->assets[$key] = $asset;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addRange(array $assets)
    {
        foreach ($assets as $asset) {
            $this->add($asset);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove($asset)
    {
        $assetName = basename($asset);
        if (array_key_exists($assetName, $this->assets)) {
           unset($this->assets[$assetName]);

           return;
        }

        $asset = new AlAsset($this->kernel, $asset);
        if (in_array($asset, $this->assets)) {
            $key = array_search($asset, $this->assets);
            unset($this->assets[$key]);
        }
    }
}
