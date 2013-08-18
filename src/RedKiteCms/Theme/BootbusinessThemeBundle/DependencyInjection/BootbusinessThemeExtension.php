<?php
/**
 * This file is part of the BusinessWebsiteThemeBundle theme and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 RedKiteTheme
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace  RedKiteTheme\Theme\BootbusinessThemeBundle\DependencyInjection;

use RedKiteLabs\ThemeEngineBundle\Core\Rendering\DependencyInjection\BaseExtension;

class BootbusinessThemeExtension extends BaseExtension
{
    public function configureTheme()
    {
        return
            array(
                array(
                    'path' => __DIR__.'/../Resources/config',
                    'configFiles' =>
                    array(
                        'bootbusiness_theme.xml',
                    ),
                    'configuration' =>
                    array(
                        array(
                            'path' => __DIR__.'/../Resources/config/templates',
                            'configFiles' =>
                            array(
                                'product.xml',
                                'two_columns.xml',
                                'home.xml',
                                'all_products.xml',
                                'contacts.xml',
                                'empty.xml',
                            ),
                            'configuration' =>
                            array(
                                array(
                                    'path' => __DIR__.'/../Resources/config/templates/slots',
                                    'configFiles' =>
                                    array(
                                        'product.xml',
                                        'two_columns.xml',
                                        'home.xml',
                                        'all_products.xml',
                                        'contacts.xml',
                                        'base.xml',
                                        'empty.xml',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            );
    }

    public function getAlias()
    {
        return 'bootbusiness_theme';
    }
}