<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\BootbusinessBlockBundle\Core\Block;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Navbar\BlockManagerBootstrapNavbarBlock;

/**
 * Description of BlockManagerBootbusinessNavbarBlock
 */
class BlockManagerBootbusinessNavbarBlock extends BlockManagerBootstrapNavbarBlock
{
    protected $contentTemplate = 'BootbusinessBlockBundle:Navbar:%s/navbar.html.twig';
}