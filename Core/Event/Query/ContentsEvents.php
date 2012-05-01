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
 * Defines the names for events dispatched when applying a filter on blocks
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
final class ContentsEvents
{
    const RETRIEVE_CONTENTS = 'query_contents.retrieve_contents';
    const FROM_LANGUAGE_ID = 'query_contents.from_language_id';
    const RETRIEVE_CONTENTS_BY_SLOT_NAME = 'query_contents.retrieve_contents_by_slot_name';
    const FROM_PAGE_ID = 'query_contents.from_page_id';
    const FROM_PAGE_ID_AND_SLOT_NAME = 'query_contents.from_page_id_and_slot_name';
}