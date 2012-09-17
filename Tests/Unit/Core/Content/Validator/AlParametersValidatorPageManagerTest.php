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
    private $languageRepository;
    private $pageRepository;

    protected function setUp()
    {
        $this->pageRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface');
        $this->languageRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface');

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->languageRepository, $this->pageRepository));

        $this->validator = new AlParametersValidatorPageManager($this->factoryRepository);
    }

    public function testPageRepositoryInjectedBySetters()
    {
        $pageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->assertEquals($this->validator, $this->validator->setPageRepository($pageRepository));
        $this->assertEquals($pageRepository, $this->validator->getPageRepository());
        $this->assertNotSame($this->validator, $this->validator->getPageRepository());
    }

    public function testHasPagesReturnsFalseWhenAnyLanguageExist()
    {
        $this->pageRepository->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(null));

        $this->assertFalse($this->validator->hasPages());
    }

    public function testHasPagesReturnsTrueWhenAtLeastALanguageExist()
    {
        $this->pageRepository->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array('fake')));

        $this->assertTrue($this->validator->hasPages());
    }

    public function testHasPagesReturnsFalseWhenNumberOfPagesIsNotHigherThanTheMinimunRequired()
    {
        $this->pageRepository->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array('fake')));

        $this->assertFalse($this->validator->hasPages(1));
    }

    public function testHasPagesReturnsTrueWhenNumberOfPagesIsHigherThanTheMinimunRequired()
    {
        $this->pageRepository->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array('fake', 'fake1')));

        $this->assertTrue($this->validator->hasPages(1));
    }

    public function testLanguageExistsReturnsFalseWhenTheRequiredLanguageDoesNotExist()
    {
        $this->pageRepository->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue(null));

        $this->assertFalse($this->validator->pageExists('fake'));
    }

    public function testLanguageExistsReturnsTrueWhenTheRequiredLanguageExists()
    {
        $this->pageRepository->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue(array('fake')));

        $this->assertTrue($this->validator->pageExists('fake'));
    }
}
