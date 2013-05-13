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
            doStartEdit($(this));
            
            $(document).trigger("cmsStarted");
            
            return this;
        },
        stop: function()
        {
            if($('body').hasClass('cms_started'))
            {
                $("#al_cms_contents a").unbind();
                deactivateEditableInlineContents();
                showHiddenContentsFromEditMode();
                
                this.each(function() {
                    $(this)
                        .popover('destroy')
                        .removeClass('al_edit_on')
                        .unbind()
                    ;  
                });
                var activeInlineList = $('body').data('al-active-inline-list');
                if (activeInlineList != null) {
                    activeInlineList.inlinelist('stop');
                }

                cmsStartInternalJavascripts();
                $('body').removeClass('cms_started');
                
                stopBlocksMenu = false;
                
                $(document).trigger("cmsStopped");
            }
            
            return this;
        },
        startEditElement: function()
        {     
            doStartEdit($(this));
                
            return this;
        },
        stopEditElement: function()
        {     
            stopEditElement($(this));
                
            return this;
        },
        lockBlocksMenu: function()
        {     
            stopBlocksMenu = true;
                
            return this;
        },
        unlockBlocksMenu: function()
        {     
            stopBlocksMenu = false;
                
            return this;
        }
    };
    
    function doStartEdit(element)
    {
        startEditElement(element);
            
        // Starts the editor for included blocks
        startEditElement(element.find('[data-editor="enabled"]'));
    }
    
    function startEditElement(element)
    {
        if ( ! $('body').hasClass('cms_started')) {
            return;
        }
        
        element.each(function()
        {
            var $this = $(this);
            var decodedContent = decodeURIComponent($this.attr('data-encoded-content'));
            var popoverOptions = {
                placement: function () {
                    var position = $this.position();

                    if (position.top + 400 < $('#al_cms_contents').height()){
                        return "bottom";
                    }

                    return "top";
                },
                trigger: 'manual',
                content: decodedContent,
                template: '<div class="popover al-popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>'
            }
            
            var hasPopover = ($this.attr('rel') == 'popover');            
            if (hasPopover) {
                $this.popover(popoverOptions);
            }
            
            activateEditableInlineContents();
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
                    
                    $this.highligther('render');
                    $(this).css('cursor', 'pointer');
                    
                    
                    $('#al_block_menu_toolbar').show();
                    if ($(this).is('[data-hide-blocks-menu="true"]')) {
                        $('#al_block_menu_toolbar').hide();
                        
                        return;
                    }

                    $('#al_block_menu_toolbar').position({
                            my: "right top",
                            at: "right bottom",
                            of: $this
                        })                      
                        .data('parent', $this)
                    ;
                })
                .click(function(event)
                {   
                    event.stopPropagation();
                    
                    var $this = $(this);                     
                    if ($(document).find('.al-popover:visible').length > 0 && $this.attr('data-name') == 'block_' + $('body').data('idBlock')) {
                        if (stopBlocksMenu) {
                            stopEditElement($this);
                            
                            return false;
                        }
                    }

                    if(stopBlocksMenu) {
                        return false;
                    }                    
                    stopBlocksMenu = true;

                    startEdit($this);
                    if (hasPopover) {
                        showPopover($this);
                    }
                    
                    return false;
                })
            ;

            $(this).find("a").unbind().click(function(event) {
                event.preventDefault();
            });            
        });
    }
    
    function startEdit(element)
    {
        element.highligther('toggle');
        
        $('body')
            .data('idBlock', element.attr('data-block-id'))
            .data('slotName', element.attr('data-slot-name'))            
            .data('included', element.attr('data-included'))
            .data('activeBlock', element)
        ;
        $('#al_block_menu_toolbar').hide();

        $(document).trigger("blockEditing", [ element ]);
    }
    
    function stopEditElement(element)
    {
        stopBlocksMenu = false;  
        element.highligther('toggle');

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
        
        $(document).trigger("popoverShow", [ element ]);
    }
    
    function activateEditableInlineContents()
    {          
        $(document)
            .find('[data-content-editable="true"]')
            .attr('contenteditable', true)
            .addClass('al-editable-inline')
        ;
    }
    
    function deactivateEditableInlineContents()
    {
        $('.al-editable-inline').removeAttr('contenteditable');
    }
    
    function hideContentsForEditMode(element)
    {
        element.each(function() {
            var $this = $(this);
            if($this.attr('data-hide-when-edit') == "true") {
                var html = $this.html();
                $this.html('<p>A ' + $this.attr('data-type')  + ' block is not rendered when the editor is active</p>').data('html', html).addClass('is_hidden_in_edit_mode');
            }
        });
    }

    function showHiddenContentsFromEditMode()
    {
        $('.is_hidden_in_edit_mode').each(function() {
            var $this = $(this);
            $this.removeClass('is_hidden_in_edit_mode').html($this.data('html')); 
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
        $('#al_start_slots_management').click(function() {
            if ($('#al_stop_editor').is(':visible')) {    
                alert("This operation is not allowed when you are editing the contents");
                
                return false;
            }
            
            $('[data-editor="enabled"]').changeTheme('start');
            
            return false;
        });
        
        $('#al_stop_slots_management').click(function(){
            $('[data-editor="enabled"]').changeTheme('stop');
            $('.al_block_menu').hide();
            
            return false;
        });
        
        $('#al_cms_contents').click(function(){
            var block = $('body').data('activeBlock');
            if (block == null || block.attr('rel') == 'popover') {
                return;
            }
            
            block.blocksEditor('stopEditElement');
        });
        
        $('.al_language_item').click(function()
        {
            Navigate($(this).attr('rel'), $('#al_pages_navigator').html());
            
            return false;
        });

        $('.al_page_item').click(function()
        {
            Navigate($('#al_languages_navigator').html(), $(this).attr('rel'));
            
            return false;
        });
            
        $('#al_start_editor').click(function()
        {
            if ($('#al_stop_slots_management').is(':visible')) {
                alert("This operation is not allowed when you are editing the slots");
                
                return false;
            }
            
            $('[data-editor="enabled"]').blocksEditor('start');

            return false;
        });

        $('#al_stop_editor').click(function()
        {
            $('[data-editor="enabled"]').trigger("editorStopping").blocksEditor('stop');
            $('.al_block_menu').hide();
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
            if ($('#al_stop_slots_management').is(':visible')) {    
                return false;
            }
            
            $("#al_toggle_edit_buttons a").toggle();
                
            return false;
        });
        
        $("#al_toggle_slots_changer a").click(function ()
        {
            if ($('#al_stop_editor').is(':visible')) {    
                return false;
            }
            
            $("#al_toggle_slots_changer a").toggle();
                
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