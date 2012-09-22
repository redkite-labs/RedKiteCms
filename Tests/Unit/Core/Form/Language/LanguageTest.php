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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Form\Language;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Language\Language;

/**
 * LanguageTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class LanguageTest extends TestCase
{
    private $language = null;
    
    protected function setUp()
    {
        parent::setUp();

        $this->language = new Language();
    }

    public function testLanguage()
    {
        $language = 'en';
        $this->language->setLanguage($language);
        $this->assertEquals($language, $this->language->getLanguage());
    }

    public function testIsMain()
    {
        $this->language->setIsMain(true);
        $this->assertTrue($this->language->getIsMain());
    }
}