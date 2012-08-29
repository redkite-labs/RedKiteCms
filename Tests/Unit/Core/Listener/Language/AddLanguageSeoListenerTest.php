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
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Language\AddLanguageSeoListener;

/**
 * AddLanguageSeoListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AddLanguageSeoListenerTest extends Base\AddLanguageBaseListenerTest
{
    protected function setUp()
    {
        $this->objectModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->manager->expects($this->any())
            ->method('getSeoRepository')
            ->will($this->returnValue($this->objectModel));

        parent::setUp();

        $this->testListener = new AddLanguageSeoListener($this->manager);
    }

    public function testDbRecordsHaveBeenCopiedFromRequestLanguage()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->once())
            ->method('getLanguages')
            ->will($this->returnValue(array('en-gb', 'en')));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->once())
            ->method('get')
            ->will($this->returnValue($request));

        $this->setUpTestToCopyFromRequestLanguage();
        $testListener = new AddLanguageSeoListener($this->manager, $container);
        $testListener->onBeforeAddLanguageCommit($this->event);
    }

    protected function setUpObject()
    {
        $seo = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');
        $seo->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('idLanguage' => 2, 'languageName' => 'fake', 'Permalink' => 'fake')));

        return $seo;
    }
}