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
    var stopBlocksMenu = false;
    
    var methods = {
        start: function() 
        {
            $('body').addClass('cms_started');
            startEditElement($(this));
            
            return this;
        },
        stop: function()
        {
            if($('body').hasClass('cms_started'))
            {
                $("#al_cms_contents a").unbind();
                showHiddenContentsFromEditMode('.al_hide_edit_mode');
                
                this.each(function() {
                    $(this)
                        .popover('destroy')
                        .removeClass('al_edit_on')
                        .unbind()
                    ;  
                });

                cmsStartInternalJavascripts();
                $('body').removeClass('cms_started');
                
                stopBlocksMenu = false;
            }
            
            return this;
        },
        startEditElement: function()
        {     
            startEditElement($(this));
                
            return this;
        },
        stopEditElement: function()
        {     
            stopEditElement($(this));
                
            return this;
        }
    };
    
    function startEditElement(element)
    {
        element.each(function()
        {
            var $this = $(this);

            var popoverOptions = {
                placement: function () {
                    var position = $this.position();

                    if (position.top + 400 < $('#al_cms_contents').height()){
                        return "bottom";
                    }

                    return "top";
                },
                trigger: 'manual',
                template: '<div class="popover al-popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>'
            }
            
            var hasPopover = ($this.attr('rel') == 'popover');            
            if (hasPopover) {
                $this.popover(popoverOptions);
            }

            hideContentsForEditMode($this);                
            $this
                .unbind()
                .addClass('al_edit_on')
                .mouseenter(function(event)
                {
                    event.stopPropagation(); 
                    if (stopBlocksMenu) {
                        return;
                    }

                    highlightElement($this);
                    placeBottomRight($this);

                    $(this).css('cursor', 'pointer');
                })
                .click(function(event)
                {   
                    event.stopPropagation(); 
                   
                    var $this = $(this);
                    if ($(document).find('.al-popover:visible').length > 0 && $this.attr('id') == 'block_' + $('body').data('idBlock')) {
                        if (stopBlocksMenu) {
                            stopEditElement($this);
                            
                            return;
                        }
                    }

                    if(stopBlocksMenu) {
                        return;
                    }
                    stopBlocksMenu = true;

                    startEdit($this);
                    if (hasPopover) {
                        showPopover($this);
                    }
                })
            ;

            $(this).find("a").unbind().click(function(event) {
                event.preventDefault();
            });            
        });
    }
    
    function highlightElement(element)
    {
        var position = element.offset();
        var blockWidth = element.outerWidth();
        var blockHeight = element.outerHeight();
        $('#al_block_menu_top')
            .width(blockWidth)
            .css('top', position.top - 1 + 'px')
            .css('left', position.left + 'px')    
            .addClass('highlight') 
            .show()
        ;                    

        $('#al_block_menu_bottom')
            .width(blockWidth)
            .css('top', position.top + blockHeight + 'px')
            .css('left', position.left + 'px')  
            .addClass('highlight')
            .show()
        ;
        $('#al_block_menu_left')
            .height(blockHeight)
            .css('top', position.top  + 'px')
            .css('left', position.left - 1 + 'px')  
            .addClass('highlight')  
            .show()
        ;
        $('#al_block_menu_right')
            .height(blockHeight)
            .css('top', position.top + 'px')
            .css('left', position.left - 1 + blockWidth + 'px')    
            .addClass('highlight')
            .show()
        ;   
    }
    
    function placeBottomRight(target)
    {
        var element = $('#al_block_menu_toolbar');
        var position = target.offset();
        var top = position.top + target.outerHeight();
        var left = (position.left + target.outerWidth()) - element.width();
                             
        element
            .css('position', 'absolute')
            .css('top', top)
            .css('left', left)  
            .data('parent',target)
            .show()
        ;
    }
    
    function startEdit(element)
    {
        $('#al_block_menu_top')
            .addClass('on-editing')
            .removeClass('highlight')
        ;                    

        $('#al_block_menu_bottom')
            .addClass('on-editing')
            .removeClass('highlight')
        ;

        $('#al_block_menu_left')
            .addClass('on-editing')
            .removeClass('highlight')
        ;

        $('#al_block_menu_right')
            .addClass('on-editing')
            .removeClass('highlight')
        ;

        var idBlock = element.attr('data-block-id');
        var slotName = element.attr('data-slot-name');   

        $('body')
            .data('idBlock', idBlock)
            .data('slotName', slotName)
            .data('activeBlock', element)
        ;
        $('#al_block_menu_toolbar').hide();

        $(document).trigger("blockEditing", [ element ]);
    }
    
    function stopEditElement(element)
    {
        stopBlocksMenu = false;  

        $('#al_block_menu_top')
            .addClass('highlight')
            .removeClass('on-editing')
        ;                    

        $('#al_block_menu_bottom')
            .addClass('highlight')
            .removeClass('on-editing')
        ;

        $('#al_block_menu_left')
            .addClass('highlight')
            .removeClass('on-editing')
        ;

        $('#al_block_menu_right')
            .addClass('highlight')
            .removeClass('on-editing')
        ;

        $('.al_block_menu').hide();

        element.each(function(){   
            var $this = $(this);
            $this.popover('destroy');
            startEditElement($this);
        });

        $(document).trigger("blockStopEditing", [ element ]);
    }
    
    function showPopover(element)
    {
        element.addClass('popover-zindex').popover('show');

        $('.al-popover:visible').each(function(){
            var pos = element.offset();
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
        
        $(document).trigger("popoverShow", [ element.attr('data-block-id'), $(this).attr('data-type') ]);
    }
    
    function hideContentsForEditMode(element)
    {
        element.each(function() {
            if($(this).hasClass('al_hide_edit_mode')) {
                var data = $(this).metadata();
                $(this).html('<p>A ' + data.type  + ' block is not renderable in edit mode</p>').addClass('is_hidden_in_edit_mode');
            }
        });
    }

    function showHiddenContentsFromEditMode(element)
    {
        return; //FIXME
        element.each(function() {
            if($(this).hasClass('is_hidden_in_edit_mode'))
            {
                $(this).removeClass('is_hidden_in_edit_mode').html(decodeURIComponent($(this).data('block')));
            }
        });
    }
    
    $.fn.blocksEditor = function( method, options ) {        
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
        }   
    };
})( jQuery );

function Navigate(language, page)
{
    location.href = frontController + 'backend/' + language + '/' + page;
}

$(document).ready(function(){
    try
    {   
        $('#al_cms_contents').click(function(){
            var block = $('body').data('activeBlock');
            if (block.attr('rel') == 'popover') {
                return;
            }
            
            block.blocksEditor('stopEditElement');
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
            //$('[data-editor="enabled"]').StartToEdit();
            $('[data-editor="enabled"]').blocksEditor('start');
            //$('.al_editable').StartToEdit();

            return false;
        });

        $('#al_stop_editor').click(function()
        {
            //$('.al_editable').trigger("editorStapping").StopToEdit();
            $('[data-editor="enabled"]').trigger("editorStapping").blocksEditor('stop');
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
