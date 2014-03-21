<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
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
use Symfony\Component\Finder\Finder;

/**
 * Class for iterating over a list of Assets elements
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AssetCollection implements AssetsCollectionInterface
{
    protected $kernel;
    protected $assets = array();

    /**
     * Constructor
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param array                                         $assets
     * @codeCoverageIgnore
     */
    public function __construct(KernelInterface $kernel, array $assets = array())
    {
        $this->kernel = $kernel;

        $this->addRange($assets);
    }

    /**
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @codeCoverageIgnore
     */
    public function current()
    {
        return current($this->assets);
    }

    /**
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return scalar scalar on success, or <b>NULL</b> on failure.
     * @codeCoverageIgnore
     */
    public function key()
    {
        return key($this->assets);
    }

    /**
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return mixed
     * @codeCoverageIgnore
     */
    public function next()
    {
        return next($this->assets);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return mixed
     * @codeCoverageIgnore
     */
    public function rewind()
    {
        return reset($this->assets);
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * @codeCoverageIgnore
     */
    public function valid()
    {
        return (current($this->assets) !== false);
    }

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * @codeCoverageIgnore
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
        if (null !== $asset && $asset != "" && is_string($asset)) {
            $assetName = basename($asset);
            // parses the first subfolder when all the subfolder's files are required
            if ($assetName == '*') {
                $asset = new Asset($this->kernel, substr($asset, 0, strlen($asset) - 2));

                $assetPath = $asset->getRealPath();
                $finder = new Finder();
                $subAssets = $finder->files()->depth(0)->in($assetPath);
                foreach ($subAssets as $subAsset) {
                    $this->addAsset((string) $subAsset);
                }

                return;
            }

            $this->addAsset($asset);
        }
    }

    protected function addAsset($asset)
    {
        $asset = new Asset($this->kernel, $asset);
        if ( ! is_file($asset->getRealPath())) {
            if (!in_array($asset, $this->assets)) {
                $this->assets[] = $asset;
            }

            return;
        }

        // Avoids assets duplication
        $key = basename($asset->getRealPath());
        if ( ! array_key_exists($key, $this->assets)) {
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
        $asset = new Asset($this->kernel, $asset);
        if (in_array($asset, $this->assets)) {
            $key = array_search($asset, $this->assets);
            unset($this->assets[$key]);
        }
    }
}
