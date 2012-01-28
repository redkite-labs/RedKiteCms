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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query;

/**
 * Defines the names for events dispatched when applying a filter on pages
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
final class PagesEvents
{
    const ACTIVE_PAGES = 'query_pages.active_pages';
    const FROM_PAGE_NAME = 'query_pages.from_page_name';
    const HOME_PAGE = 'query_pages.home_page';
}   