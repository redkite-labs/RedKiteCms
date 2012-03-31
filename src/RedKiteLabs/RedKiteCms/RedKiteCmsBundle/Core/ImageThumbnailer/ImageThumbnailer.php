<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\ImageThumbnailer;

use Symfony\Component\Filesystem\Filesystem;
use Imagine\Gd\Imagine;
use Imagine\Filter\Transformation;
use Imagine\Image\Box;

/**
 * Description of ImageThumbnailer
 *
 * @author alphalemon
 */
class ImageThumbnailer {
    
    protected $image;
    protected $thumbnailWidth;
    protected $thumbnailHeight;
    private $thumbnailsFolder = '.thumbnails';
    private $thumbnailPath = null;
    private $thumbnailImage = null;


    public function __construct($image, $thumbnailWidth = 100, $thumbnailHeight = 100) {
        $this->image = $image;
        $this->thumbnailWidth = $thumbnailWidth;
        $this->thumbnailHeight = $thumbnailHeight;
        $this->setupTargetPath();
        $this->setupTargetImage();
    }
    
    public function setThumbnailFolder($v)
    {
        $this->thumbnailsFolder = $v;
    }

    public function getThumbnailFolder()
    {
        return $this->thumbnailsFolder;
    }
    
    public function getThumbnailPath()
    {
        return $this->thumbnailPath;
    }
    
    public function getThumbnailImage()
    {
        return $this->thumbnailImage;
    }
    
    public function create()
    {
        $imagine = new Imagine();
        $transformation = new Transformation();
        $transformation->thumbnail(new Box($this->thumbnailWidth, $this->thumbnailHeight));
        $transformation->apply($imagine->open($this->image))
                ->save($this->thumbnailImage);
    }
    
    protected function setupTargetPath()
    {
        $this->thumbnailPath = dirname($this->image) . '/' . $this->thumbnailsFolder . '/';
        
        if(!is_dir($this->thumbnailPath))
        {
            $filesystem = new Filesystem();
            $filesystem->mkdir($this->thumbnailPath);
        }
    }
    
    protected function setupTargetImage()
    {
        $this->thumbnailImage = $this->thumbnailPath . md5($this->image) . '.jpg';
    }
}