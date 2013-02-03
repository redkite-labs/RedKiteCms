/*
 * This file is part of the BusinessCarouselBundle and it is distributed
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
    $.fn.AddItem = function(blockId)
    {
        this.each(function()
        {
            $(this).click(function()
            {
                showItemForm(blockId);

                return false;
            });
        });
    };

    $.fn.EditItem = function(blockId)
    {
        this.each(function()
        {
            $(this).click(function()
            {
                showItemForm(blockId, $(this).attr('rel'));

                return false;
            });
        });
    };

    $.fn.DeleteItem = function(blockId)
    {
        this.each(function()
        {
            $(this).click(function()
            {
                deleteItem(blockId, $(this).attr('rel'));

                return false;
            });
        });
    };

    $.fn.ListItems = function(blockId)
    {
        this.each(function()
        {
            $(this).click(function()
            {
                $.ajax({
                    type: 'GET',
                    url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/al_listJsonItems',
                    data: {
                        'page' :  $('#al_pages_navigator').html(),
                        'language' : $('#al_languages_navigator').html(),
                        'blockId' : blockId
                    },
                    beforeSend: function()
                    {
                        $('body').AddAjaxLoader();
                    },
                    success: function(html)
                    {
                        $('#al_editor_dialog').html(html);
                        $('#al_editor_dialog').dialog('open');
                    },
                    error: function(err)
                    {
                        $('#al_editor_dialog').html(err.responseText);
                        $('#al_editor_dialog').dialog('open');
                    },
                    complete: function()
                    {
                        $('body').RemoveAjaxLoader();
                    }
                });

                return false;
            });
        });
    }
})($);

function showItemForm(blockId, id)
{
    if(id == null) id = -1;

    $.ajax({
      type: 'GET',
      url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/al_showJsonItem',
      data: {
        'page' :  $('#al_pages_navigator').html(),
        'language' : $('#al_languages_navigator').html(),
        'blockId' : blockId,
        'itemId' : id
      },
      beforeSend: function()
      {
        $('body').AddAjaxLoader();
      },
      success: function(response)
      {
        fillItemForm(response);
      },
      error: function(err)
      {
        $('#al_error').html(err.responseText);
      },
      complete: function()
      {
        $('body').RemoveAjaxLoader();
      }
    });

    return false;
}

function deleteItem(blockId, id)
{
    if(confirm("Are you sure you want to remove the selected item?"))
    {
        $.ajax({
            type: 'POST',
            url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/al_deleteJsonItem',
            data: {
                'page' :  $('#al_pages_navigator').html(),
                'language' : $('#al_languages_navigator').html(),
                'blockId' : blockId,
                'RemoveItem' : id
            },
            beforeSend: function()
            {
                $('body').AddAjaxLoader();
            },
            success: function(response)
            {
                fillItemForm(response);
            },
            error: function(err)
            {
                $('#al_error').html(err.responseText);
            },
            complete: function()
            {
                $('body').RemoveAjaxLoader();
            }
        });
    }

    return false;
}

function fillItemForm(response)
{
    $(response).each(function(key, item)
    {
        switch(item.key)
        {
            case "editor":
                $('#al_editor_dialog').html(item.value);
                break;
            case "content":
                $('#block_' + item.id).html(item.value);
                break;
            case "list":
                $('#al_editor_dialog').html(item.value);
                break;
        }
    });
}
