<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace Controller\Theme;

use RedKiteCms\Rendering\Controller\Theme\SaveThemeController as BaseSaveThemeController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to save a site as a theme
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Theme
 */
class SaveThemeController extends BaseSaveThemeController
{
    /**
     * Save a site as a theme action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveAction(Request $request, Application $app)
    {
        $options = array(
            "configuration_handler" => $app["red_kite_cms.configuration_handler"],
            "plugin_manager" => $app["red_kite_cms.plugin_manager"],
            "theme_slot_manager" => $app["red_kite_cms.theme_slot_manager"],
            "pages_collection_parser" => $app["red_kite_cms.pages_collection_parser"],
        );

        return parent::save($options);
    }
}