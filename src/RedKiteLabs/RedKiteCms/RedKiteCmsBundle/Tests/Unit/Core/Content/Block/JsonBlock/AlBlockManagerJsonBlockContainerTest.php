<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\JsonBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;
use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;

class AlBlockManagerJsonBlockContainerTester extends AlBlockManagerJsonBlockContainer
{
    public function getDefaultValue()
    {
        return "my value";
    }
}

/**
 * AlBlockManagerJsonBlockTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerJsonBlockContainerTest extends AlBlockManagerContainerBase
{
    public function testBlockManagerInitialization()
    {
        $this->initContainer();
        
        $blockManager = new AlBlockManagerJsonBlockContainerTester($this->container);
    }
}
