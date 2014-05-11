<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\PageBlocksInterface;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AssetCollection;

/**
 * TemplateAssetsManagerDeploy is the object deputed to handle website assets when
 * deploying the site
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class TemplateAssetsManagerDeploy extends TemplateAssetsManager
{
    /** @var null|PageBlocksInterface */
    private $pageBlocks = null;

    public function setPageBlocks(PageBlocksInterface $pageBlocks)
    {
        $this->pageBlocks = $pageBlocks;

        return $this;
    }

    protected function mergeAppBlocksAssets(AssetCollection $assetsCollection, array $options)
    {
        $blockTypes = $this->pageBlocks->getBlockTypes();

        // When a block has examined, it is saved in this array to avoid parsing it again
        $appsAssets = array();

        // merges assets from installed apps
        foreach ($this->availableBlocks as $className) {
            if ( ! in_array($className, $blockTypes)) {
                continue;
            }

            if ( ! in_array($className, $appsAssets)) {
                $parameterSchema = '%s.%s_%s';
                $parameter = sprintf($parameterSchema, strtolower($className), $options["type"], $options["assetType"]);
                $this->addAssetsFromContainer($assetsCollection, $parameter);
                $this->addExtraAssets($assetsCollection, $parameter);

                $appsAssets[] = $className;
            }
        }

        return $assetsCollection;
    }
}
