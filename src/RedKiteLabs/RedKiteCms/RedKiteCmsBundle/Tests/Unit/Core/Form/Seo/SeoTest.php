<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Seo;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Seo\Seo;

/**
 * SeoTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SeoTest extends TestCase
{
    private $seo = null;
    
    protected function setUp()
    {
        parent::setUp();

        $this->seo = new Seo();
    }

    public function testIdPage()
    {
        $page = 2;
        $this->seo->setIdPage($page);
        $this->assertEquals($page, $this->seo->getIdPage());
    }
    
    public function testIdLanguage()
    {
        $language = 2;
        $this->seo->setIdLanguage($language);
        $this->assertEquals($language, $this->seo->getIdLanguage());
    }
    
    public function testPermalink()
    {
        $permalink = 'my-permalink';
        $this->seo->setPermalink($permalink);
        $this->assertEquals($permalink, $this->seo->getPermalink());
    }
    
    public function testTitle()
    {
        $title = 'my title';
        $this->seo->setTitle($title);
        $this->assertEquals($title, $this->seo->getTitle());
    }
    
    public function testDescription()
    {
        $description = 'my description';
        $this->seo->setDescription($description);
        $this->assertEquals($description, $this->seo->getDescription());
    }
    
    public function testKeywords()
    {
        $keywords = 'my,keywords';
        $this->seo->setKeywords($keywords);
        $this->assertEquals($keywords, $this->seo->getKeywords());
    }
}