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
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Language\DeleteLanguageSeoListener;

/**
 * DeleteLanguageSeoListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class DeleteLanguageSeoListenerTest extends Base\DeleteLanguageBaseListenerTest
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
            ->method('getSeoModel')
            ->will($this->returnValue($this->objectModel));
        
        parent::setUp();
        
        $this->testListener = new DeleteLanguageSeoListener($this->manager);
    }
    
    protected function setUpObject()
    {
        return $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');
    }
}