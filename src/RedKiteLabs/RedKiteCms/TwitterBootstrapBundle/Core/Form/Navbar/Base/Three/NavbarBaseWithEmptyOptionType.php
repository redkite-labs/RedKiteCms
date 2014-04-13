<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Navbar\Base\Three;

/**
 * Defines the form to edit a navbar dropbown block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class NavbarBaseWithEmptyOptionType extends NavbarBaseType
{
    protected function setChoices()
    {
        $choices = parent::setChoices();
        $choices["empty_value"] = "None";

        return $choices;
    }
}
