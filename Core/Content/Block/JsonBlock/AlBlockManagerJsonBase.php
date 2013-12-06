<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * AlBlockManagerJson is the base object deputated to handle a json content
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class AlBlockManagerJsonBase extends AlBlockManager
{
    /**
     * Decodes a json content
     *
     * @param  string|RedKiteLabs\RedKiteCmsBundle\Model\AlBlock $block
     * @param  boolean                                           $assoc
     * @return array|object                                      depends on assoc param
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
                'message' => 'exception_wrong_json_format',
                'parameters' => array(
                    '%blockTypeInfo%' => $blockTypeInfo,
                ),
            );
            throw new Exception\InvalidJsonFormatException(json_encode($exception));
        }

        return $content;
    }
}
