<?php

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\ImagesBlock;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerContainer;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\BlockManagerJsonBlock;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\AssetsPath\AssetsPath;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\General\RuntimeException;

/**
 * BlockManagerImages is the base object deputated to handle a content made by a list
 * of images, like a slider or an image gallery
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BlockManagerImages extends BlockManagerContainer
{
    /**
     * {@inheritdoc}
     *
     * Extends the base edit method to manage an images list
     *
     * @api
     */
    protected function edit(array $values)
    {
        if (array_key_exists('AddFile', $values)) {
            $values["content"] = $this->addImage($values);
        }

        if (array_key_exists('RemoveFile', $values)) {
            $values["content"] = $this->removeImage($values);
        }

        //$values["Content"] = $this->arrangeImages($values);
        return $this->doBaseEdit($values);
    }

    protected function doBaseEdit(array $values)
    {
        return parent::edit($values);
    }

    private function addImage($values)
    {
        $images = $this->decodeImages();
        $savedImages = $this->fetchImagesBySrcAttribute($images);

        $file = $values["AddFile"];
        $imageFile = "/" . AssetsPath::getUploadFolder($this->container) . "/" . preg_replace('/http?:\/\/[^\/]+/', '', $file);

        if (in_array($imageFile, $savedImages)) {
            throw new RuntimeException('exception_file_already_added');
        }

        $images[]['image'] = $imageFile;

        return json_encode($images);
    }

    private function removeImage($values)
    {
        $images = $this->decodeImages();
        $savedImages = $this->fetchImagesBySrcAttribute($images);

        $fileToRemove = $values["RemoveFile"];
        $key = array_search($fileToRemove, $savedImages);
        if (false !== $key) {
            unset($images[$key]);
        }

        return json_encode($images);
    }

    private function decodeImages()
    {
        return BlockManagerJsonBlock::decodeJsonContent($this->alBlock);
    }

    private function fetchImagesBySrcAttribute($images)
    {
        $savedImages = array_map(function ($el) { return (array_key_exists('src', $el)) ? $el['src'] : ''; }, $images);

        return $savedImages;
    }

    /*
    protected function arrangeImages(array $values)
    {
        if ( ! array_key_exists('Content', $values)) {
            $images = BlockManagerJsonBlock::decodeJsonContent($this->alBlock);
            $savedImages = array_map(function ($el) { return (array_key_exists('src', $el)) ? $el['src'] : ''; }, $images);

            if (array_key_exists('AddFile', $values)) {
                $file = $values["AddFile"];
                $imageFile = "/" . AssetsPath::getUploadFolder($this->container) . "/" . preg_replace('/http?:\/\/[^\/]+/', '', $file);

                if (in_array($imageFile, $savedImages)) {
                    throw new RuntimeException('exception_file_already_added');
                }

                $images[]['image'] = $imageFile;
            }

            if (array_key_exists('RemoveFile', $values)) {
                $fileToRemove = $values["RemoveFile"];
                $key = array_search($fileToRemove, $savedImages);
                if (false !== $key) {
                    unset($images[$key]);
                }
            }

            return json_encode($images);
        }

        return $values['Content'];
    }*/
}
