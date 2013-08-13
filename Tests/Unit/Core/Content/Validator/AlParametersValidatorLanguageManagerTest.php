<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Validator;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorLanguageManager;

/**
 * AlParametersValidatorLanguagesManager
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlParametersValidatorLanguagesManager extends TestCase
{
    private $validator;
    private $languageRepository;

    protected function setUp()
    {
        $this->languageRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface');

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->languageRepository));

        $this->validator = new AlParametersValidatorLanguageManager($this->factoryRepository);
    }
    
    public function testLanguageRepositoryInjectedBySetters()
    {
        $languageRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface');
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
