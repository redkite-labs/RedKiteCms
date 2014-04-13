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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Language;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Language\Language;

/**
 * LanguageTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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