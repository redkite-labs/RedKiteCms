<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infthemeRepositoryation, please view the LICENSE
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
 * AlThemeRepositoryPropelTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlThemeRepositoryPropelTest extends Base\BaseModelPropel
{
    private $themeRepository;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $factoryRepository = $container->get('alphalemon_cms.factory_repository');
        $this->themeRepository = $factoryRepository->createRepository('Theme');
    }

    public function testRetrieveActiveTheme()
    {
        $theme = $this->themeRepository->activeBackend();
        $this->assertInstanceOf('\AlphaLemon\ThemeEngineBundle\Model\AlTheme', $theme);
        $this->assertEquals(1, count($theme));
    }

    public function testThemeIsNullWhenANullValueIsGiven()
    {
        $theme = $this->themeRepository->fromName(null);
        $this->assertNull($theme);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnExceptionIsThrownWhenTheGivenParameterIsNotString()
    {
        $this->themeRepository->fromName(array('BusinessWebsiteThemeBundle'));
    }

    public function testRetrieveThemeObjectFromItsName()
    {
        $theme = $this->themeRepository->fromName('BusinessWebsiteThemeBundle');
        $this->assertEquals(1, count($theme));
        $this->assertEquals('BusinessWebsiteThemeBundle', $theme->getThemeName());
    }
}