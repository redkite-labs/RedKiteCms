<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * Provides the Container for the AlBlockManager
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlBlockManagerContainer extends AlBlockManager
{
    protected $container = null;

    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        $this->container = $container;
        $dispatcher = $container->get('event_dispatcher');
        $factoryRepository = $container->get('alpha_lemon_cms.factory_repository');

        parent::__construct($dispatcher, $factoryRepository, $validator);
    }
}
