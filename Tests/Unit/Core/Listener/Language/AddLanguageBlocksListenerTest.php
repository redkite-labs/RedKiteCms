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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\Language;

use RedKiteLabs\RedKiteCmsBundle\Core\Listener\Language\AddLanguageBlocksListener;

/**
 * AddLanguageBlocksListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AddLanguageBlocksListenerTest extends Base\AddLanguageBaseListenerTest
{
    protected function setUp()
    {
        $this->objectModel = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager->expects($this->any())
            ->method('getBlockRepository')
            ->will($this->returnValue($this->objectModel));

        $this->urlManager = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Core\UrlManager\AlUrlManagerInterface');
        $this->urlManager->expects($this->any())
            ->method('fromUrl')
            ->will($this->returnSelf());

        parent::setUp();

        $this->testListener = new AddLanguageBlocksListener($this->manager);
    }

    public function testDbRecorsHaveBeenCopiedFromRequestLanguageAndAnyLinkHasBeenRecognizedAsInternal()
    {
        $this->setUpLanguageManager();        
        $container = $this->initContainer();
        $this->urlManager->expects($this->any())
            ->method('getInternalUrl')
            ->will($this->returnValue(null));

        $this->setUpTestToCopyFromRequestLanguage();
        $testListener = new AddLanguageBlocksListener($this->manager, $container);
        $testListener->onBeforeAddLanguageCommit($this->event);
    }

    public function testDbRecorsHaveBeenCopiedFromRequestLanguageAndALinkHasBeenConvertedBecauseItHasBeenRecognizedHasInternal()
    {
        $this->setUpLanguageManager();
        $container = $this->initContainer();
        $this->urlManager->expects($this->any())
            ->method('getInternalUrl')
            ->will($this->returnValue('/alcms.php/backend/my-permalink'));

        $this->setUpTestToCopyFromRequestLanguage();
        $testListener = new AddLanguageBlocksListener($this->manager, $container);
        $testListener->onBeforeAddLanguageCommit($this->event);
    }

    protected function setUpObject()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('Id' => 2, 'CreatedAt' => 'fake', "Content" => '<a href="my-awesome-homepage" >aaa</a>')));

        return $block;
    }
    
    private function initContainer()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->once())
            ->method('getLanguages')
            ->will($this->returnValue(array('en-gb', 'en')));
        
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->at(0))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request));
        
        $container->expects($this->at(1))
            ->method('get')
            ->with('alphalemon_cms.urlManager')
            ->will($this->returnValue($this->urlManager));
        
        return $container;
    }
}
