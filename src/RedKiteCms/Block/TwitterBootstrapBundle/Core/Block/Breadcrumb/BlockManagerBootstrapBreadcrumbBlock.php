<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Breadcrumb;

use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Menu\AlBlockManagerMenu;

/**
 * AlBlockManagerBootstrapBreadcrumb handles a Twitter Bootstrap Bradcrumb component
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerBootstrapBreadcrumbBlock extends AlBlockManagerMenu
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
