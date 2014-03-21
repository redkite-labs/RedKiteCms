<?php
/**
 * This file is part of the BusinessMenuBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock;

use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * AlBlockManagerJsonBlockContainer extends the AlBlockManagerJson base object with
 * the Symfony2 Container object
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class AlBlockManagerJsonBlockContainer extends AlBlockManagerJsonBlock
{
    protected $container;
    protected $translator;

    /**
     * Constructor
     *
     * @param ContainerInterface             $container
     * @param AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        $this->container = $container;
        $eventsHandler = $container->get('red_kite_cms.events_handler');
        $factoryRepository = $container->get('red_kite_cms.factory_repository');
        $this->translator = $this->container->get('red_kite_cms.translator');

        parent::__construct($eventsHandler, $factoryRepository, $validator);
    }
}
