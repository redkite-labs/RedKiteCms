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
 
var stopBlocksMenu = false;

(function( $ ){
    $.fn.PlaceTopRight = function(target)
    {
        var $this = $(this);
        var position = target.offset();
        var left = (position.left + target.outerWidth()) - $this.width();
        $this
            .css('position', 'absolute')
            .css('top', position.top)
            .css('left', left)  
            .data('parent',target)
        ;
        
        return this;
    };
    
    $.fn.StartToEdit = function()
    {
        $('body').addClass('cms_started');

        this.each(function()
        {
            var $this = $(this);
            $this
                .unbind()
                .HideContentsForEditMode()
                .addClass('al_edit_on')
                .mouseenter(function(event)
                {
                    event.stopPropagation(); 
                    if (stopBlocksMenu) {
                        return;
                    }
                    
                    var position = $this.offset();
                    var blockWidth = $this.outerWidth();
                    var blockHeight = $this.outerHeight();
                    $('#al_block_menu_top')
                        .width(blockWidth)
                        .css('top', position.top + 'px')
                        .css('left', position.left + 'px')   
                        .show()
                    ;                    
                    
                    $('#al_block_menu_bottom')
                        .width(blockWidth)
                        .css('top', position.top + blockHeight + 'px')
                        .css('left', position.left + 'px')  
                        .show()
                    ;
                    $('#al_block_menu_left')
                        .height(blockHeight)
                        .css('top', position.top + 'px')
                        .css('left', position.left + 'px')  
                        .show()
                    ;
                    $('#al_block_menu_right')
                        .height(blockHeight)
                        .css('top', position.top + 'px')
                        .css('left', position.left + blockWidth + 'px')  
                        .show()
                    ;                   
                    
                    $('#al_block_menu_toolbar')
                        .PlaceTopRight($this)
                        .show()
                    ;
                    
                    $(this).css('cursor', 'pointer');
                })
                .click(function(event){
                    event.stopPropagation(); 
                    
                    if(stopBlocksMenu) {
                        return;
                    }
                    stopBlocksMenu = true;  
                    
                    $this.find('.al_inline_editable').StartInlineEditor($this);
                    $this.find('.al-data-list').StartListEditing();
                    
                    var editableData = $this.metadata();
                    var idBlock = editableData.id;
                    var slotName = editableData.slotName;
                    $('body').data('idBlock', idBlock).data('slotName', slotName).data('activeBlock', $this);                    
                    $('#al_block_menu_toolbar').hide();
                    
                    $(document).trigger("blockEditing", [ this ]);
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
            var activeBlock = $('body').data('activeBlock');
            if (activeBlock  != null) {
                activeBlock.StopEditBlock();
            }
            
            if(closeEditor == null) {
                closeEditor = true;
                $("#al_cms_contents a").unbind();
            }

            $('.al_hide_edit_mode').ShowHiddenContentsFromEditMode();
            if (closeEditor) {
                $('#al_editor_dialog').dialog('close');
            }

            this.each(function() {
                $(this).unbind().removeClass('al_edit_on');  
            });

            cmsStartInternalJavascripts();
            $('body').removeClass('cms_started');
        }

        return this;
    };
    
    $.fn.StopEditBlock = function()
    {
        if (stopBlocksMenu) {
            stopBlocksMenu = false;  
            $(this)
                .find('.al_inline_editable')
                .each(function(){                
                    $(this).popover('destroy');
                })
                .find('.al-data-list')
                .StopListEditing()
            ;
            
            $(document).trigger("blockStopEditing", [ this ]);                              
            $('.al_block_menu').hide();
        }
        
        return this;
    };
            
    $.fn.StartInlineEditor = function(parent)
    {
        this.each(function(){
            var $this = $(this);
            var options = {
                placement: function () {
                    var position = $this.position();
                    
                    if (position.top + 400 < $('#al_cms_contents').height()){
                        return "bottom";
                    }

                    return "top";
                },
                template: '<div class="popover al-popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>'
            }
            $this.popover(options).click(function()
            {
                $('.al-popover:visible').each(function(){
                    var pos = $this.offset();
                    var popover = $(this);
                    
                    var actualWidth = popover.width();
                    var actualLeft = pos.left;
                    
                    if (actualLeft + actualWidth < $('#al_cms_contents').width()) {
                        popover.offset({left: actualLeft}).find('.arrow').css('left', '10%');
                    } else {
                        popover.offset({left: actualLeft + (actualWidth / 5) - actualWidth}).find('.arrow').css('left', '90%');
                    }
                });
                
                $('.al_editor_save').each(function(){ 
                    var $this = $(this);
                    $this.unbind().click(function(){
                        $('body').EditBlock('Content', $('#al_item_form').serialize());

                        return false;
                    });
                });
                var data = parent.metadata();
                $(document).trigger("popoverShow", [ data.id, data.type ]);
            });
        });

        return this;
    };
        
    $.fn.HideContentsForEditMode = function()
    {
        this.each(function() {
            if($(this).hasClass('al_hide_edit_mode')) {
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
        $('#al_cms_contents').click(function(){
            var activeBlock = $('body').data('activeBlock');
            if (activeBlock  != null) {
                activeBlock.StopEditBlock();
            }
        });

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
            $('.al_editable').trigger("editorStapping").StopToEdit();
            $('.al_block_menu').each(function(){ $(this).hide() });
            $('#al_block_menu_toolbar').hide();
            $('#al_blocks_list').hide();

            return false;
        });
        
        $("#al_tab a").click(function ()
        {
            $("#al_tab a").toggle();
                
            return false;
	    });
        
        $("#al_toggle_edit_buttons a").click(function ()
        {
            $("#al_toggle_edit_buttons a").toggle();
                
            return false;
	    });
        
        $("#al_tab .al_tab").click(function ()
        {
            $(".al_tab").toggle();
                
            return false;
	    });
        
        $(".al_tab_open").click(function ()
        {
            $("#al_control_panel_body").toggle();
            $('#al_tab').css('top', $("#al_control_panel_body").height() + 'px');
                
            return false;
	    });
        
        $(".al_tab_close").click(function ()
        {
            $('#al_tab').css('top', '0');
            $("#al_control_panel_body").toggle();
                
            return false;
	    });
        
        $('#al_show_navigation').click(function ()
        {
            $("#al_toggle_nav_button").toggle();
                
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
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_showPages',
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
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_showLanguages',
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
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_showThemes',
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
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_showFilesManager',
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
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_' + $(this).attr('rel')  + '_deploy',
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
