<?php
/*
 * This file is part of the RedKite CMS InstallerBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Core\BowerBuilder;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * AlBowerBuilder parses al the registered bundles folders and collects the component.json
 * file if exists. All the collected files are merged and an unique file is generated
 * into the web folder.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBowerBuilder
{
    protected $kernel;

    /**
     * Constrctor
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Build bower files
     *
     * @param string $webPath The path to the web directory
     * @param string|null $projectRoot The project root directory where the files saved
     */
    public function build($webPath, $projectRoot=null)
    {
        if (empty($projectRoot)) {
            $projectRoot = dirname($this->kernel->getRootDir());
        }

        $components = array(
            "name" => "RedKite CMS",
            "dependencies" => $this->parse(),
        );
        @file_put_contents($projectRoot.'/bower.json', json_encode($components));

        $bowerrc = array(
            "directory" => $webPath . '/components',
        );
        @file_put_contents($projectRoot.'/.bowerrc', json_encode($bowerrc));
    }

    /**
     * Parses the registered bundles
     *
     * @return array
     */
    protected function parse()
    {
        $components = array();
        $bundles = $this->kernel->getBundles();
        foreach ($bundles as $bundle) {
            $componentPath = $bundle->getPath() . '/component.json';
            if (file_exists(($componentPath))) {
                $bundleComponents = json_decode(@file_get_contents($componentPath), true);
                if (null === $bundleComponents) {
                    throw new \InvalidArgumentException(sprintf('File %s has an error: please check the syntax consistency', $componentPath));
                }
                $components = ($bundle->getName() == "RedKiteCmsBundle") ? $bundleComponents['dependencies'] + $components : array_merge($components, $bundleComponents['dependencies']);
            }
        }

        return $components;
    }
}
