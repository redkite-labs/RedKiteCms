<?php
/*
 * This file is part of the BootbusinessBlockBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteCms\Block\BootbusinessBlockBundle\Core\Block;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Navbar\AlBlockManagerBootstrapNavbarBlock;

/**
 * Description of AlBlockManagerBootbusinessBlock
 */
class AlBlockManagerBootbusinessNavbarBlock extends AlBlockManagerBootstrapNavbarBlock
{
    protected $contentTemplate = 'BootbusinessBlockBundle:Navbar:%s/navbar.html.twig';
}