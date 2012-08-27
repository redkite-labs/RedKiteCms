<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\ImageThumbnailer;

use Symfony\Component\Filesystem\Filesystem;
use Imagine\Gd\Imagine;
use Imagine\Filter\Transformation;
use Imagine\Image;

/**
 * ImageThumbnailer creates the thumbnail of the given image
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlImageThumbnailer
{
    protected $imagine;
    protected $transformation;
    private $thumbnailsFolder = '.thumbnails';
    private $thumbnailPath = null;
    private $thumbnailImage = null;

    /**
     * Constructor
     *
     * @param string $image
     * @param int $thumbnailWidth
     * @param int $thumbnailHeight
     * @param Imagine $imagine
     */
    public function __construct(Image\ImagineInterface $imagine = null, Transformation $transformation = null)
    {
        $this->imagine = (null === $imagine) ? new Imagine() : $imagine;
        $this->transformation = (null === $transformation) ? new Transformation() : $transformation;
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

    /**
     * Creates the thumbnail
     *
     * @param string $image
     * @param int $thumbnailWidth
     * @param int $thumbnailHeight
     */
    public function create($image, $thumbnailWidth = 100, $thumbnailHeight = 100)
    {
        $this->setupTargetPath($image);
        $this->setupTargetImage($image);

        $this->transformation->thumbnail(new Image\Box($thumbnailWidth, $thumbnailHeight))
                             ->apply($this->imagine->open($image))
                             ->save($this->thumbnailImage);
    }

    /**
     * Sets up the target path and creates it if it does not exist
     */
    protected function setupTargetPath($image)
    {
        $this->thumbnailPath = dirname($image) . '/' . $this->thumbnailsFolder . '/';
        if(!is_dir($this->thumbnailPath))
        {
            $filesystem = new Filesystem();
            $filesystem->mkdir($this->thumbnailPath);
        }
    }

    /**
     * Sets up the target image name
     */
    protected function setupTargetImage($image)
    {
        $this->thumbnailImage = $this->thumbnailPath . md5($image) . '.jpg';
    }
}