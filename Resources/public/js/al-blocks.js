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

    $.fn.ShowEditorContent =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                $(this).OpenEditor();
            });
        });
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

        $.ajax({
            type: 'POST',
            url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_showBlocksEditor',
            data: {'page' :  $('#al_pages_navigator').val(),
                   'language' : $('#al_languages_navigator').val(),
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
                    updateContentsJSon(response);
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
                console.log('An editor has been occoured opening the editor');
                $('#al_dialog').html(err.responseText);
                $('#al_dialog').dialog('open');
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
                url: frontController + 'backend/' + $('#al_available_languages').val() + '/addBlock',
                data: {'page' :  $('#al_pages_navigator').val(),
                       'language' : $('#al_languages_navigator').val(),
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
                    $('#al_dialog').html(err.responseText);
                    $('#al_dialog').dialog('open');
                },
                complete: function()
                {
                    $('body').RemoveAjaxLoader();
                }
            });

            return false;
            
        });
    };

    $.fn.EditBlock =function(key, fileName)
    {
        this.each(function()
        {
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').val() + '/editBlock',
                data: {'page' :  $('#al_pages_navigator').val(),
                       'language' : $('#al_languages_navigator').val(),
                       'idBlock' : $('body').data('idBlock'),
                       'key'       : key,
                       'fileName'  : fileName,
                       'slotName' : $('body').data("slotName"),
                       'value'     : encodeURIComponent($(this).val())},
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
                    $('#al_dialog').html(err.responseText);
                    $('#al_dialog').dialog('open');
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
                    url: frontController + 'backend/' + $('#al_available_languages').val() + '/showExternalFilesManager',
                    data: {'page' :  $('#al_pages_navigator').val(),
                           'language' : $('#al_languages_navigator').val(),
                           'key'       : key},
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
                        $('#al_dialog').html(err.responseText);
                        $('#al_dialog').dialog('open');
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
    
    $.fn.RemoveExternalFile =function(key)
    {
        this.each(function()
        {
            $(this).click(function()
            {
                $.ajax({
                    type: 'POST',
                    url: frontController + 'backend/' + $('#al_available_languages').val() + '/removeExternalFile',
                    data: {'page' :  $('#al_pages_navigator').val(),
                           'language' : $('#al_languages_navigator').val(),
                           'idBlock' : $('body').data('idBlock'),
                           'key'       : key,
                           'fileName'  : $('.al-selected-item').attr('rel')},
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
                        $('#al_dialog').html(err.responseText);
                        $('#al_dialog').dialog('open');
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
    
    $.fn.DeleteContent =function()
    {
        var editableData = $(this).metadata();
        var idBlock = editableData.id;
        var slotName = editableData.slotName; 
        
        $.ajax({
            type: 'POST',
            url: frontController + 'backend/' + $('#al_available_languages').val() + '/deleteBlock',
            data: {'page' :  $('#al_pages_navigator').val(),
                   'language' : $('#al_languages_navigator').val(),
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
                $('#al_dialog').html(err.responseText);
                $('#al_dialog').dialog('open');
            },
            complete: function()
            {
                $('body').RemoveAjaxLoader();
            }
        });
    };

})($);


function updateContentsJSon(response)
{
    $(response).each(function(key, item)
    {
        switch(item.key)
        {
            case "message":
                $('#al_dialog').html(item.value);
                break;
            case "contents":
                $('.al_' + item.slotName).html(item.value); 
                $('#' + item.slotName + ' div.al_hide_edit_mode').HideContentsForEditMode();
                $('.al_editable').StopPageEditing(false).StartPageEditing();
                break;
            case "editor": 
                var dialogOptions = {
                    buttons:{},
                    close: function(event, ui)
                    { 
                        isEditorOpened = false;
                        if(tinyMCE != null) $('#al_html_editor').RemoveTinyMCE();   
                        $('#al_editor_dialog').dialog('destroy').remove();
                    }
                };
                InitDialog('al_editor_dialog', dialogOptions);
                $('#al_editor_dialog').html(item.value);
                var openEditor = (item.openEditor != null) ? item.openEditor : true;
                if(openEditor) $('#al_editor_dialog').dialog('open');
                break;
            case "fileManager":
                InitDialog('al_file_manager');
                $('#al_file_manager').html(item.value);
                break;
            case "editorContents":  
                $('.al-' + item.section  + '-list').html(item.value);
                break;
        }
    });
}