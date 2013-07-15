<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\CmsInstallerBundle\Core\BowerBuilder;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * AlBowerBuilder parses al the registered bundles folders and collects the component.json
 * file if exists. All the collected files are merged and an unique file is generated
 * into the web folder.
 *
 * @author alphalemon <webmaster@alphalemon.com>
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
     * Builds the component.json file
     *
     * @param string $filePath
     */
    public function build($filePath)
    {
        $components = array(
            "name" => "AlphaLemon CMS",
            "dependencies" => $this->parse(),
        );

        @file_put_contents($filePath, json_encode($components));
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
                $components = ($bundle->getName() == "AlphaLemonCmsBundle") ? $bundleComponents['dependencies'] + $components : array_merge($components, $bundleComponents['dependencies']);
            }
        }

        return $components;
    }
}
