<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */
namespace RedKiteCms\Block\IconLinked\Core;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use RedKiteCms\Block\Icon\Core\IconBlock;
use RedKiteCms\Content\Block\ExtendableCollectionBlock;

/**
 * Class IconLinkedBlock is the object deputed to handle a FontAwesome linked icon
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Block\IconLinked\Core
 */
class IconLinkedBlock extends ExtendableCollectionBlock
{
    /**
     * @Type("string")
     */
    protected $value = "";
    /**
     * @Type("array")
     */
    protected $tags = array(
        'href' => '#',
    );
    /**
     * @Type("string")
     */
    protected $type = "IconLinked";
    /**
     * @Type("string")
     */
    protected $customTag = "rkcms-icon-linked";

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = array(
            new IconBlock(),
        );

        parent::__construct();
    }
} 