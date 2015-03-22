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

namespace RedKiteCms\Rendering\Controller\ElFinder;

use RedKiteCms\Rendering\Controller\BaseController;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ElFinderController is the object deputed to implement the action to create the connector for the ElFinder media
 * library
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\ElFinder
 */
abstract class ElFinderController extends BaseController
{
    /**
     * Implements the action to create the connector for the ElFinder media library
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $options["connector"]->connect();
    }

    /**
     * Configures the options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            array(
                'connector',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'connector' => '\RedKiteCms\Bridge\ElFinder\ElFinderConnectorInterface',
            )
        );
    }
}