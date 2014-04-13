<?php
/**
 * This file is part of the BusinessDropCapBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBaseBlocksBundle\Core\Form\Base\BaseType as BaseJson;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Implements a base form to defines the translation domain for this bundle
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class BaseType extends BaseJson
{
    /**
     * Sets the default options for this type
     *
     * @param OptionsResolverInterface $resolver The resolver for the options
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'TwitterBootstrapBundle',
            'csrf_protection' => false,
        ));
    }
}