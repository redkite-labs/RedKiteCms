<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\TemplateManager\Base;

use Symfony\Component\EventDispatcher\Event;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;

/**
 * Defines the base event raised when the website is deployed
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
abstract class BasePopulateEvent extends Event
{
    protected $templateManager;

    /**
     * Constructor
     *
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager $templateManager
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
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager $templateManager
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
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager $templateManager
     *
     * @api
     */
    public function setTemplateManager(AlTemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }
}
