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
    $.fn.SelectPage =function()
    {
        this.each(function() 
        {
            $(this).click(function()
            {
                // Deselects the current selected page and selects the new one                
                ResetWholeForm();
                if(!$(this).hasClass('al_element_selected'))
                {
                    $('#al_pages_list .al_element_selected').removeClass('al_element_selected');
                    $(this).addClass('al_element_selected');

                    if($('#page_attributes_idLanguage').val() != 'none')
                    {
                        LoadPageAttributes($(this).attr('ref'));
                    }
                    else
                    {
                        $('#al_select_languages_reminder').show();
                        $('#page_attributes_idPage').val($(this).attr('ref'));
                    }
                }
                else
                {
                    $(this).removeClass('al_element_selected');
                    $('#page_attributes_idPage').val('');
                    $('#al_select_languages_reminder').hide();
                }

                return false;
            });
        });
    };
})($);

function ResetWholeForm()
{
    $("#al_page_form").ResetFormElements();
    $("#al_attributes_form").ResetFormElements();
}


function InitPagesCommands()
{
    $("#al_page_saver").click(function()
    {
        var isHome = ($('#pages_isHome').is(':checked')) ? 1 : 0;        
        $.ajax({
            type: 'POST',
            url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_savePage',
            data: {'language' : $('#al_languages_navigator').val(),
                   'page' : $('#al_pages_navigator').val(),
                   'pageId' : $('#page_attributes_idPage').val(),
                   'languageId' : $('#page_attributes_idLanguage').val(),
                   'pageName' : $('#pages_pageName').val(),
                   'templateName' : $('#pages_template').val(),
                   'permalink' : $('#page_attributes_permalink').val(),
                   'isHome' : isHome,
                   'title' : $('#page_attributes_title').val(),
                   'description' : $('#page_attributes_description').val(),
                   'keywords' : $('#page_attributes_keywords').val()
               },
            beforeSend: function()
            {
                $('body').AddAjaxLoader();
            },
            success: function(response)
            {
                if($('#page_attributes_idPage').val() == '') ResetWholeForm();
                UpdatePagesJSon(response);
            },
            error: function(err)
            {
                $('#al_dialog').html(err.responseText);
            },
            complete: function()
            {
                $('#al_dialog').dialog('open');
                $('body').RemoveAjaxLoader();
            }
          });
    });

    $("#al_pages_remover").click(function()
    {
        if(confirm("Are you sure to remove the page and its attributes?"))
        {
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_deletePage',
                data: {'language' : $('#al_languages_navigator').val(),
                       'page' : $('#al_pages_navigator').val(),
                       'languageId' : $('#page_attributes_idLanguage').val(),
                       'pageId' : $('#page_attributes_idPage').val()
                   },
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(response)
                {
                    UpdatePagesJSon(response);
                    if($('#page_attributes_idLanguage').val() != 'none')
                    {
                        $("#al_attributes_form").ResetFormElements();
                    }
                    else
                    {
                        ResetWholeForm();
                        $('#page_attributes_idPage').val('');
                    }
                    $('#al_select_languages_reminder').hide();
                },
                error: function(err)
                {
                    $('#al_dialog').html(err.responseText);
                },
                complete: function()
                {
                    $('#al_dialog').dialog('open');
                    $('body').RemoveAjaxLoader();
                }
              });
        }
    });

    $("#page_attributes_idLanguage").change(function()
    {
        if($("#page_attributes_idLanguage").val() != 'none')
        {
            var idPage = $('#al_pages_list .al_element_selected').attr('ref');
            if(idPage)
            {
                LoadPageAttributes(idPage);
            }
        }
        else
        {
            $('#al_pages_list .al_element_selected').removeClass('al_element_selected');
            ResetWholeForm();
        }

        $('#al_select_languages_reminder').hide();
    });
}

function UpdatePagesJSon(response)
{
    $(response).each(function(key, item)
    {
        switch(item.key)
        {
            case "message":
                $('#al_dialog').html(item.value);
                break;
            case "pages":
                var idSelectedPage = $('#al_pages_list .al_element_selected').attr('ref'); 
                $('#al_pages_list').html(item.value);
                $('#al_pages_list .al_element_selector').each(function(key, item)
                {
                    if(idSelectedPage == $(item).attr('ref'))
                    {
                        $(item).addClass('al_element_selected');
                        return;
                    }
                });
                break;
            case "pages_menu":
                $('#al_pages_navigator_box').html(item.value);
                $('#al_pages_navigator').change(function()
                {
                    Navigate();
                });
                break;
            case "permalink":
                if($('#page_attributes_permalink').val() != item.value) $('#page_attributes_permalink').val(item.value);
                break;
        }
    });

    ObservePages();
}

function ObservePages()
{
    $('.al_element_selector').unbind().SelectPage();
}

function LoadPageAttributes(idPage)
{
    ResetWholeForm();
    $('#page_attributes_idPage').val(idPage);
    $.ajax({
        type: 'POST',
        url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_loadPageAttributes',
        data: {'language' : $('#al_languages_navigator').val(),
               'page' : $('#al_pages_navigator').val(),
               'languageId' : $('#page_attributes_idLanguage').val(),
               'pageId' : idPage},
        beforeSend: function()
        {
            $('body').AddAjaxLoader();
        },
        success: function(response)
        {
            $(response).each(function(key, el)
            {
                switch(el.name)
                {
                    case '#pages_isHome':
                    case '#pages_published':
                        if(el.value == 1)
                        {
                            $(el.name).attr('checked', 'checked');
                        }
                        else
                        {
                            $(el.name).removeAttr("checked");
                        }
                        break;
                    default:
                        $(el.name).val(el.value);
                        break;
                }
            });
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
}