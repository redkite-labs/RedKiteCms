/*
 * This file is part of the RedKite CMS Application and it is distributed
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
        start: function() 
        {
            stopBlocksMenu = false;            
            $(this).mouseenter(function(event) {
                var $this = $(this);
                if ( ! isIncluded($this)) {
                    if (stopBlocksMenu) {
                        return;
                    }

                    $(this).highligther('highlight', {
                        cssClass: 'al-slot-highlighted'
                    });                    
                }
            })
            .click(function() {
                var $this = $(this);
                
                if ( ! isIncluded($this)) {                
                    stopBlocksMenu = true;
                    $('#al_old_slots').position({
                            my: "right top",
                            at: "right bottom",
                            of: $this
                        })
                        .show()        
                        .css('visibility', 'visible')          
                        .data('parent', $this)
                    ;

                    $('#al-undo-slot-assignment').data('html', $this.html());
                    $(this).highligther('activate',   {
                        cssClass: 'al-slot-highlighted',
                        toggleClass: 'al-slot-editing'
                    });
                }
                
                return false;
            });
            
            initCommands();
            
            
            
            return this;
        },
        stop: function() 
        {
            $(this).unbind();
            $('#al_old_slots').find('.al-slot').unbind();            
            $('#al-undo-slot-assignment').unbind();            
            $('#al-close-slots-panel').unbind();            
            $('#al-save-slot-assignment').unbind();
            close();
        }
    };
    
    function isIncluded(element) {
        return (element.is('[data-included="1"]')) ? true : false;
    }
       
    function close() {
        $('#al_old_slots').css('visibility', 'hidden');
        var parent = $('#al_old_slots').data('parent');
        if (parent != null) { 
            parent.highligther('deactivate');
        }
        stopBlocksMenu = false;
    }
    
    function initCommands() {
        $('#al_old_slots').find('.al-slot').unbind().click(function(){
            var $this = $(this);
            $('#al-save-slot-assignment').data('slot', $this.attr('data-slot-name'));
            $('#al_old_slots').data('parent').html($this.attr('data-content'));

            return false;
        });
        
        $('#al-undo-slot-assignment').unbind().click(function(){
            $('#al_old_slots').data('parent').html($(this).data('html'));

            return false;
        });
        
        $('#al-close-slots-panel').unbind().click(function(){
            close();

            return false;
        });
        
        $('#al-save-slot-assignment').click(function(){
            var $this = $(this);
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_changeSlot',
                data: {
                    'pageId' :  $('#al_pages_navigator').attr('rel'),
                    'languageId' : $('#al_languages_navigator').attr('rel'),
                    'sourceSlotName' : $this.data('slot'),
                    'targetSlotName' : $('#al_old_slots').data('parent').attr('data-slot-name')
                },
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(response)
                {
                    $(response).each(function(key, item)
                    {
                        switch(item.key)
                        {
                            case "message":
                                $('body').showAlert(item.value);
                                
                                break;
                            case "slots": 
                                $('#al_old_slots').html(item.value);
                                initCommands();
                                
                                break;
                        }
                    });
                    
                    close();
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
    
    $.fn.changeTheme = function( method, options ) 
    {
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.changeTheme' );
        }   
    };
})($);

(function($){
    var methods = {
        change: function()
        {
            this.each(function()
            {
                $(this).click(function()
                {
                    $.ajax({
                      type: 'POST',
                      url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_showThemeChanger',
                      data: {
                          'themeName' : $(this).attr('data-theme-name')
                      },
                      beforeSend: function()
                      {
                        $('body').AddAjaxLoader();
                      },
                      success: function(html)
                      {
                        $('body').showDialog(html, {width:300, buttons: null});
                      },
                      error: function(err)
                      {
                        $('body').showDialog(err);
                      },
                      complete: function()
                      {
                        $('body').RemoveAjaxLoader();
                      }
                    });

                    return false;
                });
            });
        },        
        scratch: function()
        {
            this.each(function()
            {
                $(this).click(function()
                {
                    if ( ! confirm(translate('WARNING: this command will destroy all the saved data and start a new site base on the choosen theme from the scratch: are you sure to continue?'))) {
                        return false;
                    }
                    
                    var themeName = $(this).attr('data-theme-name');
                    
                    $.ajax({
                      type: 'POST',
                      url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/startFromTheme',
                      data: {
                          'themeName' : themeName
                      },
                      beforeSend: function()
                      {
                        $('body').AddAjaxLoader();
                      },
                      success: function(html)
                      {
                        $('body').showAlert(html);
                        Navigate($('#al_languages_navigator').html(), $('#al_pages_navigator').html());
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
            });
        }
    };
    
    $.fn.manageTheme = function( method, options ) 
    {
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.manageTheme' );
        }   
    };
    
})($);


(function($){
    var methods = {
        load: function() {
            location.href = frontController + 'backend/' + $('#al-language').val() + '/al_previewTheme/' + $('#al-language').val() + '/' + $('#al-page').val() + '/' + $('#al-theme').val() + '/' + $(this).attr('rel');
        }
    };
    
    $.fn.template = function( method, options ) 
    {
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.template' );
        }   
    };
    
})($);


ObserveThemeCommands = function()
{
    try {
        $('.al_themes_changer').unbind().manageTheme('change');
        $('.al_start_from_theme').unbind().manageTheme('scratch');
    } catch (e) {}
};
