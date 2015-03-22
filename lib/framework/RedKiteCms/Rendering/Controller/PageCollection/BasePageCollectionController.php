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

namespace RedKiteCms\Rendering\Controller\PageCollection;

use RedKiteCms\Rendering\Controller\BaseController;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BasePageCollectionController is the base class to define a page collection based controller
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Page
 */
abstract class BasePageCollectionController extends BaseController
{
    /**
     * Configures the base options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            array(
                'request',
                'page_collection_manager',
                'username',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'request' => '\Symfony\Component\HttpFoundation\Request',
                'page_collection_manager' => '\RedKiteCms\Content\PageCollection\PageCollectionManager',
                'username' => array(
                    'null',
                    'string',
                ),
            )
        );
    }
}