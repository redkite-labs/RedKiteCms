<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Validator;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorPageManager;

/**
 * ParametersValidatorLanguagesManager
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ParametersValidatorPagesManager extends TestCase
{
    private $validator;
    private $languageRepository;
    private $pageRepository;

    protected function setUp()
    {
        $this->pageRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface');
        $this->languageRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface');

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->languageRepository, $this->pageRepository));

        $this->validator = new ParametersValidatorPageManager($this->factoryRepository);
    }

    public function testPageRepositoryInjectedBySetters()
    {
        $pageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface')
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
