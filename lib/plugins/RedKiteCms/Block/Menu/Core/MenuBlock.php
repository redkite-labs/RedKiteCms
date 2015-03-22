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
namespace RedKiteCms\Block\Menu\Core;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use RedKiteCms\Block\Link\Core\LinkBlock;
use RedKiteCms\Content\Block\ExtendableCollectionBlock;

/**
 * Class MenuBlock is the object deputed to handle a navigation menu
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Block\Menu\Core
 */
class MenuBlock extends ExtendableCollectionBlock
{
    /**
     * @Type("string")
     */
    protected $type = "Menu";
    /**
     * @Type("string")
     */
    protected $customTag = "rkcms-menu";

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = array(
            new LinkBlock(),
            new LinkBlock(),
        );

        parent::__construct();
    }
} 