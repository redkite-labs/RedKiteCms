<?php

namespace RedKiteLabs\RedKiteCmsBundle\Core\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\BundleGenerator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * AlBaseGenerator defines the base generator
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class AlBaseGenerator extends BundleGenerator
{
    protected $filesystem;
    protected $skeletonDir;
    protected $bundleSkeletonDir;

    /**
     * Generates the App-Block bundle
     * 
     * @param string $namespace
     * @param string $bundle
     * @param string $dir
     * @param string $format
     * @param string $structure
     * @param array $options
     */
    abstract public function generateExt($namespace, $bundle, $dir, $format, $structure, array $options);

    /**
     * Base constructor
     *
     * @param Filesystem $filesystem
     * @param string $skeletonDir
     */
    public function __construct(Filesystem $filesystem, $skeletonDir, $bundleSkeletonDir = null)
    {
        $this->filesystem = $filesystem;
        $this->skeletonDir = $skeletonDir;
        $this->bundleSkeletonDir = $bundleSkeletonDir;

        parent::__construct($filesystem, $skeletonDir);
    }
}
