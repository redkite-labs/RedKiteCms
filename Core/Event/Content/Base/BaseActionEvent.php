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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Base;

use Symfony\Component\EventDispatcher\Event;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;

/**
 * Defines a base event raised from a ContentManager
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class BaseActionEvent extends Event
{
    protected $alManager;

    /**
     * Constructor
     *
     * @param AlContentManagerInterface $alBlockManager
     */
    public function __construct(AlContentManagerInterface $alBlockManager = null)
    {
        $this->alManager = $alBlockManager;
    }

    /**
     * Returns the current AlContentManager object
     *
     * @return AlBlockManager
     */
    public function getContentManager()
    {
        return $this->alManager;
    }
    
    /**
     * Sets the current AlContentManager object
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface $value
     */
    public function setContentManager(AlContentManagerInterface $value)
    {
        $this->alManager = $value;
    }
}
