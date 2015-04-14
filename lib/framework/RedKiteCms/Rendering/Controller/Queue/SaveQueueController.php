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

namespace RedKiteCms\Rendering\Controller\Queue;

use RedKiteCms\Content\BlockManager\BlockManagerEdit;
use RedKiteCms\FilesystemEntity\SlotParser;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EditBlockController is the object deputed to implement the action to edit a
 * block on a page
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Block
 */
abstract class SaveQueueController
{
    /**
     * Implements the action to edit a block
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function save(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $request = $options["request"];
        $queue = $request->get('queue');
        FilesystemTools::writeFile($options["configuration_handler"]->siteDir() . '/queue/queue_' . date("Y-m-d-H.i.s") . '.json', json_encode($queue));

        return new Response("Queue saved");
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
                'configuration_handler',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'request' => '\Symfony\Component\HttpFoundation\Request',
                'configuration_handler' => '\RedKiteCms\Configuration\ConfigurationHandler',
            )
        );
    }
}