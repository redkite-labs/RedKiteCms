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
namespace RedKiteCms\Block\Image\Core;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use RedKiteCms\Content\Block\ExtendableBlock;

/**
 * Class ImageBlock is the object deputed to handle an image
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Block\Image\Core
 */
class ImageBlock extends ExtendableBlock
{
    /**
     * @Type("string")
     */
    protected $type = "Image";
    /**
     * @Type("string")
     */
    protected $href = "";
    /**
     * @Type("array")
     */
    protected $tags = array(
        'src' => '',
        'data-src' => 'holder.js/260x180',
        'title' => '',
        'alt' => '',
    );
    /**
     * @Type("string")
     */
    protected $customTag = "rkcms-image";

    /**
     * {@inheritdoc}
     */
    public function updateSource()
    {
        $source = array(
            "value" => $this->value,
            "tags" => $this->tags,
            "href" => $this->href,
        );

        $this->source = \Symfony\Component\Yaml\Yaml::dump($source, 100, 2);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTranslatorOptions()
    {
        return array(
            "domain" => "RedKiteCms",
            "fields" => array(),
        );
    }
} 