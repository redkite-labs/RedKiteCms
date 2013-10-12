<?php
/**
 * This file is part of the BusinessCarouselBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Form\JsonBlock;

use RedKiteLabs\RedKiteCmsBundle\Core\Form\Base\BaseBlockType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * JsonBlockType is the abstract Type that should be used to implement an App-Block which
 * has a form interface and saves its content as json
 */
abstract class JsonBlockType extends BaseBlockType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        return array(
            'csrf_protection' => false,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'al_json_block';
    }
}
