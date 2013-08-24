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
                array(
                    'path' => __DIR__.'/../Resources/config',
                    'configFiles' =>
                    array(
{% for theme_file in theme_files %}
                        '{{ theme_file }}.xml',
{% endfor %}
                    ),
                    'configuration' =>
                    array(
                        array(
                            'path' => __DIR__.'/../Resources/config/templates',
                            'configFiles' =>
                            array(
{% for template_file in template_files %}
                                '{{ template_file }}.xml',
{% endfor %}
                            ),
                            'configuration' =>
                            array(
                                array(
                                    'path' => __DIR__.'/../Resources/config/templates/slots',
                                    'configFiles' =>
                                    array(
{% for slot_file in slot_files %}
                                        '{{ slot_file }}.xml',
{% endfor %}
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
        return '{{ extension_alias }}';
    }
}