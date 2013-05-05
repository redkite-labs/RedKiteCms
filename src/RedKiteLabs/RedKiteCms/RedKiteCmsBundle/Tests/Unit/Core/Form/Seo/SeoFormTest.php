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

namespace AlphaLemon\Block\ImageBundle\Tests\Unit\Core\Form;

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Seo\SeoForm;

/**
 * AlImageTypeTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
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
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
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
            'data_class' => 'AlphaLemon\AlphaLemonCmsBundle\Core\Form\Seo\Seo',
        );
        
        $this->assertEquals($expectedResult, $this->getForm()->getDefaultOptions(array()));
    }
    
    public function testGetName()
    {
        $this->assertEquals('seo_attributes', $this->getForm()->getName());
    }
}