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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Event\Content\Base;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\TemplateManager\Base\BasePopulateEvent;

class PopulateEventTester extends BasePopulateEvent
{
}

/**
 * PopulateEventTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class PopulateEventTest extends TestCase
{
    private $templateManager;

    public function testTemplateManagerProperty()
    {
        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->event = new PopulateEventTester($this->templateManager);
        $templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->event->setTemplateManager($templateManager);
        $this->assertSame($templateManager, $this->event->getTemplateManager());        
        $this->assertNotSame($this->templateManager, $this->event->getTemplateManager());
    }
}

