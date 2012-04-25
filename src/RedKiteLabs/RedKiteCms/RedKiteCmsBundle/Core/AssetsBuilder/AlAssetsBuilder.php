<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\AssetsBuilder;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Finder\Finder;

/**
 * Generates a twig file for the given assets. 
 *
 * @deprecated
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlAssetsBuilder
{
    protected $container;
    protected $outputBundle;
    protected $outputFolder;
    protected $assets = array();
    protected $assetsFiles = array();

    public function  __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->outputBundle = ($this->container->hasParameter('al.deploy_bundle')) ? $this->container->getParameter('al.deploy_bundle') : null;        
        if($this->container->hasParameter('alcms.assets.output_folder')) $this->outputFolder = $this->container->getParameter('alcms.assets.output_folder');        
    }

    public function getOutputBundle()
    {
        return $this->outputBundle;
    }

    public function setOutputBundle($v)
    {
        $this->outputBundle = $v; ;
    }
    
    public function getOutputFolder()
    {
        return $this->outputFolder;
    }
    
    public function setOutputFolder($v)
    {
        $this->outputFolder = $v;
    }

    public function getAssets()
    {
        return $this->assets;
    }
    
    /**
     * Adds an array of assets. Accepted assets are given as {% stylesheets %}{% endstylesheets %} function expects:
     *   
     *   - An asset file with relative path, ie bundles/alphalemoncms/js/alphalemon.js
     *   - An asset directory, ie bundles/alphalemoncms/js/*
     * 
     * @param array $assets 
     */
    public function addAssets(array $assets)
    { 
        foreach($assets as $asset)
        {
            $filename = basename($asset);     
            $currentAsset = $asset;
            
            // Checks if the assets is given with a relative path 
            if(false !== strpos($currentAsset, 'bundles') || false !== strpos($currentAsset, '@'))
            {    
                // Recreates the full path
                if(false === strpos($currentAsset, '@'))
                {
                    $currentAsset = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alcms.web_folder_name') . '/' . $currentAsset;
                }
                else
                {
                    preg_match('/(@[\w]+)\/([\w\/\*]+)/', $currentAsset, $match);
                    $currentAsset = AlToolkit::locateResource($this->container, $match[1]) . $match[2];
                }
                $currentAsset = AlToolkit::normalizePath($currentAsset);
                
                // Checks if the asset represents a folder that requires all the files that contains
                $assetLength = strlen($currentAsset);
                if(substr($currentAsset, $assetLength - 1, 1) == '*')
                {
                    // Checks the files stored into the folder
                    $path = substr($currentAsset, 0, $assetLength - 1);
                    $finder = new Finder();
                    $filesFound = $finder->depth(0)->files()->in($path);                     
                    foreach($filesFound as $fileFound)
                    {
                        $f = basename((string)$fileFound);
                        if(!in_array($f, $this->assetsFiles))
                        {
                            $this->assetsFiles[] = $f;
                        }
                    }
                }
            }
            
            if(!in_array($asset, $this->assets) && !in_array($filename, $this->assetsFiles))
            {
                $this->assets[] = $asset;
            }
        } 
    }

    /**
     * Cleans the current assets
     */
    public function cleanAssets()
    {
        $this->assets = array();
        $this->assetsFiles = array();
    }

    /**
     * Writes the twig file for the given assets
     * 
     * @param string    $skeletonFile   The skeleton file to use
     * @param string    $outputFile     The output file name
     * @param array     $filters        The filters to use
     */
    public function writeAssetFile($skeletonFile, $outputTwigFile, array $filters = array(), $output = null)
    { 
        if(null === $this->outputBundle) 
        {
            throw new \InvalidArgumentException('Any output bundle has been declared: assets file cannot be written');
        }
        
        $outputFolder = $this->locate($this->outputBundle) . $this->outputFolder;
        
        $this->checkFolder($outputFolder);

        $skeletonFolder = $this->locate($this->container->getParameter('alcms.assets.skeletons_folder'));
        $skeleton = file_get_contents($skeletonFolder . "/" . $skeletonFile);
        
        $normalizedAssets = $this->normalizeAssetsPath($this->assets); 
        $output = (null !== $output) ? "output='$output'" : '';
        $contents = (!empty($normalizedAssets)) ? \sprintf($skeleton, $output, implode(',', $filters), '\'' . implode('\' \'', $normalizedAssets). '\'') : '';
        
        file_put_contents($outputFolder . "/" . $outputTwigFile, $contents);
    }
    
    /**
     * Normalize the assets' path to be used in the assets twig file. The paths which are normalized are:
     * 
     *  @BundleName/Resources/public/[asset]
     *  /full/path/to/resource/Resources/public/[asset]
     * 
     * and converted as
     *  
     *  bundles/bundlenameinlowercase/[asset]
     * 
     * When the rule doesn't match, the given path is returned untouched
     * 
     * @param array     $assets
     * @return array    The normalized paths
     */
    private function normalizeAssetsPath(array $assets)
    {
        $formattedAssets = array();
        foreach($assets as $asset)
        {
            if(trim($asset) != "")
            {
                preg_match('/[@|\/](.*?)\/Resources\/public\/(.*)/', $asset, $matches);
                if(!empty($matches))
                {
                    $bundleName = $matches[1];
                    if(strpos($bundleName, '/') !== false)
                    {
                        $bundleName = \str_replace('/', '', \strrchr($bundleName, '/'));
                    }

                    $path = AlToolkit::retrieveBundleWebFolder($this->container, $bundleName) . "/" . $matches[2];
                }
                else
                {
                    $path = $asset;
                }
                
                $formattedAssets[] = $path;
            }
        }
        
        return $formattedAssets;
    }

    /**
     * Locates a path
     * @param string $path  The path to be located
     * @return string       
     */
    private function locate($path)
    {
        $locatedPath = AlToolkit::locateResource($this->container, $path, true);
        if($locatedPath === false)
        {
            throw new InvalidArgumentException(AlToolkit::translateMessage($this->container, 'I cannot locate the resource [%path%]: it could be caused of a bad configuration on your configuration file.', array('%path%' => $path)));
        }
        
        return $locatedPath;
    }

    /**
     * Creates a folder when it doesn't exists
     *  
     * @param string $folder 
     */
    private function checkFolder($folder)
    {
        if(!is_dir($folder))
        {
            $fs = new Filesystem();
            $fs->mkdir($folder);
        }
    }
}