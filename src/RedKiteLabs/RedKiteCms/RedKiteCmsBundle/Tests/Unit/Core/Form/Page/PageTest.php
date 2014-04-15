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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Page;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Page\Page;

/**
 * PageTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class PageTest extends TestCase
{
    private $page = null;
    
    protected function setUp()
    {
        parent::setUp();

        $this->page = new Page();
    }

    public function testPageName()
    {
        $page = 'index';
        $this->page->setPageName($page);
        $this->assertEquals($page, $this->page->getPageName());
    }
    
    public function testTemplate()
    {
        $template = 'home';
        $this->page->setTemplate($template);
        $this->assertEquals($template, $this->page->getTemplate());
    }

    public function testIsHome()
    {
        $this->page->setIsHome(true);
        $this->assertTrue($this->page->getIsHome());
    }
    
    public function testIsPublished()
    {
        $this->page->setIsPublished(true);
        $this->assertTrue($this->page->getIsPublished());
    }
}