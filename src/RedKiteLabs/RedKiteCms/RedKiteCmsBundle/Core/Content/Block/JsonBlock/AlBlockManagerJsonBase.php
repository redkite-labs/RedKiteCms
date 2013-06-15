<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * AlBlockManagerJson is the base object deputated to handle a json content
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
abstract class AlBlockManagerJsonBase extends AlBlockManager
{
    /**
     * Decodes a json content
     *
     * @param  string|AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock $block
     * @param  boolean  $assoc
     * @return array|object depends on assoc param
     * @throws Exception\InvalidJsonFormatException
     * 
     * @api
     */
    public static function decodeJsonContent($block, $assoc = true)
    {
        $content = $block;
        $blockType = null;
        if (is_object($block)) {
            $content = $block->getContent();
            $blockType = $block->getType();
        }
        
        $content = json_decode($content, $assoc);
        if (null === $content) {
            $blockTypeInfo = (null !== $blockType) ? ' for the block ' . $blockType . ' ' : '';

            $exception = array(
                'message' => 'The content format %blockTypeInfo%is wrong. You should remove that block and add it again.',
                'parameters' => array(
                    '%blockTypeInfo%' => $blockTypeInfo,
                ),
                'domain' => 'exceptions',
            );
            throw new Exception\InvalidJsonFormatException(json_encode($exception));
        }

        return $content;
    }
}
