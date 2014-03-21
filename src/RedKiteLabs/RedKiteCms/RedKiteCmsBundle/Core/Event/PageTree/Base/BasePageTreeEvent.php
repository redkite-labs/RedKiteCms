<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\PageTree\Base;

use Symfony\Component\EventDispatcher\Event;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree;

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
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree $pageTree
     *
     * @api
     */
    public function __construct(PageTree $pageTree)
    {
        $this->pageTree = $pageTree;
    }

    /**
     * Returns the deployer object
     *
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree
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
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree $pageTree
     *
     * @api
     */
    public function setPageTree(PageTree $pageTree)
    {
        $this->pageTree = $pageTree;
    }
}
