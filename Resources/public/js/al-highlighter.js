(function($){
    var settings;
    
    var methods = {
        render: function() 
        {
            var $this = $(this);
            var position = $this.offset();
            var blockWidth = $this.outerWidth();
            var blockHeight = $this.outerHeight();
            $('#al_block_menu_top')
                .width(blockWidth)
                .css('top', position.top - 1 + 'px')
                .css('left', position.left + 'px')   
                .removeClass()
                .addClass('al_block_menu ' + settings.cssClass) 
                .show()
            ;                    

            $('#al_block_menu_bottom')
                .width(blockWidth)
                .css('top', position.top + blockHeight + 'px')
                .css('left', position.left + 'px')   
                .removeClass()
                .addClass('al_block_menu ' + settings.cssClass) 
                .show()
            ;
            $('#al_block_menu_left')
                .height(blockHeight)
                .css('top', position.top  + 'px')
                .css('left', position.left - 1 + 'px')   
                .removeClass()
                .addClass('al_block_menu ' + settings.cssClass) 
                .show()
            ;
            $('#al_block_menu_right')
                .height(blockHeight)
                .css('top', position.top + 'px')
                .css('left', position.left - 1 + blockWidth + 'px')    
                .removeClass() 
                .addClass('al_block_menu ' + settings.cssClass) 
                .show()
            ;   
            
            return this;
        },
        toggle: function()
        {
            var cssClass;
            var toggleClass;
            
            if ($('#al_block_menu_top').hasClass(settings.cssClass)) {
                cssClass = settings.toggleClass;
                toggleClass = settings.cssClass;
            } else {
                cssClass = settings.cssClass;
                toggleClass = settings.toggleClass;
            }
            
            $('#al_block_menu_top')
                .addClass(cssClass)
                .removeClass(toggleClass)
            ;                    

            $('#al_block_menu_bottom')
                .addClass(cssClass)
                .removeClass(toggleClass)
            ;

            $('#al_block_menu_left')
                .addClass(cssClass)
                .removeClass(toggleClass)
            ;

            $('#al_block_menu_right')
                .addClass(cssClass)
                .removeClass(toggleClass)
            ;
            
            return this;
        }
    };
    
    $.fn.highligther = function( method, options ) 
    {
        settings = $.extend( {
          cssClass      : 'highlight',
          toggleClass   : 'on-editing'
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


