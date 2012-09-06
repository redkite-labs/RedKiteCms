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
    $.fn.SelectLanguage =function()
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
                    LoadLanguageAttributes($(this).attr('ref'));
                }
                else
                {
                    $(this).removeClass('al_element_selected');
                    $("#al_attributes_form").ResetFormElements();
                }

                return false;
            });
        });
    }
})($);

function retrieveIdLanguage()
{
    var idLanguage = $('#al_languages_list .al_element_selected').attr('ref');
    if(idLanguage == null) idLanguage = 0;

    return idLanguage;
}

function InitLanguagesCommands()
{
    $("#al_language_saver").click(function()
    {
        var isMain = ($('#languages_isMain').is(':checked')) ? 1 : 0;
        $.ajax({
            type: 'POST',
            url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_saveLanguage',
            data: {'idLanguage' : retrieveIdLanguage(),
                   'newLanguage' : $('#languages_language').val(),
                   'language' : $('#al_languages_navigator option:selected').val(),
                   'page' : $('#al_pages_navigator option:selected').val(),
                   'isMain' : isMain
               },
            beforeSend: function()
            {
                $('body').AddAjaxLoader();
            },
            success: function(response)
            {
                UpdateLanguagesJSon(response);
                if(!$('#al_languages_list .al_element_selected').attr('ref')) $("#al_attributes_form").ResetFormElements();
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

    $("#al_languages_remover").click(function()
    {
        if(confirm("Are you sure to remove the selected language?"))
        {
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_deleteLanguage',
                data: {'idLanguage' : retrieveIdLanguage(),
                       'language' : $('#al_languages_navigator option:selected').val(),
                       'page' : $('#al_pages_navigator option:selected').val()},
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(response)
                {
                    UpdateLanguagesJSon(response);
                    $("#al_attributes_form").ResetFormElements();
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

    $("#languages_idLanguage").change(function()
    {
        LoadLanguageAttributes();
    });
}

function UpdateLanguagesJSon(response)
{
    $(response).each(function(key, item)
    {
        switch(item.key)
        {
            case "message":
                $('#al_dialog').html(item.value);
                break;
            case "languages":
                var idSelectedLanguage = $('#al_languages_list .al_element_selected').attr('ref');
                $('#al_languages_list').html(item.value);
                $('#al_languages_list .al_element_selector').each(function(key, item)
                {
                    if(idSelectedLanguage == $(item).attr('ref'))
                    {
                        $(item).addClass('al_element_selected');
                        return;
                    }
                });
                break;
            case "languages_menu":
                $('#al_languages_navigator_box').html(item.value);
                $('#al_languages_navigator').change(function()
                {
                    Navigate();
                });
                break;
        }
    });

    ObserveLanguages();
};

function ObserveLanguages()
{
    $('.al_element_selector').unbind().SelectLanguage();
}

function LoadLanguageAttributes(idLanguage)
{
    $("#al_attributes_form").ResetFormElements();
    $.ajax({
        type: 'POST',
        url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_loadLanguageAttributes',
        data: {'language' : retrieveIdLanguage()},
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

                    case '#languages_isMain':
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
}
