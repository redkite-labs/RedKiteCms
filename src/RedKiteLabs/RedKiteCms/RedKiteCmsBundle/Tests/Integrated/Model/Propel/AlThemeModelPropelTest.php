<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infthemeModelation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Integrated\Model\Propel;

use AlphaLemon\AlphaLemonCmsBundle\Tests\WebTestCaseFunctional;


/**
 * AlThemeModelPropelTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlThemeModelPropelTest extends Base\BaseModelPropel
{
    private $themeModel;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $this->themeModel = $container->get('theme_model');
    }
    
    public function testRetrieveActiveTheme()
    {
        $theme = $this->themeModel->activeBackend();        
        $this->assertInstanceOf('\AlphaLemon\ThemeEngineBundle\Model\AlTheme', $theme);
        $this->assertEquals(1, count($theme));
    }

    public function testThemeIsNullWhenANullValueIsGiven()
    {
        $theme = $this->themeModel->fromName(null);
        $this->assertNull($theme);
    }
    
    /**
     * @expectedException \InvalidArgumentException 
     */
    public function testAnExceptionIsThrownWhenTheGivenParameterIsNotString()
    {
        $this->themeModel->fromName(array('BusinessWebsiteThemeBundle'));
    }
    
    public function testRetrieveThemeObjectFromItsName()
    {
        $theme = $this->themeModel->fromName('BusinessWebsiteThemeBundle');
        $this->assertEquals(1, count($theme));
        $this->assertEquals('BusinessWebsiteThemeBundle', $theme->getThemeName());
    }
}