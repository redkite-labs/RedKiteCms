/*
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

;(function($){
    $.fn.AddAjaxLoader = function()
    {
        try
        {
            zIndex = GetTopMost();

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

            $(loader)
                    .css("z-index", zIndex)
                    .show();
            zIndex += 1;
            $(loaderImage)
                    .css("z-index", zIndex)
                    .show();
        }
        catch(e)
        {
            alert(e);
        }
    };

    $.fn.RemoveAjaxLoader = function()
    {
        $('.ajax_loader').remove();
        $('.ajax_loader_bg').remove();
    };
})($);
