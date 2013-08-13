<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infblockRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Slot;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Aligner\AlRepeatedSlotsAligner;

/**
 * AlSlotsConverterFactoryTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlRepeatedSlotsAlignerTest extends TestCase
{
    private $themes;
    private $slotsConverterFactory;
    private $blockRepository;
    private $aligner;
    private $theme = null;

    protected function setUp()
    {
        parent::setUp();

        $this->themes = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection');

        $this->slotsConverterFactory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactoryInterface');
        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->at(0))
            ->method('createRepository')
            ->with('Block')
            ->will($this->returnValue($this->blockRepository));

        $this->aligner = new AlRepeatedSlotsAligner($this->themes, $this->slotsConverterFactory, $this->factoryRepository);
    }
    
    public function testAnyOperationIsMadeWhenTheTemplateHasNotBeenChanged()
    {
        $this->assertNull($this->aligner->align("Home", array()));
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
        
        $converter = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterInterface');
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
        
        $slots = $this->initSlots($slotValues);
        $this->initBlocks($blockValues);
        $this->aligner->align("Home", $slots);
    }

    /**
     * @dataProvider valuesProvider
     */
    public function testConvertWhenPageAndLanguageHasNotBeenSetted($slots, $blocks, $convert, $transaction, $result)
    {
        $language = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage');
        $language
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2))
        ;
        
        $languageRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $languageRepository
            ->expects($this->once())
            ->method('mainLanguage')
            ->will($this->returnValue($language))
        ;
        
        $page = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlPage');
        $page
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2))
        ;
        
        $pageRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
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
        
        $this->doTest($slots, $blocks, $convert, $transaction, $result);
    }
    
    /**
     * @dataProvider valuesProvider
     */
    public function testConvertWhenPageAndLanguageHasBeenSetted($slots, $blocks, $convert, $transaction, $result)
    {
        $this->aligner
             ->setLanguageId(2)
             ->setPageId(2);
        
        $this->doTest($slots, $blocks, $convert, $transaction, $result);
    }
    
    private function doTest($slots, $blocks, $convert, $transaction, $result)
    {
        $converter = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterInterface');
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
        
        $slots = $this->initSlots($slots);
        $this->initBlocks($blocks);
        
        $this->assertEquals($result, $this->aligner->align("Home", $slots));
    }
    
    public function valuesProvider()
    {
        return array(
            // The slot repeated status has not changed
            array(
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
            ),
            // The slot repeated status has not changed because convert fails
            array(
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
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
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
            $slot = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot')
                 ->disableOriginalConstructor()
                 ->getMock();
            
            $slot
                ->expects($this->once())
                ->method('getSlotName')
                ->will($this->returnValue($slotValue["slot_name"]))
            ;

            $slot
                ->expects($this->once())
                ->method('getRepeated')
                ->will($this->returnValue($slotValue["repeated"]))
            ;
            
            $slots[$slotValue["slot_name"]] = $slot;            
        }
        
        return $slots;
    }
}
