<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Twig;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Twig\ImageThumbnailExtension;
use org\bovigo\vfs\vfsStream;

/**
 * ImageThumbnailExtensionTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class ImageThumbnailExtensionTest extends TestCase
{
    private $container;
    private $thumbnailExtension;

    protected function setUp()
    {
        $structure =
            array(
                'app' => array(),
                'web' => array('images' => array('logo.png' => '')),
            );

        $this->root = vfsStream::setup('root', null, $structure);

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->thumbnailer = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\ImageThumbnailer\AlImageThumbnailer')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->thumbnailExtension = new ImageThumbnailExtension($this->container);
    }

    public function testTwigFunctions()
    {
        $functions = array(
            "thumbnail",
        );
        $this->assertEquals($functions, array_keys($this->thumbnailExtension->getFunctions()));
    }

    public function testNameExtension()
    {
        $this->assertEquals('images', $this->thumbnailExtension->getName());
    }

    public function testThumbnailExtensionReturnsNullWhenImageDoesNotExist()
    {
        $this->setUpContainer();
        $this->assertNull($this->thumbnailExtension->thumbnail('fake.png'));
    }

    public function testThumbnailImageIsCreatedUsingThePredefinedDimensions()
    {
        $this->setUpContainer();
        $this->setUpThumbnailer();

        $this->assertEquals('<img src="/images/.thumbnails/logo.png" width="100" height="100" rel="/images/logo.png" />', $this->thumbnailExtension->thumbnail('/images/logo.png'));
    }


    public function testThumbnailImageIsCreatedSpecifingTheThumbnailDimension()
    {
        $this->setUpContainer();
        $this->setUpThumbnailer(70, 90);

        $this->assertEquals('<img src="/images/.thumbnails/logo.png" width="70" height="90" rel="/images/logo.png" />', $this->thumbnailExtension->thumbnail('/images/logo.png'));
    }

    private function setUpContainer()
    {
        $this->container->expects($this->exactly(2))
                        ->method('getParameter')
                        ->will($this->onConsecutiveCalls(vfsStream::url('root/app'), 'web'));


    }

    private function setUpThumbnailer($width = 100, $height = 100)
    {
        $this->thumbnailer->expects($this->once())
                          ->method('create');

        $this->thumbnailer->expects($this->once())
                          ->method('getThumbnailFolder')
                          ->will($this->returnValue('.thumbnails'));

        $this->thumbnailer->expects($this->once())
                          ->method('getThumbnailImageName')
                          ->will($this->returnValue('logo.png'));

        $this->thumbnailer->expects($this->once())
                          ->method('getThumbnailWidth')
                          ->will($this->returnValue($width));

        $this->thumbnailer->expects($this->once())
                          ->method('getThumbnailHeight')
                          ->will($this->returnValue($height));

        $this->container->expects($this->once())
                        ->method('get')
                        ->will($this->returnValue($this->thumbnailer));
    }
}
