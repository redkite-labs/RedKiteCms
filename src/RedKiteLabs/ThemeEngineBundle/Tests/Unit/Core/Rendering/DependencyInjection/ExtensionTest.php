<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
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

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Asset;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Rendering\DependencyInjection\BaseExtension;
use org\bovigo\vfs\vfsStream;

/**
 * ExtensionTester
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ExtensionTester extends BaseExtension
{
    public function configureTheme()
    {
        return array(
                    array(
                        'path' => vfsStream::url('root/Resources/config'),
                        'configFiles' =>
                        array(
                            'theme_name.xml',
                        ),
                        'configuration' =>
                        array(
                            array(
                                'path' => vfsStream::url('root/Resources/config/templates'),
                                'configFiles' =>
                                array(
                                    'template.xml',
                                ),
                                'configuration' =>
                                array(
                                    array(
                                        'path' => vfsStream::url('root/Resources/config/templates/slots'),
                                        'configFiles' =>
                                        array(
                                            'slots.xml',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                );
    }
    
    public function getAlias() {
        return "ExtensionTester";
    }
}

/**
 * ExtensionTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ExtensionTest extends TestCase
{
    private $templateAssets;
    private $kernel;
    private $templateSlots;

    protected function setUp()
    {
        $this->root = vfsStream::setup('root', null, array(
            'Resources' => array(
                'config' => array(
                    'templates'=> array(
                            'slots'=> array(
                                'slots.xml' => '<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="red_kite_labs_theme.theme.template.apps.slots.nav_menu_blocks_title" class="%red_kite_labs_theme_engine.slot.class%" public="false">
            <argument type="string">nav_menu_blocks_title</argument>
            <argument type="collection" >
                <argument key="repeated">language</argument>
                <argument key="htmlContent">
                    <![CDATA[<h1>Block Apps</h1>]]>
                </argument>
            </argument>
            <tag name="red_kite_labs_theme.theme.template.apps.slots" />
        </service>
    </services>
</container>',
                            ),
                        
                        'template.xml' => '<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">


    <services>
        <service id="red_kite_labs_theme.theme.template_assets.apps" class="%red_kite_labs_theme_engine.template_assets.class%" public="false">
        </service>

        <service id="red_kite_labs_theme.theme.template.apps.slots" class="%red_kite_labs_theme_engine.template_slots.class%" public="false">
            <tag name="red_kite_labs_theme.theme.template.apps" />
        </service>

        <service id="red_kite_labs_theme.theme.template.apps" class="%red_kite_labs_theme_engine.template.class%" public="false">
            <argument type="service" id="kernel" />
            <argument type="service" id="red_kite_labs_theme.theme.template_assets.apps" />
            <argument type="service" id="red_kite_labs_theme.theme.template.apps.slots" />
            <tag name="red_kite_labs_theme.theme.template" />

            <call method="setThemeName">
                <argument type="string">RedKiteLabsThemeBundle</argument>
            </call>
            <call method="setTemplateName">
                <argument type="string">apps</argument>
            </call>
        </service>
    </services>
</container>',
                    ),
                    'theme_name.xml' => '<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="red_kite_labs_theme.theme" class="%red_kite_labs_theme_engine.theme.class%">
            <argument type="string">RedKiteLabsTheme</argument>
            <tag name="red_kite_labs_theme_engine.themes.theme" />
        </service>
    </services>
</container>',
                ),
            ),
        ));
    }

    public function testAAA()
    {
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerBuilder');
        
        $extensionTester = new ExtensionTester();
        $extensionTester->load(array('templates' => array()), $container);
    }
}
