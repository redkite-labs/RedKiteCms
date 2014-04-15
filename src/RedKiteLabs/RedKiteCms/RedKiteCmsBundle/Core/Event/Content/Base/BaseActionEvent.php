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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Base;

use Symfony\Component\EventDispatcher\Event;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\ContentManagerInterface;

/**
 * Defines a base event raised from a ContentManager
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseActionEvent extends Event
{
    /** @var ContentManagerInterface */
    protected $alManager;

    /**
     * Constructor
     *
     * @param ContentManagerInterface $alBlockManager
     */
    public function __construct(ContentManagerInterface $alBlockManager = null)
    {
        $this->alManager = $alBlockManager;
    }

    /**
     * Returns the current ContentManager object
     *
     * @return ContentManagerInterface
     */
    public function getContentManager()
    {
        return $this->alManager;
    }

    /**
     * Sets the current ContentManager object
     *
     * @param ContentManagerInterface $value
     */
    public function setContentManager(ContentManagerInterface $value)
    {
        $this->alManager = $value;
    }
}
