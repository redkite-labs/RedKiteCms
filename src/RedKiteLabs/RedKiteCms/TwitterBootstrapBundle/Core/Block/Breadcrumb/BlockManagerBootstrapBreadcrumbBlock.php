<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Breadcrumb;

use RedKiteLabs\RedKiteCms\RedKiteCmsBaseBlocksBundle\Core\Block\Menu\BlockManagerMenu;

/**
 * BlockManagerBootstrapBreadcrumbBlock handles a Twitter Bootstrap Bradcrumb component
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagerBootstrapBreadcrumbBlock extends BlockManagerMenu
{
    protected $blocksTemplate = 'TwitterBootstrapBundle:Content:Breadcrumb/breadcrumb.html.twig';
    protected $listClass = "breadcrumb";
    
    /**
     * {@inheritdoc}
     */
    public function blockExtraOptions()
    {
        return array_merge(
            parent::blockExtraOptions(),
            array(  
                'no_link_when_active' => true,
            )
        );
    }
}
