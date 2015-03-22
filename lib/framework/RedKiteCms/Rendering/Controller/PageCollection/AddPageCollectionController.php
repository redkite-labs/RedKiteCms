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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddPageCollectionController is the object deputed to add a new page collection to the website
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Page
 */
abstract class AddPageCollectionController extends BasePageCollectionController
{
    /**
     * Implements the action to add a new page collection to the website
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(array $options)
    {
        $pageManager = $options["page_collection_manager"];
        $page = $pageManager
            ->contributor($options["username"])
            ->add($options["theme"], $options['red_kite_cms_config']->homepageTemplate());

        return $this->buildJSonResponse($page);
    }

    /**
     * Configures the options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(
            array(
                'theme',
                'red_kite_cms_config',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'red_kite_cms_config' => '\RedKiteCms\Configuration\ConfigurationHandler',
                'theme' => '\RedKiteCms\Content\Theme\Theme',
            )
        );
    }
}