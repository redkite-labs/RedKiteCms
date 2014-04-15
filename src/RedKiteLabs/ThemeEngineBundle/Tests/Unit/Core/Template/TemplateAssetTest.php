<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Asset;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Template\TemplateAssets;

/**
 * TemplateAssetTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class TemplateAssetTest extends TestCase
{   
    protected function setUp() 
    {
        $this->templateAssets = new TemplateAssets();
    }
    
    /**
     * @expectedException \RedKiteLabs\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testThemeNameThrowsAnExceptionWhenANullValueIsGiven()
    {
        $this->templateAssets->setThemeName(null);
    }
    
    /**
     * @expectedException \RedKiteLabs\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testThemeNameThrowsAnExceptionWhenANonStringValueIsGiven()
    {
        $this->templateAssets->setThemeName(3);
    }
    
    /**
     * @expectedException \RedKiteLabs\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testTemplateNameThrowsAnExceptionWhenANullValueIsGiven()
    {
        $this->templateAssets->setTemplateName(null);
    }
    
    /**
     * @expectedException \RedKiteLabs\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testTemplateNameThrowsAnExceptionWhenANonStringValueIsGiven()
    {
        $this->templateAssets->setTemplateName(array('fake'));
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testAnExceptionIsThrownCallingAMethodThatDoesNotExist()
    {
        $this->templateAssets->getImages();
    }
    
    public function testAssetsAreNotRetrievedJustValorizingTheThemeName()
    {
        $this->templateAssets->setThemeName('BusinessWebsiteThemeBundle');
        $this->assertEquals('BusinessWebsiteThemeBundle', $this->templateAssets->getThemeName());
        $this->assertCount(0, $this->templateAssets->getExternalStylesheets());
    }
    
    public function testAssetsAreNotRetrievedJustValorizingTheTemplateName()
    {
        $this->templateAssets->setTemplateName('Home');
        $this->assertEquals('home', $this->templateAssets->getTemplateName());
        $this->assertCount(0, $this->templateAssets->getExternalStylesheets());
    }
    
    public function testAssetsHaveBeenSetted()
    {
        $this->templateAssets
                ->setThemeName('BusinessWebsiteThemeBundle')
                ->setTemplateName('Home');
        $this->assertCount(0, $this->templateAssets->getExternalStylesheets());
    }
    
    public function testSetExternalStylesheetsFromAStringAsset()
    {
        $assets = '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css';
        
        $this->templateAssets->setExternalStylesheets($assets);
        $this->assertEquals(array($assets), $this->templateAssets->getExternalStylesheets());   
        $this->assertCount(1, $this->templateAssets->getExternalStylesheets());
    }
    
    public function testSetExternalStylesheets()
    {
        $assets = array('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css');
        
        $this->templateAssets->setExternalStylesheets($assets);
        $this->assertEquals($assets, $this->templateAssets->getExternalStylesheets());        
        $this->assertCount(1, $this->templateAssets->getExternalStylesheets());  
    }
}