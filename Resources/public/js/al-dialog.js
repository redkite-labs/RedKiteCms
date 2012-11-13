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

InitDialog = function(id, options)
{
    try
    {
        var defaultOptions = {
        autoOpen: false,
        width: 800,
        buttons: {
            "Close": function() {
                $(this).dialog("close");
            }
        }};

        if(id == null) id = "al_dialog";
        if(options !== null) $.extend (defaultOptions, options);

        if($('body').find(id).length == 0)
        {
            $('<div id="' + id + '"></div>')
                    .css("display", "none")
                    .appendTo('body');
        }

        $('#' + id).dialog(defaultOptions);
    }
    catch(e)
    {
        alert(e);
    }
};

GetTopMost = function()
{
    // Credits for this snippet goes to Studio-42 http://elfinder.org
    var zindex = 100;
    $('body').find(':visible').each(function() {
            var $this = $(this), z;
            if ($this.css('position') != 'static' && (z = parseInt($this.zIndex())) > zindex) {
                zindex = z + 1;
            }
    });
    
    return zindex + 10;
};

(function($){
    $.fn.showAutoCloseDialog = function(html, width, delay)
    {
        if (width == null) width = 400;
        if (delay == null) delay = 2000;

        var zIndexDialog = GetTopMost();
        var options = {
            width: width,
            zIndex: zIndexDialog,
            buttons: {}
        };

        var message = '<div class="al_success_big">' + html + '</div>';
        message += '<div class="al_autoclose">This window closes automatically in 2 seconds</div>';

        InitDialog('al_message_success', options);
        $('#al_message_success')
            .html(message)
            .dialog('open')
            .delay(delay)
            .fadeOut(function(){ $(this).dialog("close") });

        return false;
    };

    $.fn.showDialog = function(html, width)
    {
        if (width == null) width = 800;

        var zIndexDialog = GetTopMost();
        var options = {
            width: width,
            zIndex: zIndexDialog,
            hide: 'explode'
        };
        InitDialog('al_dialog', options);
        $('#al_dialog')
            .html(html)
            .dialog('open');

        return false;
    };
})($);