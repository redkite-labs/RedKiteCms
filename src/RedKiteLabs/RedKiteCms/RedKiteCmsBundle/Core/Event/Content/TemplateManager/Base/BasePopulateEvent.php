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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\TemplateManager\Base;

use Symfony\Component\EventDispatcher\Event;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;

/**
 * Defines the base event raised when the website is deployed
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class BasePopulateEvent extends Event
{
    protected $templateManager;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager $templateManager
     *
     * @api
     */
    public function __construct(AlTemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    /**
     * Returns the template manager
     *
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager $templateManager
     *
     * @api
     */
    public function getTemplateManager()
    {
        return $this->templateManager;
    }

    /**
     * Sets the template manager
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager $templateManager
     *
     * @api
     */
    public function setTemplateManager(AlTemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }
}
