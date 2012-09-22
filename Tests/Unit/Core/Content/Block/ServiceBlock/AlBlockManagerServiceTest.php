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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\ServiceBlock;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\ServiceBlock\AlBlockManagerService;

/**
 * AlBlockManagerServiceBlock
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerServiceBlockTest extends TestCase
{
    public function testServiceBlockDefaultValueReturnsNull()
    {
        $eventsHandler = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface');
        $factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');

        $blockManager = new AlBlockManagerService($eventsHandler, $factoryRepository);
        $this->assertNull($blockManager->getDefaultValue());
    }
}
