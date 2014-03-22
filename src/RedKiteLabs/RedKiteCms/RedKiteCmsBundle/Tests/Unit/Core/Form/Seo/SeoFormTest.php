<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Seo;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Seo\SeoForm;
 
/**
 * SeoFormTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SeoFormTest extends BaseType
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
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\LanguageRepositoryPropel')
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
        $this->setBaseResolver();

        $options = array(
            'data_class' => 'RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Seo\Seo',
        );
        $this->resolver
            ->expects($this->at(1))
            ->method('setDefaults')
            ->with($options)
        ;

        $this->getForm()->setDefaultOptions($this->resolver);
    }
    
    public function testGetName()
    {
        $this->assertEquals('seo_attributes', $this->getForm()->getName());
    }
}