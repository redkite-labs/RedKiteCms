<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Event\PageTree\Base;

use Symfony\Component\EventDispatcher\Event;
use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree;

/**
 * Defines the base event raised when the website is deployed
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class BasePageTreeEvent extends Event
{
    protected $pageTree;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree $pageTree
     *
     * @api
     */
    public function __construct(AlPageTree $pageTree)
    {
        $this->pageTree = $pageTree;
    }

    /**
     * Returns the deployer object
     *
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     *
     * @api
     */
    public function getPageTree()
    {
        return $this->pageTree;
    }

    /**
     * Sets the deployer
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree $pageTree
     *
     * @api
     */
    public function setPageTree(AlPageTree $pageTree)
    {
        $this->pageTree = $pageTree;
    }
}
