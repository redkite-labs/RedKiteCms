<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 * 
 */

namespace AlphaLemon\ThemeEngineBundle\Tests\Functional\AlphaLemon\ThemeEngineBundle\Core\ThemeManager;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\ThemeManager\AlThemeManager;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;

class AlThemeManagerTest extends TestCase 
{       
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseExceptionWhenEmptyOptionsArray()
    {
        $testAlThemeManager = new AlThemeManager(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        $testAlThemeManager->add();
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseExceptionWhenAnyValidOptionIsGiven()
    {
        $testAlThemeManager = new AlThemeManager(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        $testAlThemeManager->add(array('pathToSeek' => 'bar'));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseExceptionWhenTryingToActivateANonExistentTheme()
    {
        $testAlThemeManager = new AlThemeManager(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        $testAlThemeManager->activate('foo');
    }
    
    public function testAddThemeByName()
    {
        AlphaLemonDataPopulator::depopulate();
        $testAlThemeManager = new AlThemeManager(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        $this->assertTrue($testAlThemeManager->add(array('name' => 'themeBundle')));
        $this->assertEquals(1, AlThemeQuery::create()->filterByThemeName('themeBundle')->count());
        $this->assertTrue($testAlThemeManager->activate('themeBundle'));
        $this->assertEquals('themeBundle', AlThemeQuery::create()->filterByActive(1)->findOne()->getThemeName());
        
        return $testAlThemeManager;
    }
    
    /**
     * @depends testAddThemeByName
     */
    public function testAddThemeByNameWithActiveOptionAtFalse(AlThemeManager $testAlThemeManager)
    {
        $this->assertTrue($testAlThemeManager->add(array('name' => 'theme1Bundle', 'active' => '0')));
        $this->assertEquals(1, AlThemeQuery::create()->filterByThemeName('theme1Bundle')->count());
        $this->assertEquals('themeBundle', AlThemeQuery::create()->filterByActive(1)->findOne()->getThemeName());
        
        return $testAlThemeManager;
    }
    
    /**
     * @depends testAddThemeByNameWithActiveOptionAtFalse
     */
    public function testAddThemeByNameWithActiveOptionAtTrue(AlThemeManager $testAlThemeManager)
    {        
        $this->assertTrue($testAlThemeManager->add(array('name' => 'theme2Bundle', 'active' => '1')));
        $this->assertEquals(1, AlThemeQuery::create()->filterByThemeName('theme2Bundle')->count());
        $this->assertEquals('theme2Bundle', AlThemeQuery::create()->filterByActive(1)->findOne()->getThemeName());
        
        return $testAlThemeManager;
    }
    
    /**
     * @depends testAddThemeByNameWithActiveOptionAtTrue
     */
    public function testActivateTheActiveTheme(AlThemeManager $testAlThemeManager)
    {
        $this->assertNull($testAlThemeManager->activate('theme2Bundle'), 'The current active theme has been acivated again');
        
        return $testAlThemeManager;
    }
    
    /**
     * @depends testActivateTheActiveTheme
     */
    public function testActivate(AlThemeManager $testAlThemeManager)
    {   
        $this->assertTrue($testAlThemeManager->activate('theme1Bundle'));
        $query = AlThemeQuery::create()->filterByActive(1);
        $this->assertEquals(1, $query->count(), 'Just one theme should be marked as active');
        $this->assertEquals('theme1Bundle', $query->findOne()->getThemeName());        
        
        return $testAlThemeManager;
    }
    
    /**
     * @depends testActivate
     */
    public function testRemove(AlThemeManager $testAlThemeManager)
    {
        $this->assertTrue($testAlThemeManager->remove('theme2Bundle'));
        $this->assertEquals(0, AlThemeQuery::create()->filterByThemeName('theme2Bundle')->count());     
    }
}