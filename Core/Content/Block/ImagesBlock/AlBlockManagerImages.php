<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\ImagesBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerContainer;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;

/**
 * AlBlockManagerImages manages a content made by a serie of images
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlBlockManagerImages extends AlBlockManagerContainer
{
    protected function edit(array $values)
    {
        $images = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock);
        $savedImages = array_map(function($el){ return $el['image']; }, $images);

        if (array_key_exists('AddFile', $values)) {
            $file = $values["AddFile"];

            $imageFile = $this->container->getParameter('alpha_lemon_cms.upload_assets_absolute_path') . "/" . preg_replace('/http?:\/\/[^\/]+/', '', $file);
            if (in_array($imageFile, $savedImages)) {
                throw new \Exception("The image file has already been added");
            }

            $images[]['image'] = $imageFile;
        }

        if (array_key_exists('RemoveFile', $values)) {
            $fileToRemove = $values["RemoveFile"];
            $file = $this->container->getParameter('alpha_lemon_cms.web_folder_full_path') . $fileToRemove;

            $key = array_search($fileToRemove, $savedImages);
            if (false !== $key)  unset($images[$key]);
        }

        $values["Content"] = json_encode($images);

        return parent::edit($values);
    }
}
