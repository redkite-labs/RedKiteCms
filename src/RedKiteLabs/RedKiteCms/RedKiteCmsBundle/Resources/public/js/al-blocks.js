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
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

;var isEditorOpened = false;

(function($){
    $.fn.ToggleBodyContents =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                $('#slot_' + $(this).attr('ref')).animate({
                  height: 'toggle'
                }, 200);

                return false;
            });
        });

        return this;
    };

    $.fn.AddBlock =function(type, options, successCallback)
    {
        this.each(function()
        {
            var contentType = (type == null) ? $(this).attr('data-type') : type;
            var included = $(this).attr('data-included') == "1" ? true : false;
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/addBlock',
                data: {'page' :  $('#al_pages_navigator').html(),
                       'language' : $('#al_languages_navigator').html(),
                       'pageId' :  $('#al_pages_navigator').attr('rel'),
                       'languageId' : $('#al_languages_navigator').attr('rel'),
                       'idBlock' : $(this).attr('data-block-id'),
                       'slotName' : $(this).attr('data-slot-name'),
                       'contentType': contentType,
                       'included': included,
                       'options': options},
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(response)
                {
                    updateContentsJSon(response);
                    if (successCallback != null) {
                        successCallback();
                    }
                },
                error: function(err)
                {
                    $('body').showAlert(err.responseText, 0, 'alert-error');
                },
                complete: function()
                {
                    $('body').RemoveAjaxLoader();
                }
            });

            return false;
        });
    };

    $.fn.EditBlock =function(key, value, options, successCallback)
    {
        this.each(function()
        {
            value = (value == null) ? encodeURIComponent($(this).val()) : value;
            
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/editBlock',
                data: {'page' :  $('#al_pages_navigator').html(),
                       'language' : $('#al_languages_navigator').html(),
                       'pageId' :  $('#al_pages_navigator').attr('rel'),
                       'languageId' : $('#al_languages_navigator').attr('rel'),
                       'idBlock'    : $('body').data('idBlock'),
                       'slotName'   : $('body').data("slotName"),
                       'key'        : key,
                       'value'      : value,
                       'included'   : $('body').data('included'),
                       'options'    : options
                },
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(response)
                {
                    var activeBlock = $('body').data('activeBlock');
                    updateContentsJSon(response);
                    Holder.run();
                    if (successCallback != null) {
                        successCallback(activeBlock);
                    }
                },
                error: function(err)
                {
                    $('body').showAlert(err.responseText, 0, 'alert-error');
                },
                complete: function()
                {
                    $(document).blocksEditor('stopCursorOverEditor');
                    $('body').RemoveAjaxLoader();
                }
            });

            return false;
        });
    };

    $.fn.Delete =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                $(this).DeleteContent();

                return false;
            });
        });
    };

    $.fn.DeleteBlock =function()
    {
        if (confirm(translate('Are you sure to remove the active block'))) {
            var included = $(this).attr('data-included') == "1" ? true : false;
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/deleteBlock',
                data: {'page' :  $('#al_pages_navigator').html(),
                       'language' : $('#al_languages_navigator').html(),
                       'pageId' :  $('#al_pages_navigator').attr('rel'),
                       'languageId' : $('#al_languages_navigator').attr('rel'),                       
                       'idBlock' : $(this).attr('data-block-id'),
                       'slotName' : $(this).attr('data-slot-name'),
                       'included': included
                },
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(response)
                {
                    updateContentsJSon(response);
                },
                error: function(err)
                {
                    $('body').showAlert(err.responseText, 0, 'alert-error');
                },
                complete: function()
                {
                    $('body').RemoveAjaxLoader();
                }
            });
        }        
    };
})($);

function showMediaLibrary(html)
{
    if($('body').find("al_media_lib").length == 0)
    {
        $('<div id="al_media_lib"></div>')
                .css("display", "none")
                .appendTo('body');
    }
    $('#al_media_lib').html(html);
}

function updateContentsJSon(response, editorWidth)
{
    var slot;
    $(response).each(function(key, item)
    {
        switch(item.key)
        {
            case "message":
                $('body').showAlert(item.value);
                
                break;
            case "redraw-slot":
                slot = $('.al_' + item.slotName);
                if (slot.length > 0) {
                    slot
                        .html(item.value)
                        .find('[data-editor="enabled"]')
                        .blocksEditor('start')
                    ;
                } else {
                    var element = $('[data-name="' + item.blockId + '"]');
                    var parent = element.parent();
                    element.replaceWith(item.value);
                    $(parent)
                        .find('[data-editor="enabled"]')
                        .blocksEditor('start')
                    ;
                }
                
                break;
            case "add-block":                
                if(item.insertAfter == 'block_0')
                {
                    var slot = $('.al_' + item.slotName);
                    if (slot.length > 0) {
                        slot
                            .empty()
                            .append(item.value)
                        ;
                    } else {
                        $('[data-slot-name="' + item.slotName + '"]')
                            .replaceWith(item.value);
                    }
                }
                else
                {
                    $(item.value).insertAfter('[data-name="' + item.insertAfter + '"]');
                }
                
                $('[data-name="' + item.blockId + '"]').blocksEditor('start');
                
                break;
            case "edit-block": 
                var blockName = '[data-name="' + item.blockName + '"]';
                $(blockName)
                    .blocksEditor('stopEditElement')
                    .replaceWith(item.value);
                $(blockName).blocksEditor('startEditElement');
                
                break;
            case "remove-block":
                $('[data-name="' + item.blockName + '"]')
                    .unbind()
                    .remove()
                ;
                
                break;
            case "images-list":
                $('.al_images_list').html(item.value);
                
                break;
            case "externalAssets":
                $('.al_' + item.section  + '_list').html(item.value);
                $('[data-name="' + item.blockName + '"]').replaceWith(item.blockContent);
                
                break;
            case "editorContents":
                $('.editor_contents').html(item.value);
                
                break;
        }
    });
}
