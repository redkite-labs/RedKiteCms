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

use RedKiteCms\Rendering\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SaveQueueController is the object deputed to save the operations from the frontend to the backend
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Block
 */
abstract class SaveQueueController extends BaseController
{
    /**
     * Implements the action to save the queue
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
        if (null !== $queue) {
            $this->options["queue_manager"]->queue($queue);
        }
        $username = $this->fetchUserName($options["security"], $options["configuration_handler"]);

        $result = $this->options["queue_manager"]->execute($username);
        if ( ! $result) {
            $content = $this->options["queue_manager"]->renderQueue();

            return new Response($content);
        }

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
                'security',
                'configuration_handler',
                'queue_manager'
            )
        );

        $resolver->setAllowedTypes(
            array(
                'request' => '\Symfony\Component\HttpFoundation\Request',
                'configuration_handler' => '\RedKiteCms\Configuration\ConfigurationHandler',
                'security' => '\Symfony\Component\Security\Core\SecurityContext',
                'queue_manager' => '\RedKiteCms\Rendering\Queue\QueueManager',
            )
        );
    }
}