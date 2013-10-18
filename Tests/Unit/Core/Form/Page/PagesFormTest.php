<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Page;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Page\PagesForm;

/**
 * PagesFormTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class PagesFormTest extends AlBaseType
{
    protected function configureFields()
    {
        return array(
            'pageName',
            'template',
            'isHome',
            'isPublished',
        );
    }
    
    protected function getForm()
    {
        $activeTheme = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\ActiveTheme\AlActiveTheme')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $themesCollection = 
            $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $themesCollection
             ->expects($this->any())
             ->method('getTheme')
             ->will($this->returnValue(array()))
        ;
        
        return new PagesForm($activeTheme, $themesCollection);
    }
    
    public function testDefaultOptions()
    {
        $this->setBaseResolver();

        $options = array(
            'data_class' => 'RedKiteLabs\RedKiteCmsBundle\Core\Form\Page\Page',
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
        $this->assertEquals('pages', $this->getForm()->getName());
    }
}