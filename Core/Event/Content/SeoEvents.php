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
 * @author alphalemon <webmaster@alphalemon.com>
 */
final class SeoEvents
{
    // alcms.event_listener
    
    const BEFORE_ADD_SEO = 'seo.before_seo_adding';
    const BEFORE_ADD_SEO_COMMIT = 'seo.before_add_seo_commit';
    const AFTER_ADD_SEO = 'seo.after_seo_added';

    const BEFORE_EDIT_SEO = 'seo.before_seo_editing';
    const BEFORE_EDIT_SEO_COMMIT = 'seo.before_edit_seo_commit';
    const AFTER_EDIT_SEO = 'seo.after_seo_edited';

    const BEFORE_DELETE_SEO = 'seo.before_seo_deleting';
    const BEFORE_DELETE_SEO_COMMIT = 'seo.before_delete_seo_commit';
    const AFTER_DELETE_SEO = 'seo.after_seo_deleted';
}
