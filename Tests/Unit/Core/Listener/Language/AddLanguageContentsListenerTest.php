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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Language;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Language\AddLanguageBlocksListener;

/**
 * AddLanguageBlocksListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AddLanguageBlocksListenerTest extends Base\AddLanguageBaseListenerTest
{
    protected function setUp()
    {
        $this->objectModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager->expects($this->any())
            ->method('getBlockModel')
            ->will($this->returnValue($this->objectModel));

        parent::setUp();

        $this->testListener = new AddLanguageBlocksListener($this->manager);
    }

    public function testDbRecorsHaveBeenCopiedFromRequestLanguage()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->once())
            ->method('getLanguages')
            ->will($this->returnValue(array('en-gb', 'en')));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->once())
            ->method('get')
            ->will($this->returnValue($request));

        $testListener = new AddLanguageBlocksListener($this->manager, $container);

        parent::testDbRecorsHaveBeenCopiedFromRequestLanguage($testListener);
    }

    protected function setUpObject()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('Id' => 2, 'CreatedAt' => 'fake', "HtmlContent" => '<a href="_en_index" >aaa</a>')));

        return $block;
    }
}