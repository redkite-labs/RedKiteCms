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
 * @codeCoverageIgnore
 */
class AlImageThumbnailer
{
    protected $imagine;
    protected $transformation;
    private $thumbnailsFolder = '.thumbnails';
    private $thumbnailPath = null;
    private $thumbnailImage = null;
    private $imageSize = null;

    /**
     * Constructor
     *
     * @param string  $image
     * @param int     $thumbnailWidth
     * @param int     $thumbnailHeight
     * @param Imagine $imagine
     */
    public function __construct(Image\ImagineInterface $imagine = null, Transformation $transformation = null)
    {
        $this->imagine = (null === $imagine) ? new Imagine() : $imagine;
        $this->transformation = (null === $transformation) ? new Transformation() : $transformation;
    }

    /**
     * Sets the thumbnails folder
     * 
     * @param string $v
     */
    public function setThumbnailFolder($v)
    {
        $this->thumbnailsFolder = $v;
    }

    /**
     * Returns the thumbnails folder
     * 
     * @return string
     */
    public function getThumbnailFolder()
    {
        return $this->thumbnailsFolder;
    }

    /**
     * Returns the thumbnails folder
     * 
     * @return string
     */
    public function getThumbnailPath()
    {
        return $this->thumbnailPath;
    }

    /**
     * Returns the thumbnail image
     * 
     * @return string
     */
    public function getThumbnailImage()
    {
        return $this->thumbnailImage;
    }

    /**
     * Returns the thumbnail image name
     * 
     * @return string
     */
    public function getThumbnailImageName()
    {
        return (null !== $this->thumbnailImage) ? basename($this->thumbnailImage) : '';
    }

    /**
     * Returns the thumbnail width
     * 
     * @return int
     */
    public function getThumbnailWidth()
    {
        return $this->imageSize[0];
    }

    /**
     * Returns the thumbnail height
     * 
     * @return int
     */
    public function getThumbnailHeight()
    {
        return $this->imageSize[1];
    }

    /**
     * Creates the thumbnail
     *
     * @param string $image
     * @param int    $thumbnailWidth
     * @param int    $thumbnailHeight
     */
    public function create($image, $thumbnailWidth = 100, $thumbnailHeight = 100)
    {
        $this->setupTargetPath($image);
        $this->setupTargetImage($image);

        $this->transformation->thumbnail(new Image\Box($thumbnailWidth, $thumbnailHeight))
                             ->apply($this->imagine->open($image))
                             ->save($this->thumbnailImage);

        $this->imageSize = getimagesize($this->thumbnailImage);
    }

    /**
     * Sets up the target path and creates it if it does not exist
     */
    protected function setupTargetPath($image)
    {
        $this->thumbnailPath = dirname($image) . '/' . $this->thumbnailsFolder . '/';
        if (!is_dir($this->thumbnailPath)) {
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
