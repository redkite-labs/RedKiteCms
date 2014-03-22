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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Page;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Page\PagesForm;

/**
 * PagesFormTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class PagesFormTest extends BaseType
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
        $theme = $this->getMockBuilder('\RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $theme
            ->expects($this->any())
            ->method('getThemeName')
            ->will($this->returnValue('foo'))
        ;
        
        $activeTheme = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\ActiveTheme')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $activeTheme 
            ->expects($this->any())
             ->method('getActiveTheme')
             ->will($this->returnValue($theme))
        ;
        $themesCollection = 
            $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\ThemesCollection')
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
            'data_class' => 'RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Page\Page',
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