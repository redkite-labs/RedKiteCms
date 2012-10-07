<?php

namespace AlphaLemon\ThemeEngineBundle\Core\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\BundleGenerator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * AlBaseGenerator defines the base generator
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlBaseGenerator extends BundleGenerator
{
    protected $filesystem;
    protected $skeletonDir;

    /**
     * Defines an extended generate method which has an addictional option array
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
    public function __construct(Filesystem $filesystem, $skeletonDir)
    {
        $this->filesystem = $filesystem;
        $this->skeletonDir = $skeletonDir;

        parent::__construct($filesystem, $skeletonDir);
    }
}
