<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infblockRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Slot;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Aligner\RepeatedSlotsAligner;

/**
 * SlotsConverterFactoryTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class RepeatedSlotsAlignerTest extends TestCase
{
    private $themes;
    private $slotsConverterFactory;
    private $blockRepository;
    private $aligner;
    private $theme = null;

    protected function setUp()
    {
        parent::setUp();

        $this->themes = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\ThemesCollection');
        $this->template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\Template')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->slotsConverterFactory = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\SlotsConverterFactoryInterface');
        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\BlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $this->factoryRepository->expects($this->at(0))
            ->method('createRepository')
            ->with('Block')
            ->will($this->returnValue($this->blockRepository));

        $this->aligner = new RepeatedSlotsAligner($this->themes, $this->slotsConverterFactory, $this->factoryRepository);
    }
    
    /**
     * @dataProvider invalidProvider
     */
    public function testAnyOperationIsMadeWhenTheTemplateHasNotBeenChanged($slots, $templateSlots)
    {
        $this->initTemplate('home', $slots);
        $this->assertNull($this->aligner->align($this->template, $templateSlots));
    }
    
    public function invalidProvider()
    {
        return array(
            array(
                array(),
                array("logo" => $this->initSlot()),
            ),
            array(
                array("logo"),
                array(),
            ),
        );
    }
    
    private function initTemplate($templateName, $slots)
    {
        $this->template->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue($slots))
        ;
        
        $this->template->expects($this->any())
            ->method('getTemplateName')
            ->will($this->returnValue($templateName))
        ;
    }


    public function testProperties()
    {
        $this->assertNull($this->aligner->getLanguageId());
        $this->assertSame($this->aligner, $this->aligner->setLanguageId(2));
        $this->assertEquals(2, $this->aligner->getLanguageId());
        
        $this->assertNull($this->aligner->getPageId());
        $this->assertSame($this->aligner, $this->aligner->setPageId(2));
        $this->assertEquals(2, $this->aligner->getPageId());
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testOperationFailsBecauseConverterThrowsAnUnespectedException()
    {
        $this->aligner
             ->setLanguageId(2)
             ->setPageId(2);
        
        $converter = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\SlotConverterInterface');
        $converter->expects($this->once())
            ->method('convert')
            ->will($this->throwException(new \RuntimeException));    
        
        $this->slotsConverterFactory->expects($this->once())
            ->method('createConverter')
            ->will($this->returnValue($converter));
        
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->never())
            ->method('commit');

        $this->blockRepository->expects($this->once())
            ->method('rollBack');
        
        $slotValues = 
            array(
                array(
                    "slot_name" => "logo", 
                    "repeated" => "site"
                ),
            )
        ;
        
        $blockValues = 
            array(
                array(
                    "slot_name" => "logo", 
                    "language_id" => "1", 
                    "page_id" => "2"
                ),
            )
        ;
        
        $this->initTemplate('home', array('logo'));
        $themeSlots = $this->initSlots($slotValues);
        $this->initBlocks($blockValues);
        $this->aligner->align($this->template, $themeSlots);
    }

    /**
     * @dataProvider valuesProvider
     */
    public function testConvertWhenPageAndLanguageHasNotBeenSetted($slots, $themeSlots, $blocks, $convert, $transaction, $result)
    {
        $language = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Language');
        $language
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2))
        ;
        
        $languageRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\LanguageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $languageRepository
            ->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($language))
        ;
        
        $page = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page');
        $page
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2))
        ;
        
        $pageRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\PageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $pageRepository
            ->expects($this->once())
            ->method('fromTemplateName')
            ->will($this->returnValue($page))
        ;
        
        $this->factoryRepository
             ->expects($this->at(0))
             ->method('createRepository')
             ->with('Language')
             ->will($this->returnValue($languageRepository))
        ;
        
        $this->factoryRepository
             ->expects($this->at(1))
             ->method('createRepository')
             ->with('Page')
             ->will($this->returnValue($pageRepository))
        ;
        
        $this->doTest($slots, $themeSlots, $blocks, $convert, $transaction, $result);
    }
    
    /**
     * @dataProvider valuesProvider
     */
    public function testConvertWhenPageAndLanguageHasBeenSetted($slots, $themeSlots, $blocks, $convert, $transaction, $result)
    {
        $this->aligner
             ->setLanguageId(2)
             ->setPageId(2);
        
        $this->doTest($slots, $themeSlots, $blocks, $convert, $transaction, $result);
    }
    
    private function doTest($slots, $themeSlots, $blocks, $convert, $transaction, $result)
    {
        $converter = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\SlotConverterInterface');
        $converter->expects($this->exactly($convert["convert"]))
            ->method('convert')
            ->will($this->returnValue($convert["result"]));
        
        $this->slotsConverterFactory->expects($this->exactly($convert["create"]))
            ->method('createConverter')
            ->will($this->returnValue($converter));
        
        $this->blockRepository->expects($this->exactly($transaction['start']))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly($transaction['commit']))
            ->method('commit');

        $this->blockRepository->expects($this->exactly($transaction['rollBack']))
            ->method('rollBack');
        
        $this->initTemplate('home', $slots);
        $themeSlots = $this->initSlots($themeSlots);
        $this->initBlocks($blocks);
        
        $this->assertEquals($result, $this->aligner->align($this->template, $themeSlots));
    }
    
    public function valuesProvider()
    {
        return array(
            // The slot repeated status has not changed
            array(
                array(
                    'logo',
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "repeated" => "site"
                    ),
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "language_id" => "1", 
                        "page_id" => "1"
                    ),
                ),
                array(
                    "convert" => 0,
                    "create" => 0,
                    "result" => null,
                ),
                array(
                    "start" => 0,
                    "commit" => 0,
                    "rollBack" => 0,
                ),
                null,
            ),// The slot nav-menu is ignored because it does not belong the current template
            array(
                array(
                    'logo',
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "repeated" => "site"
                    ),
                    array(
                        "slot_name" => "nav-menu", 
                        "repeated" => "language",
                        "times" => 0,
                    ),
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "language_id" => "1", 
                        "page_id" => "1"
                    ),
                ),
                array(
                    "convert" => 0,
                    "create" => 0,
                    "result" => null,
                ),
                array(
                    "start" => 0,
                    "commit" => 0,
                    "rollBack" => 0,
                ),
                null,
            ),
            // The slot repeated status has not changed because convert fails
            array(
                array(
                    'logo',
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "repeated" => "site"
                    ),
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "language_id" => "1", 
                        "page_id" => "2"
                    ),
                ),
                array(
                    "convert" => 1,
                    "create" => 1,
                    "result" => false,
                ),
                array(
                    "start" => 1,
                    "commit" => 0,
                    "rollBack" => 1,
                ),
                false,
            ),
            // The slot repeated status has changed
            array(
                array(
                    'logo',
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "repeated" => "site"
                    ),
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "language_id" => "1", 
                        "page_id" => "2"
                    ),
                ),
                array(
                    "convert" => 1,
                    "create" => 1,
                    "result" => true,
                ),
                array(
                    "start" => 1,
                    "commit" => 1,
                    "rollBack" => 0,
                ),
                true,
            ),
            // The slot repeated status has changed
            array(
                array(
                    'logo',
                    "nav_menu", 
                    "slot_name" => "screenshots", 
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "repeated" => "site"
                    ),
                    array(
                        "slot_name" => "nav_menu", 
                        "repeated" => "language"
                    ),
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "language_id" => "1", 
                        "page_id" => "2"
                    ),
                    array(
                        "slot_name" => "nav_menu", 
                        "language_id" => "2", 
                        "page_id" => "2"
                    ),
                    array(
                        "slot_name" => "screenshots", 
                        "language_id" => "1", 
                        "page_id" => "2"
                    ),
                ),
                array(
                    "convert" => 2,
                    "create" => 2,
                    "result" => true,
                ),
                array(
                    "start" => 1,
                    "commit" => 1,
                    "rollBack" => 0,
                ),
                true,
            ),
            array(
                array(
                    'logo',
                    "nav_menu", 
                    "slot_name" => "screenshots", 
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "repeated" => "site"
                    ),
                    array(
                        "slot_name" => "nav_menu", 
                        "repeated" => "language"
                    ),
                ),
                array(
                    array(
                        "slot_name" => "logo", 
                        "language_id" => "1", 
                        "page_id" => "2"
                    ),
                    array(
                        "slot_name" => "nav_menu", 
                        "language_id" => "2", 
                        "page_id" => "2"
                    ),
                    array(
                        "slot_name" => "screenshots", 
                        "language_id" => "1", 
                        "page_id" => "2"
                    ),
                ),
                array(
                    "convert" => 1,
                    "create" => 1,
                    "result" => false,
                ),
                array(
                    "start" => 1,
                    "commit" => 0,
                    "rollBack" => 1,
                ),
                false,
            ),
        );
    }


    private function initBlocks(array $blockValues)
    {
        $blocks = array();
        foreach ($blockValues as $blockValue) {
            $blocks[] = $this->initBlock($blockValue["slot_name"], $blockValue["language_id"], $blockValue["page_id"]);            
        }
        
        $this->blockRepository
             ->expects($this->once())
             ->method('retrieveContents')
             ->will($this->returnValue($blocks))
        ;
    }
    
    private function initBlock($slotName, $languageId, $pageId)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block
            ->expects($this->any())
            ->method('getSlotName')
            ->will($this->returnValue($slotName))
        ;
        
        $block
            ->expects($this->any())
            ->method('getLanguageId')
            ->will($this->returnValue($languageId))
        ;
        
        $block
            ->expects($this->any())
            ->method('getPageId')
            ->will($this->returnValue($pageId))
        ;
        
        return $block;
    }
    
    private function initSlots(array $slotValues)
    {
        $slots = array();
        foreach ($slotValues as $slotValue) {     
            $times = 1;
            if (array_key_exists("times", $slotValue)) {
                $times = ($slotValue["times"]);
            }
            $slot = $this->initSlot($slotValue["slot_name"], $slotValue["repeated"], $times);
            
            $slots[$slotValue["slot_name"]] = $slot;            
        }
        
        return $slots;
    }
    
    private function initSlot($slotName = "logo", $repeated = "page", $times = 1)
    {
        $slot = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\Slot')
                 ->disableOriginalConstructor()
                 ->getMock();
        
        $slot
            ->expects($this->exactly($times))
            ->method('getSlotName')
            ->will($this->returnValue($slotName))
        ;

        $slot
            ->expects($this->exactly($times))
            ->method('getRepeated')
            ->will($this->returnValue($repeated))
        ;
        
        return $slot;
    }
}
