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
    $.fn.StartPageEditing = function()
    {
        $('.al_editable').unbind().ShowEditorContent();
        $('.al_hide_edit_mode').HideContentsForEditMode();

        this.each(function()
        {
            $(this).addClass('al_edit_on');
            $(this).mouseover(function(event)
            {
                if(!$(this).hasClass('al_highlight_content')) $(this).addClass('al_highlight_content');
                $(this).css('cursor', 'pointer');

                return false;
            }).mouseout(function(event)
            {
                $(this).removeClass('al_highlight_content');
                $(this).css('cursor', 'auto');
                
                return false;
            });
        });
        
        $("#al_cms_contents a").unbind().click(function(event) {
          event.preventDefault();
        });
        
        

        return this;
    };
    
    $.fn.StopPageEditing = function(closeEditor)
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
            $(this).unbind().removeClass('al_edit_on');
        });

        return this;
    };
    
    $.fn.HideContentsForEditMode = function()
    {
        this.each(function()
        {
            if(!$(this).hasClass('is_hidden_in_edit_mode'))
            {
                var data = $(this).metadata(); 
                $(this).data('content', $(this).html()).html('<p>A ' + data.type  + ' content is not renderable in edit mode</p>').addClass('is_hidden_in_edit_mode');
            }
        });
    };

    $.fn.ShowHiddenContentsFromEditMode = function()
    {
        this.each(function()
        {
            $(this).removeClass('is_hidden_in_edit_mode').html($(this).data('content'));
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


function Navigate()
{
    location.href = frontController +  $('#al_languages_navigator option:selected').attr('rel') + '/' + $('#al_pages_navigator option:selected').attr('rel');
}

$(document).ready(function(){
    InitDialog();

    $('#al_languages_navigator').change(function()
    {
        Navigate();
    });

    $('#al_pages_navigator').change(function()
    {
        Navigate();
    });

    $('#al_start_editor').click(function()
    {
        $('.al_editable').StartPageEditing();
        
        return false;
    });

    $('#al_stop_editor').click(function()
    {
        $('.al_editable').StopPageEditing();
        
        return false;
    });

    $('#al_open_pages_panel').click(function()
    {
        if($('#al_panel_contents').length == 0)
        {
            $.ajax({
                type: 'POST',
                url: frontController + $('#al_available_languages').val() + '/al_showPages',
                data: {},
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
                    $('#al_dialog').html(err.responseText);
                    $('#al_dialog').dialog('open');
                },
                complete: function()
                {
                    $('body').RemoveAjaxLoader();
                }
            });
        }
        
        return false;
    });

    $('#al_open_languages_panel').click(function()
    {
        if($('#al_panel_contents').length == 0)
        {
            $.ajax({
                type: 'POST',
                url: frontController + $('#al_available_languages').val() + '/al_showLanguages',
                data: {},
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
                    $('#al_dialog').html(err.responseText);
                    $('#al_dialog').dialog('open');
                },
                complete: function()
                {
                    $('body').RemoveAjaxLoader();
                }
            });
        }
        
        return false;
    });

    $('#al_open_themes_panel').click(function()
    {
        if($('#al_panel_contents').length == 0)
        {
            $.ajax({
                type: 'POST',
                url: frontController + $('#al_available_languages').val() + '/al_showThemes',
                data: {},
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
                    $('#al_dialog').html(err.responseText);
                    $('#al_dialog').dialog('open');
                },
                complete: function()
                {
                    $('body').RemoveAjaxLoader();
                }
            });
        }
        
        return false;
    });
    
    $('#al_open_media_library').click(function()
    {
        $.ajax({
            type: 'POST',
            url: frontController + $('#al_available_languages').val() + '/al_showFilesManager',
            data: {'page' :  $('#al_pages_navigator').val(),
                   'language' : $('#al_languages_navigator').val()},
            beforeSend: function()
            {
                $('body').AddAjaxLoader();
            },
            success: function(html)
            {
                $('#al_dialog').html(html);
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

    $('#al_deploy_site').click(function()
    {
        $.ajax({
            type: 'POST',
            url: frontController + $('#al_available_languages').val() + '/al_deploy',
            data: {'page' :  $('#al_pages_navigator').val(),
                   'language' : $('#al_languages_navigator').val()},
            beforeSend: function()
            {
                $('body').AddAjaxLoader();
            },
            success: function(html)
            {
                $('#al_dialog').html(html);
                $('#al_dialog').dialog('open');
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


