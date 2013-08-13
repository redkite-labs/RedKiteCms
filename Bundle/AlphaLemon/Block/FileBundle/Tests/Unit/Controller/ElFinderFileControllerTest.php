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
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\FileBundle\Tests\Unit\Controller;

use AlphaLemon\Block\FileBundle\Controller\ElFinderFileController;
use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Controller\AlCmsElFinderControllerTest;

/**
 * ElFinderControllerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ElFinderControllerTest extends AlCmsElFinderControllerTest
{
    public function testConnectFileAction()
    {
        $container = $this->initContainer(
            'el_finder.file_connector',
            'AlphaLemon\Block\FileBundle\Core\ElFinder\ElFinderFileConnector'
        );

        $controller = new ElFinderFileController();
        $controller->setContainer($container);
        $controller->connectFileAction();
    }
}
