<?php
/**
 * This file is part of the BusinessMenuBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * AlBlockManagerJsonBlockContainer extends the AlBlockManagerJson base object with
 * the Symfony2 Container object
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlBlockManagerJsonBlockContainer extends AlBlockManagerJsonBlock
{
    protected $container;

    /**
     * Constructor
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator 
     * 
     * @api
     */
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        $this->container = $container;
        $eventsHandler = $container->get('alpha_lemon_cms.events_handler');
        $factoryRepository = $container->get('alpha_lemon_cms.factory_repository');

        parent::__construct($eventsHandler, $factoryRepository, $validator);
    }
}