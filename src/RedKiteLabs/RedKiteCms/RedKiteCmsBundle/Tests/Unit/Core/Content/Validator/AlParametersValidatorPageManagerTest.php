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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Validator;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager;

/**
 * AlParametersValidatorLanguagesManager
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlParametersValidatorPagesManager extends TestCase 
{    
    private $validator;
    private $languageModel;
    private $pageModel;
    
    protected function setUp() 
    {
        $this->languageModel = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface');
        $this->pageModel = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface');        
        $this->validator = new AlParametersValidatorPageManager($this->languageModel, $this->pageModel);
    }
    
    public function testHasPagesReturnsFalseWhenAnyLanguageExist()
    {
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(null));
        
        $this->assertFalse($this->validator->hasPages());
    }
    
    public function testHasPagesReturnsTrueWhenAtLeastALanguageExist()
    {
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array('fake')));
        
        $this->assertTrue($this->validator->hasPages());
    }
    
    public function testHasPagesReturnsFalseWhenNumberOfPagesIsNotHigherThanTheMinimunRequired()
    {
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array('fake')));
        
        $this->assertFalse($this->validator->hasPages(1));
    }
    
    public function testHasPagesReturnsTrueWhenNumberOfPagesIsHigherThanTheMinimunRequired()
    {
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array('fake', 'fake1')));
        
        $this->assertTrue($this->validator->hasPages(1));
    }
    
    public function testLanguageExistsReturnsFalseWhenTheRequiredLanguageDoesNotExist()
    {
        $this->pageModel->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue(null));
        
        $this->assertFalse($this->validator->pageExists('fake'));
    }
    
    public function testLanguageExistsReturnsTrueWhenTheRequiredLanguageExists()
    {
        $this->pageModel->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue(array('fake')));
        
        $this->assertTrue($this->validator->pageExists('fake'));
    }
}