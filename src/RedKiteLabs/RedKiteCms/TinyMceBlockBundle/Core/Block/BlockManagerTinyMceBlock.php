<?php
/**
 * This file is part of the TinyMceBlockBundle and it is distributed
 * under the MIT LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license     MIT LICENSE
 *
 */

namespace RedKiteLabs\RedKiteCms\TinyMceBlockBundle\Core\Block;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\InlineTextBlock\BlockManagerInlineTextBlock;

/**
 * Defines the Block Manager to handle a hypertext block managed by the tinyMce editor
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerTinyMceBlock extends BlockManagerInlineTextBlock
{
    /**
     * {@inheritdoc}
     */
    protected function renderHtml()
    {
        return array('RenderView' => array(
            'view' => 'TinyMceBlockBundle:Content:tinymce.html.twig',
            'options' => array(
                'id' => $this->alBlock->getId(), 
                'content' => $this->alBlock->getContent(), 
            ),
        ));
    }
}
