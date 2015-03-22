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

namespace Controller\PageCollection;

use RedKiteCms\Rendering\Controller\PageCollection\ShowPageCollectionController as BaseShowPageCollectionController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This object implements the Silex controller to show the page collection dashboard interface
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Page
 */
class ShowPageCollectionController extends BaseShowPageCollectionController
{
    /**
     * Show page collection dashboard interface action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, Application $app)
    {
        $options = array(
            "request" => $request,
            "page_collection_manager" => $app["red_kite_cms.page_collection_manager"],
            'form_factory' => $app["form.factory"],
            "pages_collection_parser" => $app["red_kite_cms.pages_collection_parser"],
            "username" => $this->fetchUsername($app["security"], $app["red_kite_cms.configuration_handler"]),
            'plugin_manager' => $app["red_kite_cms.plugin_manager"],
            'theme_manager' => $app["red_kite_cms.theme_slot_manager"],
            'template_assets' => $app["red_kite_cms.template_assets"],
            'twig' => $app["twig"],
        );

        return parent::show($options);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(
            array(
                'username',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'username' => array(
                    'null',
                    'string',
                ),
            )
        );
    }
}