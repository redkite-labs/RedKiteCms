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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Media;

/**
 * AlMediaExt
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
abstract class AlMediaExt extends AlMedia
{
    protected $width;
    protected $height;
    protected $imageType;
    protected $dimensions;

    public function __construct($container, $src, $options = array())
    {
        parent::__construct($container, $src, $options);

        $this->retrieveImageSize();
    }

    protected function retrieveImageSize()
    {
        list($this->width, $this->height, $this->imageType, $this->dimensions) = getimagesize($this->realSrcPath);
    }

    public function fit($width, $height)
    {
        $reducedDimensions = $this->calulateCanvas($width, $height); 
        $this->width = $reducedDimensions["width"];
        $this->height = $reducedDimensions["height"];

        return $this;
    }

    /**
     * Calculates new image dimensions and relative canvas value to fit an image into a
     * static window
     *
     * @param      int The rectangle width to fit the media in.
     * @param      int The rectangle height to fit the media in.
     *
     * @return     array The resized width, the resized height and the calculated canvas
     */
    protected function calulateCanvas($width, $height)
    {

        if($width == null || $width == 0)
        {
            throw new RuntimeException('The width of the preview window cannot be null or zero.');
        }

        if($height == null || $height == 0)
        {
            throw new RuntimeException('The height of the preview window cannot be null or zero.');
        }

        // Set the canvas to maximum and the picture resized dimensions to picture dimensions
        $canvas = 100;
        $resizedWidth = $this->width;
        $resizedHeight = $this->height;

        // Calculate the max side for resizing
        $diffPreviewWidth = $this->width - $width;
        $diffPreviewHeight = $this->height - $height;
        $max = max($diffPreviewWidth, $diffPreviewHeight);

        // If the max value is not negative we have to resize
        if ($max == abs($max))
        {
            if ($max == $diffPreviewWidth)
            {
                // The max side is the width side
                $canvas = $width/$this->width;
                $resizedHeight = intval($this->height * $canvas);
                $resizedWidth = ($diffPreviewWidth > 0) ? $this->width * $canvas : $this->width;
            }
            elseif($max == $diffPreviewHeight)
            {
                // The max side is the height side
                $canvas = $height/$this->height;
                $resizedWidth = intval($this->width * $canvas);
                $resizedHeight = ($diffPreviewHeight > 0) ? $this->height * $canvas : $this->height;
            }
        }
        if ($canvas != 100) $canvas = intval($canvas * 100);

        return array("width" => $resizedWidth,
                     "height" => $resizedHeight,
                     "value" => $canvas);
    }
}