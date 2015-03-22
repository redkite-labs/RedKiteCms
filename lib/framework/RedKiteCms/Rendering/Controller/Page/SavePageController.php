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

use RedKiteCms\Content\BlockManager\BlockManagerApprover;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SavePageController is the object deputed to save a page in production
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Page
 */
abstract class SavePageController extends BasePageController
{
    /**
     * Implements the action to save the page
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function save(array $options)
    {
        $request = $options["request"];
        $serializer = $options["serializer"];
        $pageManager = $options["page_collection_manager"];
        $saveOptions = array(
            'page' => $request->get('page'),
            'language' => $request->get('language'),
            'country' => $request->get('country'),
        );

        $blockManager = new BlockManagerApprover($serializer, $options["block_factory"], new OptionsResolver());
        $pageManager
            ->contributor($options["username"])
            ->save($blockManager, $saveOptions);

        return $this->buildJSonResponse(array());
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
                'serializer',
                'block_factory',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'serializer' => '\JMS\Serializer\Serializer',
                'block_factory' => '\RedKiteCms\Content\Block\BlockFactory',
            )
        );
    }
}