<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
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
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\PageTree\TemplateAssetsManager;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetCollection;

/**
 * TemplateAssetsManagerBase
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class TemplateAssetsManagerBase extends TestCase
{
    protected $container;
    
    
    protected function initBlockManagerFactory($availableBlocks)
    {
        $blockManagerFactory = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        
        $blockManagerFactory->expects($this->once())
            ->method('getAvailableBlocks')
            ->will($this->returnValue($availableBlocks))
        ;
        
        return $blockManagerFactory;
    }

    protected function initContainer($listenersAssets, $containerAssets)
    {
        $container = $this->getMock("Symfony\Component\DependencyInjection\ContainerInterface");        
        $container->expects($this->at(0))
            ->method('get')
            ->with('red_kite_labs_theme_engine.registed_listeners')
            ->will($this->returnValue($this->createListenersCollection($listenersAssets)))
        ;
        
        $at = 1;
        foreach($containerAssets as $assetsCollectionMethod => $assets) {
            foreach($assets as $parameter => $parameterAssets) {
                $container->expects($this->at($at))
                    ->method('hasParameter')
                    //->with($parameter)
                    ->will($this->returnValue(true))
                ;
                $at++;
                
                $container->expects($this->at($at))
                    ->method('getParameter')
                    //->with($parameter)
                    ->will($this->returnValue($parameterAssets))
                ;
                $at++;
            }
        }
        
        return $container;
    }
    
    protected function createListenersCollection($assets)
    {
        $listenersCollection = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Rendering\Compiler\ThemeEngineListenersCollection\AlThemeEngineListenersCollection')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        
        $at = 1;
        foreach($assets as $asset) {
            $listenersCollection->expects($this->at($at))
                 ->method('valid')
                 ->will($this->returnValue(true));
            $at++;
            
            $listenersCollection->expects($this->at($at))
                 ->method('current')
                 ->will($this->returnValue($asset));
            $at++;
        }
        
        return $listenersCollection;
    }
    
    protected function createAssetsCollection($assets)
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');        
        $assetsCollection = new AlAssetCollection($kernel, $assets);
        
        return $assetsCollection;
    }
    
    protected function createAsset($file)
    {
        $asset = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAsset')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        
        $asset->expects($this->once())
            ->method('getAbsolutePath')
            ->will($this->returnValue('/asset/absolute/path'))
        ;
        
        $asset->expects($this->once())
            ->method('getAsset')
            ->will($this->returnValue($file))
        ;
        
        return $asset;
    }
    
    protected function createTemplate($assetCollections)
    {
        $template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                    ->setMethods(array('getExternalStylesheets', 'getInternalStylesheets', 'getExternalJavascripts', 'getInternalJavascripts', 'getThemeName', 'getTemplateName', 'getSlots'))
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        
        $at = 0;
        foreach($assetCollections as $method => $assetCollection) {
            $template->expects($this->at($at))
                ->method($method)
                ->will($this->returnValue($assetCollection))
            ;
            
            $at += 3;
        }
        
        $template->expects($this->any())
            ->method("getThemeName")
            ->will($this->returnValue("BootbusinessThemeBundle"))
        ;
        
        $template->expects($this->any())
            ->method("getTemplateName")
            ->will($this->returnValue("home"))
        ;
        
        return $template;
    }
    
    protected function createBlock($slotName)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlBlock');
        
        $block->expects($this->once())
            ->method('getSlotName')
            ->will($this->returnValue($slotName))
        ;
        
        return $block;
    }
}