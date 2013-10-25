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

                    if($('#seo_attributes_idLanguage option:selected').val() != 'none')
                    {
                        LoadSeoAttributes($(this).attr('ref'));
                    }
                    else
                    {
                        $('#al_select_languages_reminder').show();
                        $('#seo_attributes_idPage').val($(this).attr('ref'));
                    }
                }
                else
                {
                    $(this).removeClass('al_element_selected');
                    $('#seo_attributes_idPage').val('');
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
    $("#al_sitemp_form").ResetFormElements();
}

function InitPagesCommands()
{
    $("#al_page_saver").click(function()
    {
        var languageId = $('#seo_attributes_idLanguage option:selected').val();
        try{ 
            var isHome = ($('#pages_isHome').is(':checked')) ? 1 : 0;
            var isPublished = ($('#pages_isPublished').is(':checked')) ? 1 : 0;
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_savePage',
                data: {'language' : $('#al_languages_navigator').html(),
                       'page' : $('#al_pages_navigator').html(),
                       'pageId' : $('#seo_attributes_idPage').val(),
                       'languageId' : languageId,
                       'pageName' : $('#pages_pageName').val(),
                       'templateName' : $('#pages_template').val(),
                       'permalink' : $('#seo_attributes_permalink').val(),
                       'isHome' : isHome,
                       'isPublished' : isPublished,
                       'title' : $('#seo_attributes_title').val(),
                       'description' : $('#seo_attributes_description').val(),
                       'keywords' : $('#seo_attributes_keywords').val(),
                       'sitemapChangeFreq' : $('#seo_attributes_sitemapChangeFreq').val(),
                       'sitemapPriority' : $('#seo_attributes_sitemapPriority').val()                   
                   },
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(response)
                {
                    if ($('#seo_attributes_idPage').val() == '') {
                        ResetWholeForm();
                    }
                    UpdatePagesJSon(response);
                },
                error: function(err)
                {
                    $('body').showAlert(err.responseText, 0, 'alert-error alert-danger');
                },
                complete: function()
                {
                    $('body').RemoveAjaxLoader();
                }
            });
        }
        catch(e){
            var operation = (languageId == null || languageId == 'none') ? 'adding' : 'editing';
            $('body').showAlert('An unespected error occoured in al-pages file while ' + operation + ' a page. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a href="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
        }
        
        return false;
    });

    $("#al_pages_remover").click(function()
    {
        if(confirm(translate("Are you sure to remove the page and its attributes")))
        {
            try{
                $.ajax({
                    type: 'POST',
                    url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_deletePage',
                    data: {'language' : $('#al_languages_navigator').html(),
                           'page' : $('#al_pages_navigator').html(),
                           'languageId' : $('#seo_attributes_idLanguage option:selected').val(),
                           'pageId' : $('#seo_attributes_idPage').val()
                       },
                    beforeSend: function()
                    {
                        $('body').AddAjaxLoader();
                    },
                    success: function(response)
                    {
                        UpdatePagesJSon(response);

                        ResetWholeForm();
                        $('#seo_attributes_idPage').val('');                    
                        $('#al_select_languages_reminder').hide();
                    },
                    error: function(err)
                    {
                        $('body').showAlert(err.responseText, 0, 'alert-error alert-danger');
                    },
                    complete: function()
                    {
                        $('body').RemoveAjaxLoader();
                    }
                });
            }
            catch(e){
                $('body').showAlert('An unespected error occoured in al-pages file while removing a page. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a href="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
            }
        }
        
        return false;
    });

    $("#seo_attributes_idLanguage").change(function()
    {
        if($("#seo_attributes_idLanguage option:selected").val() != 'none')
        {
            var idPage = $('#al_pages_list .al_element_selected').attr('ref');
            if(idPage)
            {
                LoadSeoAttributes(idPage);
            }
        }
        else
        {
            $('#al_pages_list .al_element_selected').removeClass('al_element_selected');
            ResetWholeForm();
        }

        $('#al_select_languages_reminder').hide();
    
        return false;  
    });
}

function UpdatePagesJSon(response)
{
    $(response).each(function(key, item)
    {
        switch(item.key)
        {
            case "message":
                $('body').showAlert(item.value);
                
            case "pages_list":
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
            case "pages":
                $('#al_pages_navigator_box').html(item.value);
                $('.al_page_item').click(function()
                {
                    Navigate($('#al_languages_navigator').html(), $(this).attr('rel'));
                    
                    return false;
                });
                
                break;
            case "permalinks":
                $('#al_page_name').remove()
                $('body').append(item.value);
                break;
            case "permalink":
                if($('#seo_attributes_permalink').val() != item.value) $('#seo_attributes_permalink').val(item.value);
                break;
        }
    });

    ObservePages();
}

function ObservePages()
{
    $('.al_element_selector').unbind().SelectPage();
}

function LoadSeoAttributes(idPage)
{
    try{
        ResetWholeForm();
        $('#seo_attributes_idPage').val(idPage);
        $.ajax({
            type: 'POST',
            url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_loadSeoAttributes',
            data: {'language' : $('#al_languages_navigator').html(),
                   'page' : $('#al_pages_navigator').html(),
                   'languageId' : $('#seo_attributes_idLanguage option:selected').val(),
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
                        case '#pages_isPublished': 
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
                $('body').showDialog(err.responseText);
            },
            complete: function()
            {
                $('body').RemoveAjaxLoader();
            }
        });  
    }
    catch(e){
        $('body').showAlert('An unespected error occoured in al-pages file while loading page\'s attributes. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a href="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
    }
}
