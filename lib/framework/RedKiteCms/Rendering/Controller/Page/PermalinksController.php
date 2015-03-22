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

namespace RedKiteCms\Rendering\Controller\Page;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PermalinksController is the object deputed to list the website permalinks
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Page
 */
abstract class PermalinksController extends BasePageController
{
    /**
     * Implements the action to list the website permalink
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listPermalinks(array $options)
    {
        $permalinks = $options["pages_collection_parser"]
            ->contributor($options["username"])
            ->parse()
            ->permalinksByLanguage();

        $permalinksList = array();
        foreach ($permalinks as $permalink) {
            $permalinksList[] = array(
                'title' => $permalink,
                'value' => $permalink,
            );
        }

        return $this->buildJSonResponse($permalinksList);
    }

    /**
     * Configures the base options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            array(
                'request',
                'pages_collection_parser',
                'username',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'request' => '\Symfony\Component\HttpFoundation\Request',
                'pages_collection_parser' => '\RedKiteCms\Content\PageCollection\PagesCollectionParser',
                'username' => array(
                    'null',
                    'string',
                ),
            )
        );
    }
}