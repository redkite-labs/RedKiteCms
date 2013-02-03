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

var isEditorOpened = false;

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

    $.fn.ShowBlockEditor =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                $(this).OpenEditor();
            });
        });

        return this;
    };

    $.fn.OpenEditor =function()
    {
        if(isEditorOpened)
        {
            return;
        }

        var editableData = $(this).metadata();
        var idBlock = editableData.id;
        var slotName = editableData.slotName;
        var editorWidth = editableData.editorWidth;
        $.ajax({
            type: 'POST',
            url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/al_showBlocksEditor',
            data: {'page' :  $('#al_pages_navigator').html(),
                   'language' : $('#al_languages_navigator').html(),
                   'pageId' :  $('#al_pages_navigator').attr('rel'),
                   'languageId' : $('#al_languages_navigator').attr('rel'),
                   'idBlock' : idBlock,
                   'slotName' : slotName},
            beforeSend: function()
            {
                $('body').AddAjaxLoader();
            },
            success: function(response)
            {
                try
                {
                    $.parseJSON(response);
                    updateContentsJSon(response, editorWidth);
                }
                catch(e)
                {
                    showMediaLibrary(response);
                }

                $('body').data('idBlock', idBlock).data('slotName', slotName);
                isEditorOpened = true;
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

    $.fn.AddBlock =function(type)
    {
        this.each(function()
        {
            var data = $(this).metadata();
            var idBlock = data.id;
            var slotName = data.slotName;
            var contentType = (type == null) ? data.contentType : type;
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/addBlock',
                data: {'page' :  $('#al_pages_navigator').html(),
                       'language' : $('#al_languages_navigator').html(),
                       'pageId' :  $('#al_pages_navigator').attr('rel'),
                       'languageId' : $('#al_languages_navigator').attr('rel'),
                       'idBlock' : idBlock,
                       'slotName' : slotName,
                       'contentType': contentType},
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

            return false;
        });
    };

    $.fn.EditBlock =function(key, value, options)
    {
        this.each(function()
        {
            value = (value == null) ? encodeURIComponent($(this).val()) : value;

            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/editBlock',
                data: {'page' :  $('#al_pages_navigator').html(),
                       'language' : $('#al_languages_navigator').html(),
                       'pageId' :  $('#al_pages_navigator').attr('rel'),
                       'languageId' : $('#al_languages_navigator').attr('rel'),
                       'idBlock'    : $('body').data('idBlock'),
                       'slotName'   : $('body').data("slotName"),
                       'key'        : key,
                       'value'      : value,
                       'options'    : options},
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

            return false;
        });
    };

    $.fn.ShowExternalFilesManager =function(key)
    {
        this.each(function()
        {
            $(this).click(function()
            {
                $.ajax({
                    type: 'POST',
                    url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/showExternalFilesManager',
                    data: {'page' :  $('#al_pages_navigator').html(),
                           'language' : $('#al_languages_navigator').html(),
                           'pageId' :  $('#al_pages_navigator').attr('rel'),
                           'languageId' : $('#al_languages_navigator').attr('rel'),
                           'key'      : key},
                    beforeSend: function()
                    {
                        $('body').AddAjaxLoader();
                    },
                    success: function(html)
                    {
                        showMediaLibrary(html);
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
        });
    };

    $.fn.AddExternalFile =function(field, file)
    {
        this.each(function()
        {
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/addExternalFile',
                data: {'page' :  $('#al_pages_navigator').html(),
                       'language' : $('#al_languages_navigator').html(),
                       'pageId' :  $('#al_pages_navigator').attr('rel'),
                       'languageId' : $('#al_languages_navigator').attr('rel'),
                       'idBlock' : $('body').data('idBlock'),
                       'slotName' : $('body').data("slotName"),
                       'field'       : field,
                       'file'     : file},
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

            return false;
        });
    };

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

    $.fn.RemoveExternalFile =function(field)
    {
        this.each(function()
        {
            $(this).click(function()
            {
                $.ajax({
                    type: 'POST',
                    url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/removeExternalFile',
                    data: {'page' :  $('#al_pages_navigator').html(),
                           'language' : $('#al_languages_navigator').html(),
                           'pageId' :  $('#al_pages_navigator').attr('rel'),
                           'languageId' : $('#al_languages_navigator').attr('rel'),
                           'idBlock'  : $('body').data('idBlock'),
                           'slotName'  : $('body').data('slotName'),
                           'field'    : field,
                           'file'     : $('.al_selected_item').attr('rel')},
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

                return false;
            });
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
        var editableData = $(this).metadata();
        var idBlock = editableData.id;
        var slotName = editableData.slotName;

        $.ajax({
            type: 'POST',
            url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/deleteBlock',
            data: {'page' :  $('#al_pages_navigator').html(),
                   'language' : $('#al_languages_navigator').html(),
                   'pageId' :  $('#al_pages_navigator').attr('rel'),
                   'languageId' : $('#al_languages_navigator').attr('rel'),
                   'slotName' : slotName,
                   'idBlock' : idBlock},
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
    };

})($);


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
                slot = '.' + item.slotName;
                $(slot).html(item.value);
                $(slot).find('.al_editable').StartToEdit();
                break;
            case "add-block":
                slot = '.' + item.slotName;
                if(item.insertAfter == 'block_0')
                {
                    $(slot).empty().append(item.value);
                    $(slot).find('.al_editable').StartToEdit();
                }
                else
                {
                    $(item.value).insertAfter('#' + item.insertAfter).StartToEdit();
                }
                break;
            case "edit-block":
                $('#' + item.blockName).html(item.value).StartToEdit();
                break;
            case "remove-block":
                $('#' + item.blockName).remove();
                break;
            case "images-list":
                $('.al_images_list').html(item.value);
                break;
            case "editor":
                var openEditor = (item.openEditor != null) ? item.openEditor : true;
                if(openEditor) {
                    var dialogOptions = {
                        buttons:{},
                        width: editorWidth,
                        zIndex: 120000,
                        title: 'AlphaLemon CMS - Editor Contents',
                        close: function(event, ui)
                        {
                            isEditorOpened = false;
                            if(tinyMCE != null) $('#al_html_editor').RemoveTinyMCE();
                            $('#al_editor_dialog').dialog('destroy').remove();
                        }
                    };

                    InitDialog('al_editor_dialog', dialogOptions);
                    $('#al_editor_dialog').html(item.value);
                    $('#al_editor_dialog').dialog('open');
                     /*                       
                    var editor = InitDialog('al_editor_dialog');                    
                    $(editor).showDialog('Editor Contents', item.value);*/
                }

                break;
            case "externalAssets":
                $('.al_' + item.section  + '_list').html(item.value);
                break;
            case "editorContents":
                $('.editor_contents').html(item.value);
                break;

        }
    });
}
