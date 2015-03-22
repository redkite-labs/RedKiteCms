/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

$(document).ready(function()
{
    $('#rkcms-pages-editor-table').each(function(){
        var element = $(this);
        var pageValues = ko.utils.parseJson(decodeURIComponent(element.attr("data-pages")));
        var pages = new PageCollectionModel(pageValues);
        ko.applyBindings(pages, document.getElementById('rkcms-pages-editor-table'));
    });
});