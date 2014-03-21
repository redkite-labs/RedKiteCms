<?php
/*
 * This file is part of the TwitterBootstrapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Thumbnail;

use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * Defines the Block Manager to handle the Bootstrap Thumbnail
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapThumbnailBlock extends AlBlockManagerBootstrapSimpleThumbnailBlock
{
    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface                           $container
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);

        $this->blockTemplate = sprintf('TwitterBootstrapBundle:Content:Thumbnail/%s/thumbnail.html.twig', $this->bootstrapVersion);
    }
}
