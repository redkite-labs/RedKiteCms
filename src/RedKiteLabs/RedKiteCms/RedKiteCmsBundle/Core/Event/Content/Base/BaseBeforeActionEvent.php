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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Base;

use Symfony\Component\EventDispatcher\Event;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\ContentManagerInterface;

/**
 * Defines a base event raised by a Content before an action occours
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseBeforeActionEvent extends BaseActionEvent
{
    protected $values;
    protected $abort = false;

    public function __construct(ContentManagerInterface $contentManager = null, array $values = null)
    {
        parent::__construct($contentManager);

        $this->values = $values;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function setValues(array $values)
    {
        $this->values = $values;
    }

    /**
     * Stops the current action
     */
    public function abort()
    {
        $this->abort = true;
    }

    public function isAborted()
    {
        return $this->abort;
    }
}
