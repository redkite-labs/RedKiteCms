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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Slot;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Alaligner;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
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
    private $templateSlotsFactory;
    private $slotsConverterFactory;
    private $orm;
    private $aligner;

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

        $this->templateSlotsFactory = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsFactoryInterface');
        $this->slotsConverterFactory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactoryInterface');
        $this->orm = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\Base\AlPropelOrm');
        $this->aligner = new AlRepeatedSlotsAligner($this->kernel, $this->templateSlotsFactory, $this->slotsConverterFactory, $this->orm);
        $this->aligner
             ->setSkeletonFile(vfsStream::url('root/xml/repeated-slots-skeleton.xml'))
             ->setCacheFile($this->cacheFile);
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

        $templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $templateSlots->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue(array(new AlSlot('logo', array('repeated' => 'page')), new AlSlot('logo', array('nav-menu' => 'page')))));

        $this->templateSlotsFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($templateSlots));

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

        $this->templateSlotsFactory->expects($this->never())
            ->method('create');

        $this->assertNull($this->aligner->align("BusinessWebsiteThemeBundle", "Home", array()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAlignmentFailsWhenConvertThrowsAnUnexpectedException()
    {
        $this->orm->expects($this->once())
            ->method('startTransaction');

        $this->orm->expects($this->once())
            ->method('rollBack');

        $templateSlots = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterInterface');
        $templateSlots->expects($this->once())
            ->method('convert')
            ->will($this->throwException(new \RuntimeException));

        $this->doAlignToFail($templateSlots);
    }

    public function testAlignmentFailsWhenConvertFails()
    {
        $this->orm->expects($this->once())
            ->method('startTransaction');

        $this->orm->expects($this->once())
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

        $this->orm->expects($this->once())
            ->method('startTransaction');

        $this->orm->expects($this->once())
            ->method('commit');

        $this->orm->expects($this->never())
            ->method('rollBack');

        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root/FakeTheme')));

        $slots = array('logo' => new AlSlot('logo', array('repeated' => 'site')));
        $templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $templateSlots->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue($slots));

        $this->templateSlotsFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($templateSlots));

        $templateSlots = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterInterface');
        $templateSlots->expects($this->once())
            ->method('convert')
            ->will($this->returnValue(true));

        $this->slotsConverterFactory->expects($this->once())
            ->method('createConverter')
            ->will($this->returnValue($templateSlots));

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
}