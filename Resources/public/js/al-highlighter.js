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

;(function($){
    var settings;
    
    var methods = {
        highlight: function() 
        {
            render($(this), {
                "top" : '#al_block_menu_top',
                "bottom" : '#al_block_menu_bottom',
                "left" : '#al_block_menu_left',
                "right" : '#al_block_menu_right'
            });
            
            return this;
        },
        activate: function() 
        {
            $('#al_block_menu_top').hide();
            $('#al_block_menu_bottom').hide();
            $('#al_block_menu_left').hide();
            $('#al_block_menu_right').hide();
            
            render($(this), {
                "top" : '#al_active_block_menu_top',
                "bottom" : '#al_active_block_menu_bottom',
                "left" : '#al_active_block_menu_left',
                "right" : '#al_active_block_menu_right'
            });
            
            return this;
        },
        deactivate: function() 
        {
            $('#al_active_block_menu_top').hide();
            $('#al_active_block_menu_bottom').hide();
            $('#al_active_block_menu_left').hide();
            $('#al_active_block_menu_right').hide();
            
            return this;
        }
    };
    
    function render(target, elements)
    {
        var position = target.offset();
        var blockWidth = target.outerWidth();
        var blockHeight = target.outerHeight();

        $(elements['top'])
            .width(blockWidth)
            .css('top', position.top - 1 + 'px')
            .css('left', position.left + 'px')   
            .removeClass()
            .addClass('al_block_menu ' + settings.cssClass) 
            .show()
        ;                    

        $(elements['bottom'])
            .width(blockWidth)
            .css('top', position.top + blockHeight + 'px')
            .css('left', position.left + 'px')   
            .removeClass()
            .addClass('al_block_menu ' + settings.cssClass) 
            .show()
        ;
        $(elements['left'])
            .height(blockHeight)
            .css('top', position.top  + 'px')
            .css('left', position.left - 1 + 'px')   
            .removeClass()
            .addClass('al_block_menu ' + settings.cssClass) 
            .show()
        ;
        $(elements['right'])
            .height(blockHeight)
            .css('top', position.top + 'px')
            .css('left', position.left - 1 + blockWidth + 'px')    
            .removeClass() 
            .addClass('al_block_menu ' + settings.cssClass) 
            .show()
        ;   
    };
    
    $.fn.highligther = function( method, options ) 
    {
        settings = $.extend( {
          cssClass      : 'highlight'
        }, options);
        
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.highligther' );
        }   
    };
})($);


