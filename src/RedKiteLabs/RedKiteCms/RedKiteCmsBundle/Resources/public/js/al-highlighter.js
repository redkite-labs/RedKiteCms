/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

;(function($){
    var settings;
    
    var methods = {
        highlight: function() 
        {
            render($(this));
            
            return this;
        },
        activate: function() 
        {
            hide();

            render($(this));
            
            return this;
        },
        deactivate: function() 
        {
            hide();
            
            return this;
        }
    };

    function hide()
    {
        $('.al_block_menu').hide();
    }
    
    function render(target)
    {
        var position = target.offset();
        var blockWidth = target.outerWidth();
        var blockHeight = target.outerHeight();

        $(settings.elements['top'])
            .width(blockWidth)
            .css('top', position.top - 1 + 'px')
            .css('left', position.left + 'px')
            .show()
        ;                    

        $(settings.elements['bottom'])
            .width(blockWidth)
            .css('top', position.top + blockHeight + 'px')
            .css('left', position.left + 'px')
            .show()
        ;
        $(settings.elements['left'])
            .height(blockHeight)
            .css('top', position.top  + 'px')
            .css('left', position.left - 1 + 'px')
            .show()
        ;
        $(settings.elements['right'])
            .height(blockHeight)
            .css('top', position.top + 'px')
            .css('left', position.left - 1 + blockWidth + 'px')
            .show()
        ;   
    };
    
    $.fn.highligther = function( method, options ) 
    {
        settings = $.extend( {
            'elements' : {
                "top" : '.al_block_menu_top',
                "bottom" : '.al_block_menu_bottom',
                "left" : '.al_block_menu_left',
                "right" : '.al_block_menu_right'
            }
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


