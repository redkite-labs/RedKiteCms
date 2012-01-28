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

(function($){
    $.fn.AddAjaxLoader = function()
    {
        var loaderImage = document.createElement("DIV");
        $(loaderImage).addClass("ajax_loader")
                 .css("top", $(document).scrollTop())
                 .css("width", screen.width + "px")
                 .css("height", screen.height + "px");
                 
        var loader = document.createElement("DIV");
        $(loader).addClass("ajax_loader_bg")
                 .css("top", $(document).scrollTop())
                 .css("width", screen.width + "px")
                 .css("height", screen.height + "px");
                 
        $(this).append(loader);
        $(this).append(loaderImage);
        $(loader).show();
        $(loaderImage).show();
    };

    $.fn.RemoveAjaxLoader = function()
    {
        $('.ajax_loader').remove();
        $('.ajax_loader_bg').remove();
    };
})($);