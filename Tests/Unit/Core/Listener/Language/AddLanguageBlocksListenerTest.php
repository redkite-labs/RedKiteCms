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
        $this->objectModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager->expects($this->any())
            ->method('getBlockRepository')
            ->will($this->returnValue($this->objectModel));

        $this->urlManager = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface');
        $this->urlManager->expects($this->any())
            ->method('fromUrl')
            ->will($this->returnSelf());

        parent::setUp();

        $this->testListener = new AddLanguageBlocksListener($this->manager);
    }

    public function testDbRecorsHaveBeenCopiedFromRequestLanguageAndAnyLinkHasBeenRecognizedAsInternal()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->once())
            ->method('getLanguages')
            ->will($this->returnValue(array('en-gb', 'en')));

        $this->urlManager->expects($this->any())
            ->method('getInternalUrl')
            ->will($this->returnValue(null));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls($request, $this->urlManager));

        $this->setUpTestToCopyFromRequestLanguage();
        $testListener = new AddLanguageBlocksListener($this->manager, $container);
        $testListener->onBeforeAddLanguageCommit($this->event);
    }

    public function testDbRecorsHaveBeenCopiedFromRequestLanguageAndALinkHasBeenConvertedBecauseItHasBeenRecognizedHasInternal()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->once())
            ->method('getLanguages')
            ->will($this->returnValue(array('en-gb', 'en')));

        $this->urlManager->expects($this->any())
            ->method('getInternalUrl')
            ->will($this->returnValue('/alcms.php/backend/my-permalink'));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls($request, $this->urlManager));

        $this->setUpTestToCopyFromRequestLanguage();
        $testListener = new AddLanguageBlocksListener($this->manager, $container);
        $testListener->onBeforeAddLanguageCommit($this->event);
    }

    protected function setUpObject()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('Id' => 2, 'CreatedAt' => 'fake', "HtmlContent" => '<a href="my-awesome-homepage" >aaa</a>')));

        return $block;
    }
}
