<?php
/**
 * This file is part of the BusinessWebsiteThemeBundle theme and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
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

namespace  RedKiteLabs\BootbusinessThemeBundle\DependencyInjection;

use RedKiteLabs\ThemeEngineBundle\Core\Rendering\DependencyInjection\BaseExtension;

class BootbusinessThemeExtension extends BaseExtension
{
    public function configureTheme()
    {
        return
            array(
                'path' => __DIR__.'/../Resources/config',
                'theme' =>
                array(
                    'bootbusiness_theme.xml',
                ),
                'templates' =>
                array(
                    'templates/empty.xml',
                    'templates/home.xml',
                    'templates/all_products.xml',
                    'templates/product.xml',
                    'templates/contacts.xml',
                    'templates/two_columns.xml',
                ),
                'slots' =>
                array(
                    'slots/slots.xml',
                ),
            )
        ;
    }

    public function getAlias()
    {
        return 'bootbusiness_theme';
    }
}
