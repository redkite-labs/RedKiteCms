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
            $('.al_element_selector').unbind().languages('select');
            $("#al_language_saver").unbind().languages('save');
            $(".rk-language-remover").unbind().languages('remove');
        },
        select: function()
        {
            this.each(function()
            {
                $(this).click(function()
                {
                    // Deselects the current selected page and selects the new one
                    if(!$(this).hasClass('al_element_selected'))
                    {
                        $('#al_languages_list .al_element_selected').removeClass('al_element_selected');
                        $(this).addClass('al_element_selected');
                        LoadLanguageAttributes();
                    }
                    else
                    {
                        $(this).removeClass('al_element_selected');
                        $("#al_languages_form").ResetFormElements();
                    }

                    return false;
                });
            });
        },
        save: function()
        {
            $(this).click(function()
            {
                var languageId = retrieveIdLanguage();
                try {
                    var isMain = ($('#languages_isMain').is(':checked')) ? 1 : 0;
                    $.ajax({
                        type: 'POST',
                        url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_saveLanguage',
                        data: {
                            'languageId' : languageId,
                            'newLanguage' : $('#languages_language  option:selected').val(),
                            'page' :  $('#al_pages_navigator').html(),
                            'language' : $('#al_languages_navigator').html(),
                            'isMain' : isMain
                        },
                        beforeSend: function()
                        {
                            $('body').AddAjaxLoader();
                        },
                        success: function(response)
                        {
                            renderResponse(response);
                            $("#al_languages_form").ResetFormElements();
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
                    var operation = (languageId == 0) ? 'adding' : 'editing';
                    $('body').showAlert('An unespected error occoured in al-languages file while ' + operation + ' a language. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a href="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
                }
            });
        },
        remove: function()
        {
            $(this).click(function()
            {
                if(confirm(translate("Are you sure to remove the selected language")))
                {
                    try{
                        $.ajax({
                            type: 'POST',
                            url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_deleteLanguage',
                            data: {
                                'languageId' : $(this).attr('data-language-id'),
                                'page' :  $('#al_pages_navigator').html(),
                                'language' : $('#al_languages_navigator').html()
                            },
                            beforeSend: function()
                            {
                                $('body').AddAjaxLoader();
                            },
                            success: function(response)
                            {
                                renderResponse(response);
                                $("#al_languages_form").ResetFormElements();
                            },
                            error: function(err)
                            {
                                $('body').showAlert(err.responseText, 0, 'alert-error alert-danger');
                            },
                            complete: function()
                            {
                                $('#al_dialog').dialog('open');
                                $('body').RemoveAjaxLoader();
                            }
                          });
                    }
                    catch(e){
                        $('body').showAlert('An unespected error occoured in al-languages file while removing a language. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a href="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
                    }
                }
            });
        },
        change: function()
        {
            $("#languages_idLanguage").change(function()
            {
                LoadLanguageAttributes();
            });
        }
    }
    
    function LoadLanguageAttributes(idLanguage)
    {
        try{
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_loadLanguageAttributes',
                data: {'languageId' : retrieveIdLanguage()},
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(response)
                {
                    $(response).each(function(key, el) {
                        switch(el.name) {
                            case '#languages_isMain':
                                if (el.value == 1) {
                                    $(el.name).attr('checked', 'checked');
                                }
                                else {
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
            $('body').showAlert('An unespected error occoured in al-languages file while retrieving language\'s attributes. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a href="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
        }
    }
    
    function renderResponse(response)
    {
        $(response).each(function(key, item)
        {
            switch(item.key)
            {
                case "message":
                    $('body').showAlert(item.value);
                    break;
                case "languages":
                    var idSelectedLanguage = retrieveIdLanguage();
                    $('#al_languages_list').html(item.value);
                    $('#al_languages_list .al_element_selector').each(function(key, item)
                    {
                        if(idSelectedLanguage == $(item).attr('data-language-id'))
                        {
                            $(item).addClass('al_element_selected');
                            return;
                        }
                    });
                    break;
                case "languages_menu":
                    $('#al_languages_navigator_box').html(item.value);
                    $('.al_language_item').click(function()
                    {
                        Navigate($(this).attr('rel'), $('#al_pages_navigator').html());

                        return false;
                    });
                    break;
            }
        });

        $('body').languages('init');
    }
    
    function retrieveIdLanguage()
    {
        var idLanguage = $('#al_languages_list .al_element_selected').attr('data-language-id');
        if (idLanguage == null) {
            idLanguage = 0;
        }

        return idLanguage;
    }
    
    $.fn.languages = function( method ) {    
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.inlinelist' );
        }   
    };
})($);