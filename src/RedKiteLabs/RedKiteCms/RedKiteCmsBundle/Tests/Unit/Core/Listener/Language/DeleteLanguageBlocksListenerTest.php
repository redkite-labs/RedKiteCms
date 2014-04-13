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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Listener\Language;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Listener\Language\DeleteLanguageBlocksListener;

/**
 * DeleteLanguageBlocksListenerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class DeleteLanguageBlocksListenerTest extends Base\DeleteLanguageBaseListenerTest
{
    protected function setUp()
    {
        $this->objectModel = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\BlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager->expects($this->any())
            ->method('getBlockRepository')
            ->will($this->returnValue($this->objectModel));

        parent::setUp();

        $this->testListener = new DeleteLanguageBlocksListener($this->manager);
    }

    protected function setUpObject()
    {
        return $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
    }
}
