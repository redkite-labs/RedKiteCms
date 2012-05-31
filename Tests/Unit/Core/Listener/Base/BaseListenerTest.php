<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Base;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;

/**
 * BaseTestListener
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class BaseListenerTest extends TestCase
{
    protected function setUpLanguage($returnId)
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($returnId));

        return $language;
    }

    protected function setUpPage($returnId)
    {
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($returnId));

        return $page;
    }
}