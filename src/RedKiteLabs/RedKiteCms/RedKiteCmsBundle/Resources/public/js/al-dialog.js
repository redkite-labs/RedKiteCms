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
{/*
    if (id == null) id = "al_dialog";
    
    var modal = '<div id="' + id + '" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="al_dialog_title" aria-hidden="true">';
        modal += '<div class="modal-header">';
        modal += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
        modal += '<h3 class="al_dialog_title"></h3>';
        modal += '</div>';
        modal += '<div class="modal-body">';
        modal += '</div>';
        modal += '</div>';
        
        if($('body').find(id).length == 0)
        {
            $(modal).css("display", "none")
                    .appendTo('body')
                    .draggable({
                        handle: ".modal-header"
                    })
            ;
        }
    
    return modal;
    */
    try
    {
        var defaultOptions = {
        autoOpen: false,
        width: 800,
        title: 'AlphaLemon CMS',
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
            .center() //null, 0 ,200
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

    $.fn.showDialog = function(title, content, width)
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

        return this;
        
        /*
        $(this).find('.al_dialog_title').html(title);
        $(this).find('.modal-body').html(content);
        $(this).modal();
        
        return this;*/
    };
})($);