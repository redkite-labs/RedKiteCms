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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\PageTree;

/**
 * Defines the names for the PageTree events
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
final class PageTreeEvents
{
    // alcms.event_listener
    const BEFORE_PAGE_TREE_SETUP = 'page_tree.before_setup';
    const AFTER_PAGE_TREE_SETUP = 'page_tree.after_setup';
    
    const BEFORE_PAGE_TREE_REFRESH = 'page_tree.before_refresh';
    const AFTER_PAGE_TREE_REFRESH = 'page_tree.after_refresh';
}