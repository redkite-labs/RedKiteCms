<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Tools;

use JMS\Serializer\Serializer;

/**
 * Class JsonTools collects several methods to handle a json content
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Tools
 */
class JsonTools
{
    /**
     * De-serializes a block
     *
     * @param \JMS\Serializer\Serializer $serializer
     * @param $json
     *
     * @return \RedKiteCms\Content\Block\BaseBlock
     */
    public static function toBlock(Serializer $serializer, $json)
    {
        if (empty($json)) {
            return null;
        }

        $contentArray = json_decode($json, true);
        if (!array_key_exists("type", $contentArray)) {
            return null;
        }

        $className = Utils::blockClassFromType($contentArray["type"]);
        if (!class_exists($className)) {
            return null;
        }

        return $serializer->deserialize($json, $className, 'json');
    }

    /**
     * Serializes a block
     *
     * @param \JMS\Serializer\Serializer $serializer
     * @param \RedKiteCms\Content\Block\BaseBlock $block
     *
     * @return string
     */
    public static function toJson(Serializer $serializer, \RedKiteCms\Content\Block\BaseBlock $block)
    {
        return $serializer->serialize($block, 'json');
    }

    /**
     * Joins two json contents
     *
     * @param string|array $json1
     * @param string|array $json2
     *
     * @return array
     */
    public static function join($json1, $json2)
    {
        return array_merge(self::jsonDecode($json1), self::jsonDecode($json2));
    }

    /**
     * Decodes a json block
     * @param $json
     *
     * @return array|mixed
     */
    public static function jsonDecode($json)
    {
        if (is_array($json)) {
            return $json;
        }

        $value = json_decode($json, true);
        if (null === $value) {
            return array();
        }

        return $value;
    }
}