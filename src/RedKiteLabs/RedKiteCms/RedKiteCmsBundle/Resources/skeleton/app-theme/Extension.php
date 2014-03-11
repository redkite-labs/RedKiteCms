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
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace  {{ namespace }}\DependencyInjection;

use RedKiteLabs\ThemeEngineBundle\Core\Rendering\DependencyInjection\BaseExtension;

class {{ bundle_basename }}Extension extends BaseExtension
{
    public function configureTheme()
    {
        return
            array(
                'path' => __DIR__.'/../Resources/config',
                'theme' =>
                array(
{% for theme_file in theme_files %}
                    '{{ theme_file }}.xml',
{% endfor %}
                ),
                'templates' =>
                array(
{% for template_file in template_files %}
                    'templates/{{ template_file }}.xml',
{% endfor %}
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
        return '{{ extension_alias }}';
    }
}
