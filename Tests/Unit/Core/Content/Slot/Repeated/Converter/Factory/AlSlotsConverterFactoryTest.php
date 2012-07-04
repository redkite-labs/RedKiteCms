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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Slot\Repeated\Converter\Factory;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactory;

/**
 * AlSlotsConverterFactoryTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlSlotsConverterFactoryTest extends TestCase 
{    
    protected function setUp() 
    {
        parent::setUp();
        
        $this->pageContents = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                           ->disableOriginalConstructor()
                            ->getMock();
        
        $this->languageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->blockModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
    }
    
    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Slot\SameRepeatedStatusException
     */
    public function testCreateConverterThrowsAnExceptionGivingTheSameRepeatedStatus()
    {
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $slotsConverterFactory = new AlSlotsConverterFactory($this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        
        $slotsConverterFactory->createConverter($slot, 'page');
    }
    
    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ClassNotFoundException
     */
    public function testCreateConverterThrowsAnExceptionWhenTheConvertedClassCannotBeInstantiated()
    {
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $slotsConverterFactory = new AlSlotsConverterFactory($this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        
        $slotsConverterFactory->createConverter($slot, 'fake');
    }
    
    public function testConverterHasBeenInstantiated()
    {
        $this->pageContents->expects($this->any())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array()));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $slotsConverterFactory = new AlSlotsConverterFactory($this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        
        $slotsConverterFactory->createConverter($slot, 'site');
    }
}