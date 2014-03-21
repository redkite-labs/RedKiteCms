<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorLanguageManager;

/**
 * ParametersValidatorLanguagesManager
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ParametersValidatorLanguagesManager extends TestCase
{
    private $validator;
    private $languageRepository;

    protected function setUp()
    {
        $this->languageRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface');

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->languageRepository));

        $this->validator = new ParametersValidatorLanguageManager($this->factoryRepository);
    }
    
    public function testLanguageRepositoryInjectedBySetters()
    {
        $languageRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface');
        $this->assertEquals($this->validator, $this->validator->setLanguageRepository($languageRepository));
        $this->assertEquals($languageRepository, $this->validator->getLanguageRepository());
        $this->assertNotSame($this->validator, $this->validator->getLanguageRepository());
    }

    public function testHasLanguagesReturnsFalseWhenAnyLanguageExist()
    {
        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(null));

        $this->assertFalse($this->validator->hasLanguages());
    }

    public function testHasLanguagesReturnsTrueWhenAtLeastALanguageExist()
    {
        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array('fake')));

        $this->assertTrue($this->validator->hasLanguages());
    }

    public function testLanguageExistsReturnsFalseWhenTheRequiredLanguageDoesNotExist()
    {
        $this->languageRepository->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue(null));

        $this->assertFalse($this->validator->languageExists('fake'));
    }

    public function testLanguageExistsReturnsTrueWhenTheRequiredLanguageExists()
    {
        $this->languageRepository->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue(array('fake')));

        $this->assertTrue($this->validator->languageExists('fake'));
    }
}
