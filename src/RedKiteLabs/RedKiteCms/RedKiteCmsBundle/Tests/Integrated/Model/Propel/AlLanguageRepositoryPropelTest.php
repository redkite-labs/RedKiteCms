<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license inflanguageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Integrated\Model\Propel;

/**
 * AlLanguageRepositoryPropelTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlLanguageRepositoryPropelTest extends Base\BaseModelPropel
{
    private $languageRepository;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $factoryRepository = $container->get('alpha_lemon_cms.factory_repository');
        $this->languageRepository = $factoryRepository->createRepository('Language');
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage AlLanguageRepositoryPropel accepts only AlLanguage propel objects
     */
    public function testRepositoryAcceptsOnlyAlLanguageObjects()
    {
        $this->languageRepository->setRepositoryObject(new \RedKiteLabs\RedKiteCmsBundle\Model\AlPage());
    }

    public function testALanguageIsRetrievedFromItsPrimaryKey()
    {
        $language = $this->languageRepository->fromPk(2);
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage', $language);
        $this->assertEquals(2, $language->getId());
    }

    public function testFetchActiveLangues()
    {
        $languages = $this->languageRepository->activeLanguages();
        $this->assertEquals(2, count($languages));
    }

    public function testLanguageIsNullWhenANullValueIsGiven()
    {
        $language = $this->languageRepository->fromLanguageName(null);
        $this->assertNull($language);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnExceptionIsThrownWhenTheGivenParameterIsNotString()
    {
        $this->languageRepository->fromLanguageName(array('en'));
    }

    public function testTheLanguageIsRetrieved()
    {
        $languageName = 'es';
        $language = $this->languageRepository->fromLanguageName($languageName);
        $this->assertEquals($languageName, $language->getLanguageName());
    }

    public function testTheMainLanguageIsRetrieved()
    {
        $language = $this->languageRepository->mainLanguage();
        $this->assertEquals('en', $language->getLanguageName());
    }

    public function testTheFirstLanguageIsRetrieved()
    {
        $language = $this->languageRepository->firstOne();
        $this->assertEquals('en', $language->getLanguageName());
    }
}