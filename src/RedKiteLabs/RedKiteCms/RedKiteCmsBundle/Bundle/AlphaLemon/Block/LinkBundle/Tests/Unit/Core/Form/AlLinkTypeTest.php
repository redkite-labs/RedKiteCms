<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\LinkBundle\Tests\Unit\Core\Form;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use AlphaLemon\Block\LinkBundle\Core\Form\AlLinkType;

/**
 * AlLinkTypeTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlLinkTypeTest extends AlBaseType
{
    protected function configureFields()
    {
        return array(
            'href',
            'value',
        );
    }
    
    protected function getForm()
    {
        return new AlLinkType();
    }
}