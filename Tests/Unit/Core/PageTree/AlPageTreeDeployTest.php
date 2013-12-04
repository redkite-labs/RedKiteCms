<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\PageTree;

use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTreeDeploy;

/**
 * AlPageTreeTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlPageTreeDeployTest extends AlPageTreeTest
{
    /**
     * @dataProvider fetchAssets
     */
    public function testPageTreeSetsUpExternalAssetsFromABlock($availableBlocks, $blocksAssets, $result, $externalListeners = array(), $orphanSlots = array())
    {        
        $this->initValidPageTree();
        
        $this->blocksManagerFactory->expects($this->once())
            ->method('getAvailableBlocks')
            ->will($this->returnValue($availableBlocks));
        
        $listeners = array();
        $listenerAssets = array();
        if ( ! empty($externalListeners)) {
            $listeners = $externalListeners['listener'];
            $listenerAssets = $externalListeners['assets'];
            
            $this->language->expects($this->once())
                ->method('getLanguageName')
                ->will($this->returnValue($externalListeners['language']))
            ;
            
            $this->page->expects($this->once())
                ->method('getPageName')
                ->will($this->returnValue($externalListeners['page']))
            ;
        }
        
        $this->container->expects($this->at(5))
            ->method('get')
            ->with('red_kite_labs_theme_engine.registed_listeners')
            ->will($this->returnValue($listeners));
        
        $startIndex = 6;
        $this->checkAssets($listenerAssets, $startIndex, true);        
        $this->checkAssets($blocksAssets, $startIndex);
        
        if ( ! empty($orphanSlots)) {            
            $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue($orphanSlots['blocks']));
            
            $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($orphanSlots['slots'])); //
        }

        $themeAssets = array('theme-stylesheet.css');
        $this->setUpAssetsCollection($themeAssets);
        
        $this->pageBlocks->expects($this->once())
            ->method('getBlockTypes')
            ->will($this->returnValue(array('Text', 'Image')));

        $pageTree = new AlPageTreeDeploy($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($result, $pageTree->getExternalStylesheets());
    }
    
    protected function checkAssets($assets, &$startIndex, $ignoreCms = false)
    {
        foreach($assets as $parameter => $asset) {            
            $globalAsset = $asset['global']; 
            if (array_key_exists('skip', $globalAsset)) {
                continue;
            }
            
            $assetDeclared = $globalAsset['exists'];
            $this->container->expects($this->at($startIndex))
                ->method('hasParameter')
                ->with($parameter)
                ->will($this->returnValue($assetDeclared));
            
            $startIndex++;
            if ($assetDeclared) {
                $this->container->expects($this->at($startIndex))
                    ->method('getParameter')
                    ->with($parameter)
                    ->will($this->returnValue($globalAsset['assets']));
                $startIndex++;
            }
            
            if ( ! $ignoreCms) {
                if (array_key_exists('cms', $asset)) {
                    $parameter .= '.cms';
                    $cmsAsset = $asset['cms']; 
                    $assetDeclared = $cmsAsset['exists'];
                    $this->container->expects($this->at($startIndex))
                        ->method('hasParameter')
                        ->with($parameter)
                        ->will($this->returnValue($assetDeclared));

                    $startIndex++;
                    if ($assetDeclared) {
                        $this->container->expects($this->at($startIndex))
                            ->method('getParameter')
                            ->with($parameter)
                            ->will($this->returnValue($cmsAsset['assets']));
                        $startIndex++;
                    }
                } else {
                    $startIndex++;
                }
            }
        }
    }
    
    public function fetchAssets()
    {        
        $newtest = array(
            array(
                array('Image', 'Text', 'Script'),
                array(
                    'image.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css', 
                                'image-stylesheet-1.css',
                            ),
                        ),
                    ),
                    'text.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'text-stylesheet.css',
                            ),
                        ),
                    ),
                    'script.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'skip' => true,
                            'assets' => array(
                                'script-stylesheet.css',
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css', 
                    'image-stylesheet-1.css',
                    'text-stylesheet.css',
                ),
            )
        );
        
        return array_merge(parent::fetchAssets(), $newtest);
    }
}