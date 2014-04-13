<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infseoRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Integrated\Model\Propel;

/**
 * SeoRepositoryPropelTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SeoRepositoryPropelTest extends Base\BaseModelPropel
{
    private $seoRepository;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $factoryRepository = $container->get('red_kite_cms.factory_repository');
        $this->seoRepository = $factoryRepository->createRepository('Seo');
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage exception_only_propel_seo_objects_are_accepted
     */
    public function testRepositoryAcceptsOnlySeoObjects()
    {
        $this->seoRepository->setRepositoryObject(new \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page());
    }

    public function testASeoObjectIsRetrievedFromItsPrimaryKey()
    {
        $seoAttribute = $this->seoRepository->fromPk(2);
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Seo', $seoAttribute);
        $this->assertEquals(2, $seoAttribute->getId());
    }

    public function testSeoIsNullWhenANullValueIsGiven()
    {
        $seoAttributes = $this->seoRepository->fromPermalink(null);
        $this->assertNull($seoAttributes);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnExceptionIsThrownWhenTheGivenParameterIsNotString()
    {
        $this->seoRepository->fromPermalink(array('this-is-a-website-fake-page'));
    }

    public function testRetrieveSeoObjectFromPermalink()
    {
        $seoAttributes = $this->seoRepository->fromPermalink('this-is-a-website-fake-page');
        $this->assertEquals(1, count($seoAttributes));
        $this->assertEquals('this-is-a-website-fake-page', $seoAttributes->getPermalink());
    }

    public function testRetrieveSeoObjectsFromPageId()
    {
        $seoAttributes = $this->seoRepository->fromPageId(2);
        $this->assertEquals(2, count($seoAttributes));
    }

    public function testRetrieveSeoObjectsFromLanguageId()
    {
        $seoAttributes = $this->seoRepository->fromLanguageId(2);
        $this->assertEquals(2, count($seoAttributes));
    }

    public function testRetrieveSeoObjectsFromPageIdWithLanguages()
    {
        $seoAttributes = $this->seoRepository->fromPageIdWithLanguages(2);
        $this->assertEquals(2, count($seoAttributes));
        $this->assertEquals(1, count($seoAttributes[0]->getLanguage()));
    }

    public function testRetrieveSeoObjectsWithPagesAndLanguages()
    {
        $seoAttributes = $this->seoRepository->fetchSeoAttributesWithPagesAndLanguages(2);
        $this->assertEquals(4, count($seoAttributes));

        $seo = $seoAttributes[0];
        $this->assertEquals(1, count($seo->getLanguage()));
        $this->assertEquals(1, count($seo->getPage()));
    }
    
    public function testRetrieveSeoObjectsFromPagesAndLanguages()
    {
        $seoAttributes = $this->seoRepository->fromPageAndLanguage(2, 2);
        $this->assertEquals(1, count($seoAttributes));
    }
    
    public function testRetrieveSeoObjectsFromLanguageName()
    {
        $seoAttributes = $this->seoRepository->fromLanguageName('en');
        $this->assertEquals(2, count($seoAttributes));
        
        $seoAttributesUnordered = $this->seoRepository->fromLanguageName('en', false);
        for ($i = 0; $i < count($seoAttributesUnordered); $i++) {
            $this->assertNotEquals($seoAttributesUnordered[$i]->getPermalink(), $seoAttributes[$i]->getPermalink());
        }
    }
}