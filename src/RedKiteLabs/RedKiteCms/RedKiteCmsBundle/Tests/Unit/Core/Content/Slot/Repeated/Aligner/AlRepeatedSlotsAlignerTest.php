<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infblockRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Slot;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Aligner\AlRepeatedSlotsAligner;
use org\bovigo\vfs\vfsStream;

/**
 * AlSlotsConverterFactoryTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlRepeatedSlotsAlignerTest extends TestCase
{
    private $kernel;
    private $root;
    private $cacheFile;
    private $themes;
    private $slotsConverterFactory;
    private $blockRepository;
    private $aligner;
    private $theme = null;

    protected function setUp()
    {
        parent::setUp();

        $structure =
        array('FakeTheme' =>
                array('Core' =>
                    array('Slots' =>
                        array('BusinessWebsiteThemeBundleHomeSlots.php'    => ''),
                    ),
                ),
        );

        $this->root = vfsStream::setup('root', null, $structure);
        vfsStream::copyFromFileSystem(__DIR__ . '/../../../../../../../Resources/data/xml', vfsStream::newDirectory('xml')->at($this->root));
        $this->cacheFile = vfsStream::url('root/cache.xml');

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->kernel->expects($this->any())
            ->method('getRootDir')
            ->will($this->returnValue(vfsStream::url('root')));

        $this->themes = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection');

        $this->slotsConverterFactory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactoryInterface');
        $this->blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->aligner = new AlRepeatedSlotsAligner($this->kernel, $this->themes, $this->slotsConverterFactory, $this->factoryRepository);
        $this->aligner
             ->setSkeletonFile(vfsStream::url('root/xml/repeated-slots-skeleton.xml'))
             ->setCacheFile($this->cacheFile);
    }
    
    public function testGetCacheFile()
    {
        $this->assertEquals('vfs://root/cache.xml', $this->aligner->getCacheFile());
    }
    
    public function testGetCacheFileIsRereatedWhenItIsMalformed()
    {
        $this->setUpTheme();
        file_put_contents(vfsStream::url('root/cache.xml'), 'no xml data');
        $this->assertTrue($this->aligner->align("BusinessWebsiteThemeBundle", "Home", array()));
    }

    public function testSkeletonFileIsNotAssignedWhenTheGivenFileDoesNotExist()
    {
        $fakeSkeletonFile = vfsStream::url('root/fake.xml');
        $this->aligner->setSkeletonFile($fakeSkeletonFile);
        $this->assertNotEquals($fakeSkeletonFile, $this->aligner->getSkeletonFile());
    }

    public function testSkeletonFileIsNotAssignedWhenTheGivenFileIsEmpty()
    {
        vfsStream::newFile('fake.xml')->at($this->root);
        $fakeFile = vfsStream::url('root/fake.xml');
        $this->aligner->setSkeletonFile($fakeFile);
        $this->assertNotEquals($fakeFile, $this->aligner->getSkeletonFile());
    }

    public function testSkeletonFileIsNotAssignedWhenTheGivenFileIsNotAnXmlFile()
    {
        $fakeFile = vfsStream::url('root/fake.xml');
        vfsStream::newFile('fake.xml')->at($this->root);
        file_put_contents($fakeFile, "fake");
        $this->aligner->setSkeletonFile($fakeFile);
        $this->assertNotEquals($fakeFile, $this->aligner->getSkeletonFile());
    }

    public function testSkeletonFileAssignedWhenTheGivenFileIsAnXmlFile()
    {
        vfsStream::newFile('fake.xml')->at($this->root);
        $fakeFile = vfsStream::url('root/fake.xml');

        $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $contents .= "<theme>";
        $contents .= "    <templates></templates>";
        $contents .= "</theme>";

        file_put_contents($fakeFile, $contents);
        $this->aligner->setSkeletonFile($fakeFile);
        $this->assertEquals($fakeFile, $this->aligner->getSkeletonFile());
    }

    public function testCacheFileIsFilledUpWhenItDoesNotExist()
    {
        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root/FakeTheme')));

        $this->setUpTheme();
        
        $this->assertFalse(file_exists($this->cacheFile));
        $this->aligner->align("BusinessWebsiteThemeBundle", "Home", array());
        $this->assertTrue(file_exists($this->cacheFile));

        $contents = file_get_contents($this->cacheFile);
        $this->assertTrue(strpos($contents, '<templates><template name="home"><slot name="logo">page</slot></template></templates>') > 0);
    }

    public function testAnyOperationIsMadeWhenTheTemplateHasNotBeenChanged()
    {
        $this->addCacheFile();

        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root/FakeTheme')));

        $this->assertNull($this->aligner->align("BusinessWebsiteThemeBundle", "Home", array()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAlignmentFailsWhenConvertThrowsAnUnexpectedException()
    {
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $templateSlots = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterInterface');
        $templateSlots->expects($this->once())
            ->method('convert')
            ->will($this->throwException(new \RuntimeException));

        $this->doAlignToFail($templateSlots);
    }

    public function testAlignmentFailsWhenConvertFails()
    {
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');

        $templateSlots = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterInterface');
        $templateSlots->expects($this->once())
            ->method('convert')
            ->will($this->returnValue(false));

        $this->doAlignToFail($templateSlots);
    }

    private function doAlignToFail($templateSlots)
    {
        $this->addCacheFile();

        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root/FakeTheme')));

        $this->slotsConverterFactory->expects($this->once())
            ->method('createConverter')
            ->will($this->returnValue($templateSlots));

        $this->aligner->align("BusinessWebsiteThemeBundle", "Home", $slots = array('logo' => new AlSlot('logo', array('repeated' => 'site'))));
        $this->assertTrue(file_exists($this->cacheFile));

        $contents = file_get_contents($this->cacheFile);
        $this->assertTrue(strpos($contents, "<templates><template name=\"home\"><slot name=\"logo\">page</slot><slot name=\"nav-menu\">page</slot></template></templates>") > 0);
    }

    public function testAlignmentSucceded()
    {
        $this->addCacheFile();

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollBack');

        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root/FakeTheme')));

        $slots = array('logo' => new AlSlot('logo', array('repeated' => 'site')));
        $templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $templateSlots->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue($slots));
        $this->setUpTheme($templateSlots);

        $slotsConverter = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterInterface');
        $slotsConverter->expects($this->once())
            ->method('convert')
            ->will($this->returnValue(true));

        $this->slotsConverterFactory->expects($this->once())
            ->method('createConverter')
            ->will($this->returnValue($slotsConverter));

        $this->aligner->align("BusinessWebsiteThemeBundle", "Home", $slots);
        $this->assertTrue(file_exists($this->cacheFile));

        $contents = file_get_contents($this->cacheFile);
        $this->assertTrue(strpos($contents, '<templates><template name="home"><slot name="logo">site</slot></template></templates>') > 0);
    }

    private function addCacheFile()
    {
        $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $contents .= "<theme>";
        $contents .= "    <templates><template name=\"home\"><slot name=\"logo\">page</slot><slot name=\"nav-menu\">page</slot></template></templates>";
        $contents .= "</theme>";

        vfsStream::newFile('cache.xml')->at($this->root);
        file_put_contents(vfsStream::url('root/cache.xml'), $contents);
    }

    private function setUpTheme($templateSlots = null)
    {
        if (null === $templateSlots) {
            $templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
            $templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array(new AlSlot('logo', array('repeated' => 'page')), new AlSlot('logo', array('nav-menu' => 'page')))));
        }

        $template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                                ->disableOriginalConstructor()
                                ->getMock();

        $template->expects($this->once())
            ->method('getTemplateSlots')
            ->will($this->returnValue($templateSlots));

        $this->theme = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\Theme\AlThemeInterface');
        $this->theme->expects($this->once())
            ->method('getTemplate')
            ->will($this->returnValue($template));

        $this->themes->expects($this->any())
            ->method('getTheme')
            ->will($this->returnValue($this->theme));
    }
}
