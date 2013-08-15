<?php
/**
 * This file is part of the TinyMceBlockBundle and it is distributed
 * under the MIT LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license     MIT LICENSE
 *
 */

namespace RedKiteCms\Block\TinyMceBlockBundle\Core\Block;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\InlineTextBlock\AlBlockManagerInlineTextBlock;

/**
 * Defines the Block Manager to handle a hypertext block managed by the tinyMce editor
 *
 * @author RedKiteCms <webmaster@alphalemon.com>
 */
class AlBlockManagerTinyMceBlock extends AlBlockManagerInlineTextBlock
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
