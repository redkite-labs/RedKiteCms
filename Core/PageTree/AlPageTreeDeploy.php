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

namespace RedKiteLabs\RedKiteCmsBundle\Core\PageTree;

/**
 * {@inheritdoc}
 *
 * The AlPageTree object used when deploying the website
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlPageTreeDeploy extends AlPageTree
{
    protected function mergeAppBlocksAssets($assetsCollection, $type, $assetType)
    {
        $blockTypes = $this->pageBlocks->getBlockTypes();
            
        // When a block has examined, it is saved in this array to avoid parsing it again
        $appsAssets = array();

        // merges assets from installed apps
        $availableBlocks = $this->blockManagerFactory->getAvailableBlocks();
        foreach ($availableBlocks as $className) {
            if ( !in_array($className, $blockTypes)) {
                continue;
            }

            if ( ! in_array($className, $appsAssets)) {                    
                $parameterSchema = '%s.%s_%s';
                $parameter = sprintf($parameterSchema, strtolower($className), $type, $assetType);
                $this->addAssetsFromContainer($assetsCollection, $parameter);
                $this->addExtraAssets($assetsCollection, $parameter);

                $appsAssets[] = $className;
            }
        }
    }
}