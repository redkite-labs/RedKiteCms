<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infseoModelation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Integrated\Model\Propel;

use AlphaLemon\AlphaLemonCmsBundle\Tests\WebTestCaseFunctional;


/**
 * AlSeoRepositoryPropelTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlSeoRepositoryPropelTest extends Base\BaseModelPropel
{
    private $seoModel;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $this->seoModel = $container->get('seo_model');
    }

    public function testASeoObjectIsRetrievedFromItsPrimaryKey()
    {
        $seoAttribute = $this->seoModel->fromPk(2);
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo', $seoAttribute);
        $this->assertEquals(2, $seoAttribute->getId());
    }

    public function testSeoIsNullWhenANullValueIsGiven()
    {
        $seoAttributes = $this->seoModel->fromPermalink(null);
        $this->assertNull($seoAttributes);
    }
    
    /**
     * @expectedException \InvalidArgumentException 
     */
    public function testAnExceptionIsThrownWhenTheGivenParameterIsNotString()
    {
        $this->seoModel->fromPermalink(array('this-is-a-website-fake-page'));
    }
    
    public function testRetrieveSeoObjectFromPermalink()
    {
        $seoAttributes = $this->seoModel->fromPermalink('this-is-a-website-fake-page');
        $this->assertEquals(1, count($seoAttributes));
        $this->assertEquals('this-is-a-website-fake-page', $seoAttributes->getPermalink());
    }
    
    public function testRetrieveSeoObjectsFromPageId()
    {
        $seoAttributes = $this->seoModel->fromPageId(2);
        $this->assertEquals(2, count($seoAttributes));
    }
    
    public function testRetrieveSeoObjectsFromLanguageId()
    {
        $seoAttributes = $this->seoModel->fromLanguageId(2);
        $this->assertEquals(2, count($seoAttributes));
    }
    
    public function testRetrieveSeoObjectsFromPageIdWithLanguages()
    {
        $seoAttributes = $this->seoModel->fromPageIdWithLanguages(2);
        $this->assertEquals(2, count($seoAttributes));
        $this->assertEquals(1, count($seoAttributes[0]->getAlLanguage()));
    }
    
    public function testRetrieveSeoObjectsWithPagesAndLanguages()
    {
        $seoAttributes = $this->seoModel->fetchSeoAttributesWithPagesAndLanguages(2);
        $this->assertEquals(4, count($seoAttributes));
        
        $seo = $seoAttributes[0];
        $this->assertEquals(1, count($seo->getAlLanguage()));
        $this->assertEquals(1, count($seo->getAlPage()));
    }
}