/*
 * This file is part of the RedKite CMS Application and it is distributed
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

;InitDialog = function(id, options)
{
    try
    {
        var defaultOptions = {
        autoOpen: false,
        width: 800,
        title: 'RedKiteCms',
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
    $.fn.showAlert = function(html, delay, type)
    {
        if (delay == null) delay = 1500;
        if (type == null) type = 'alert-success';

        
        var alertBody = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        alertBody += '<span id="al_alert_message">' + html + '</span>';
        
        var zIndexDialog = GetTopMost();
        var alertBox = $('<div id="al_alert" class="alert"></div>')
            .html(alertBody)
            .addClass(type)
            .appendTo('body')
            .center()
            .css('z-index', zIndexDialog)
            .show()
        ;
           
        if (delay > 0) {
            $(alertBox)
                .delay(delay)
                .fadeOut(function(){ $(this).alert('close'); })
            ;
        }
        
        return this;
    };

    $.fn.showDialog = function(content, customOptions)
    {
        var zIndexDialog = GetTopMost();
        var options = {
            width: 400,
            zIndex: zIndexDialog,
            hide: 'explode'
        };
        
        
        if (options !== null) {
            $.extend (options, customOptions);
        }
        
        InitDialog('al_dialog', options);
        $('#al_dialog')
            .html(content)
            .dialog('open');

        return this;
    };
})($);
