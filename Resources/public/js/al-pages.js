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
    var methods = {
        init: function()
        {
            $('.al_element_selector').unbind().pages('select');
            $("#al_page_saver").unbind().pages('save');
            $("#al_page_save_as_new").unbind().pages('save_as_new');            
            $(".rk-page-remover").unbind().pages('remove');
            $(".rk-page-language").unbind().pages('changeLanguage');

        },
        select: function()
        {
            this.each(function()
            {
                $(this).click(function()
                {
                    // Deselects the current selected page and selects the new one
                    ResetWholeForm();
                    if(!$(this).hasClass('al_element_selected'))
                    {
                        Select($(this));
                        if($('#seo_attributes_idLanguage option:selected').val() != 'none')
                        {
                            LoadSeoAttributes($(this).attr('data-page-id'));
                        }
                        else
                        {
                            $('#seo_attributes_idPage').val($(this).attr('data-page-id'));
                        }
                    }
                    else
                    {
                        $(this).removeClass('al_element_selected');
                        $('#seo_attributes_idPage').val('');
                    }

                    return false;
                });
            });
        },
        save: function()
        {
            $(this).click(function()
            {
                var pageId = $('#al_pages_list .al_element_selected').attr('data-page-id');
                var languageId = 0; 
                if (pageId != null) {
                     languageId = $('#rk_language_' + pageId).val();
                }
                
                save(languageId, pageId)

                return false;
            });
        },
        save_as_new: function()
        {
            $(this).click(function()
            {
                var pageId = $('#al_pages_list .al_element_selected').attr('data-page-id');                
                save(0, pageId)

                return false;
            });
        },
        remove: function()
        {
            $(this).click(function()
            {
                if(confirm(translate("Are you sure to remove the page and its attributes")))
                {
                    try{
                        var pageId = $(this).attr('data-page-id');

                        $.ajax({
                            type: 'POST',
                            url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_deletePage',
                            data: {'language' : $('#al_languages_navigator').html(),
                                   'page' : $('#al_pages_navigator').html(),
                                   'languageId' : $('#rk_language_' + pageId).val(),
                                   'pageId' : pageId
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
                        $('body').showAlert('An unespected error occoured in al-pages file while removing a page. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a hdata-page-id="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
                    }
                }

                return false;
            });
        },
        changeLanguage: function()
        {
            $(this).change(function()
            {
                var idPage = $(this).attr('rel');
                if (idPage) {
                    LoadSeoAttributes(idPage);
                    Select($('#rk_page_' + idPage));
                }

                return false;  
            });
        }
    };
    
    function save(languageId, pageId)
    {
        try{ 
            var isHome = ($('#pages_isHome').is(':checked')) ? 1 : 0;
            var isPublished = ($('#pages_isPublished').is(':checked')) ? 1 : 0;
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_savePage',
                data: {'language' : $('#al_languages_navigator').html(),
                       'page' : $('#al_pages_navigator').html(),
                       'pageId' : pageId,
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
                    if (pageId == null) {
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
            $('body').showAlert('An unespected error occoured in al-pages file while ' + operation + ' a page. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a hdata-page-id="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
        }
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
                       'languageId' : $('#rk_language_' + idPage).val(),
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
                    $('body').showAlert(err.responseText, 0, 'alert-error alert-danger');
                },
                complete: function()
                {
                    $('body').RemoveAjaxLoader();
                }
            });  
        }
        catch(e){
            $('body').showAlert('An unespected error occoured in al-pages file while loading page\'s attributes. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a hdata-page-id="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
        }
    }
    
    function Select(element)
    {
        $('#al_pages_list .al_element_selected').removeClass('al_element_selected');
        element.addClass('al_element_selected');
    }

    function ResetWholeForm()
    {
        $("#al_page_form").ResetFormElements();
        $("#al_attributes_form").ResetFormElements();
        $("#al_sitemp_form").ResetFormElements();
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
                    var idSelectedPage = $('#al_pages_list .al_element_selected').attr('data-page-id');
                    $('#al_pages_list').html(item.value);
                    $('#al_pages_list .al_element_selector').each(function(key, item)
                    {
                        if(idSelectedPage == $(item).attr('data-page-id'))
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

        $('body').pages('init');
    }
    
    $.fn.pages = function( method ) {    
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.inlinelist' );
        }   
    };
})($);