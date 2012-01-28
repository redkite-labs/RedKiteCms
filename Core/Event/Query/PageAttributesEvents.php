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
 * Defines the names for events dispatched when applying a filter on page attributes
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
final class PageAttributesEvents
{
    const FROM_PAGE_AND_LANGUAGE = 'query_page_attributes.from_page_and_language';
    const FROM_PERMALINK = 'query_page_attributes.from_permalink';
    const FROM_LANGUAGE_ID = 'query_page_attributes.from_language_id';    
    const FROM_PAGE_ID = 'query_page_attributes.from_page_id';    
    const FROM_PAGE_ID_WITH_LANGUAGES = 'query_page_attributes.from_page_id_with_languages';  
}
