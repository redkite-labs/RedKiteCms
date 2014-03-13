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

namespace  RedKiteCms\Theme\ModernBusinessThemeBundle\DependencyInjection;

use RedKiteLabs\ThemeEngineBundle\Core\Rendering\DependencyInjection\BaseExtension;

class ModernBusinessThemeExtension extends BaseExtension
{
    public function configureTheme()
    {
        return
            array(
                'path' => __DIR__.'/../Resources/config',
                'theme' =>
                array(
                    'modern_business_theme.xml',
                ),
                'templates' =>
                array(
                    'templates/portfolio-4-col.xml',
                    'templates/contact.xml',
                    'templates/portfolio-2-col.xml',
                    'templates/about.xml',
                    'templates/home.xml',
                    'templates/portfolio-1-col.xml',
                    'templates/portfolio-3-col.xml',
                    'templates/faq.xml',
                    'templates/services.xml',
                    'templates/portfolio-item.xml',
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
        return 'modern_business_theme';
    }
}
