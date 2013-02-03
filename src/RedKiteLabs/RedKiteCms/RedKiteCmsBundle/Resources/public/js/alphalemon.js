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

(function( $ ){
    $.fn.StartToEdit = function()
    {
        $('body').addClass('cms_started');

        this.each(function()
        {
            $(this)
                .unbind()
                .ShowBlockEditor()
                .HideContentsForEditMode()
                .addClass('al_edit_on')
                .attr('data-toggle', 'context')
                .mouseover(function(event)
                {
                    if(!$(this).hasClass('al_highlight_content')) $(this).addClass('al_highlight_content');
                    $(this).css('cursor', 'pointer');

                    return false;
                })
                .mouseout(function(event)
                {
                    $(this).removeClass('al_highlight_content');
                    $(this).css('cursor', 'auto');

                    return false;
                })
                .on('context', function(e) {
                    $('#al_context_menu').data('parent', e.currentTarget);
                })
            ;

            $(this).find("a").unbind().click(function(event) {
                event.preventDefault();
            });
        });

        return this;
    };

    $.fn.StopToEdit = function(closeEditor)
    {
        if($('body').hasClass('cms_started'))
        {
            if(closeEditor == null)
            {
                closeEditor = true;
                $("#al_cms_contents a").unbind();
            }

            $('.al_hide_edit_mode').ShowHiddenContentsFromEditMode();
            if(closeEditor) $('#al_editor_dialog').dialog('close');

            this.each(function()
            {
                $(this).unbind().removeClass('al_edit_on').removeAttr('data-toggle');
            });

            cmsStartInternalJavascripts();
            $('body').removeClass('cms_started');
        }

        return this;
    };

    $.fn.HideContentsForEditMode = function()
    {
        this.each(function()
        {
            if($(this).hasClass('al_hide_edit_mode'))
            {
                var data = $(this).metadata();
                $(this).html('<p>A ' + data.type  + ' block is not renderable in edit mode</p>').addClass('is_hidden_in_edit_mode');
            }
        });

        return this;
    };

    $.fn.ShowHiddenContentsFromEditMode = function()
    {
        this.each(function()
        {
            if($(this).hasClass('is_hidden_in_edit_mode'))
            {
                $(this).removeClass('is_hidden_in_edit_mode').html(decodeURIComponent($(this).data('block')));
            }
        });
    };

    function ChangeSitePage()
    {
        this.each(function()
        {
            Navigate();
        });
    }
})( jQuery );


function Navigate(language, page)
{
    location.href = frontController + 'backend/' + language + '/' + page;
}

$(document).ready(function(){
    try
    {
        $('.al_language_item').each(function(){
            $(this).click(function()
            {
                Navigate($(this).attr('rel'), $('#al_pages_navigator').html());
                
                return false;
            });
        });

        $('.al_page_item').each(function(){
            $(this).click(function()
            {
                Navigate($('#al_languages_navigator').html(), $(this).attr('rel'));
                
                return false;
            });
        });
            
        $('#al_start_editor').click(function()
        {
            $('.al_editable').StartToEdit();

            return false;
        });

        $('#al_stop_editor').click(function()
        {
            $('.al_editable').StopToEdit();

            return false;
        });

        $('#al_open_users_manager').ListUsers();

        $('#al_logout').click(function()
        {
            location.href = frontController + 'backend/logout';
        });
        
        $('#al_open_pages_panel').click(function()
        {
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/al_showPages',
                data: {
                    'page' :  $('#al_pages_navigator').html(),
                    'language' : $('#al_languages_navigator').html()
                },
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(html)
                {
                    $('#al_panel').OpenPanel(html, function(){InitPagesCommands();ObservePages();});
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


            return false;
        });

        $('#al_open_languages_panel').click(function()
        {
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/al_showLanguages',
                data: {
                    'page' :  $('#al_pages_navigator').html(),
                    'language' : $('#al_languages_navigator').html()
                },
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(html)
                {
                    $('#al_panel').OpenPanel(html, function(){InitLanguagesCommands();ObserveLanguages();});
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

            return false;
        });

        $('#al_open_themes_panel').click(function()
        {
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/al_showThemes',
                data: {
                    'page' :  $('#al_pages_navigator').html(),
                    'language' : $('#al_languages_navigator').html()
                },
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(html)
                {
                    $('#al_panel').OpenPanel(html, function(){ObserveThemeCommands();});
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

            return false;
        });

        $('#al_open_media_library').click(function()
        {
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/al_showFilesManager',
                data: {'page' :  $('#al_pages_navigator').attr('rel'),
                    'language' : $('#al_languages_navigator').attr('rel')},
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(html)
                {
                    InitDialog('al_editor_dialog_tmp');
                    $('#al_editor_dialog_tmp').html(html);
                    $('#al_editor_dialog_tmp')
                        .dialog('open')
                        .delay(100)
                        .fadeOut(function(){ $(this).dialog("close") })
                    ;
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

            return false;
        });

        $('.al_deployer').click(function()
        {
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages').attr('rel') + '/al_' + $(this).attr('rel')  + '_deploy',
                data: {'page' :  $('#al_pages_navigator').attr('rel'),
                    'language' : $('#al_languages_navigator').attr('rel')},
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(html)
                {
                    $('body').showAlert(html);
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

            return false;
        });
    }
    catch(e)
    {
        alert(e);
    }
});
