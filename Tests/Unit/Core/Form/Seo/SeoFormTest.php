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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\ImageBundle\Tests\Unit\Core\Form;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Seo\SeoForm;
 
/**
 * SeoFormTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SeoFormTest extends AlBaseType
{
    protected function configureFields()
    {   
        return array(
            'idPage',
            'idLanguage',
            'permalink',
            'title',
            'description',
            'keywords',
            'sitemapChangeFreq',
            'sitemapPriority',
        );
    }
    
    protected function getForm()
    {
        $languagesRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $languagesRepository
             ->expects($this->any())
             ->method('activeLanguages')
             ->will($this->returnValue(array()))
        ;
        
        return new SeoForm($languagesRepository);
    }
    
    public function testDefaultOptions()
    {
        $expectedResult = array(
            'data_class' => 'RedKiteLabs\RedKiteCmsBundle\Core\Form\Seo\Seo',
        );
        
        $this->assertEquals($expectedResult, $this->getForm()->getDefaultOptions(array()));
    }
    
    public function testGetName()
    {
        $this->assertEquals('seo_attributes', $this->getForm()->getName());
    }
}