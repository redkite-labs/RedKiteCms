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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\Base;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;

/**
 * BaseTestListener
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseListenerTest extends TestCase
{
    protected function setUpLanguage($returnId)
    {
        $language = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($returnId));

        $language->expects($this->any())
            ->method('getLanguageName')
            ->will($this->returnValue('en'));

        return $language;
    }

    protected function setUpPage($returnId)
    {
        $page = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlPage');
        $page->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($returnId));

        return $page;
    }
}
