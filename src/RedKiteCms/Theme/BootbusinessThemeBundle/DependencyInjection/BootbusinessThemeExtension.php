<?php
/**
 * This file is part of the BusinessWebsiteThemeBundle theme and it is distributed
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
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace  AlphaLemon\Theme\BootbusinessThemeBundle\DependencyInjection;

use AlphaLemon\ThemeEngineBundle\Core\Rendering\DependencyInjection\BaseExtension;

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
                                'empty.xml',
                                'home.xml',
                                'all_products.xml',
                                'product.xml',
                                'contacts.xml',
                                'two_columns.xml',
                            ),
                            'configuration' =>
                            array(
                                array(
                                    'path' => __DIR__.'/../Resources/config/templates/slots',
                                    'configFiles' =>
                                    array(
                                        'empty.xml',
                                        'home.xml',
                                        'all_products.xml',
                                        'base.xml',
                                        'product.xml',
                                        'contacts.xml',
                                        'two_columns.xml',
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