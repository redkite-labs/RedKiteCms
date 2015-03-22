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

namespace RedKiteCms\Core\RedKiteCms\Core\Form\Security;

use RedKiteCms\Bridge\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * Class UserType is the object deputed to handle a registers user
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Core\RedKiteCms\Core\Form\Security
 */
class UserType extends BaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add(
            'password',
            null,
            array(
                'attr' => array(
                    'data-bind' => 'value: password, event: {change: $root.editUser}',
                    'class' => 'form-control input-sm',
                ),
            )
        );
    }
}
