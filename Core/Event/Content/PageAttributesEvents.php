<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content;

/**
 * Defines the names for the page events
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
final class PageAttributesEvents
{
    // alcms.event_listener
    
    const BEFORE_ADD_PAGE_ATTRIBUTES = 'page_attributes.before_page_attributes_adding';
    const AFTER_ADD_PAGE_ATTRIBUTES = 'page_attributes.after_page_attributes_added';

    const BEFORE_EDIT_PAGE_ATTRIBUTES = 'page_attributes.before_page_attributes_editing';
    const AFTER_EDIT_PAGE_ATTRIBUTES = 'page_attributes.after_page_attributes_edited';

    const BEFORE_DELETE_PAGE_ATTRIBUTES = 'page_attributes.before_page_attributes_deleting';
    const AFTER_DELETE_PAGE_ATTRIBUTES = 'page_attributes.after_page_attributes_deleted';
}
