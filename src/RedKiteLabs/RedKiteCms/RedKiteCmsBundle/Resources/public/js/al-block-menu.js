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
        add: function() 
        {
            $(this).click(function()
            {
                stopBlocksMenu = true;
                var $this = $(this);
                var position = $this.offset();
                var top = position.top;
                var left = position.left;

                if (left >= ($(window).width() / 2)) {                
                    left = position.left - $('#al_blocks_list').width();
                }

                var elHeight = $('#al_blocks_list').height();
                if (top + elHeight >= $(document).height()) {  
                    top = position.top - elHeight;
                }

                $('#al_blocks_list')
                    .css('top', top + 'px')                
                    .css('left', left + 'px')
                    .show()
                ;
                
                return false;
            });
        },        
        remove: function() 
        {
            $(this).click(function()
            {
                $($('#al_block_menu_toolbar').data('parent')).DeleteBlock(); 
            
                return false;
            });
        },        
        initAdders: function() 
        {
            this.each(function(){
                $(this).click(function(){
                    $($('#al_block_menu_toolbar').data('parent')).AddBlock($(this).attr('rel'), {'included': $('#al_block_menu_toolbar').data('parent').hasClass('al_included')}, function(){Holder.run();}); 

                    stopBlocksMenu = false;
                $('#al_blocks_list').hide();
                    return false;
                });
            });
        },        
        close: function() 
        {
            $(this).click(function()
            {
                stopBlocksMenu = false;

                $('#al_blocks_list').hide();
            });
        }
    }
    
    $.fn.blocksMenu = function( method, options ) {        
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
        }   
    };
})( jQuery );

$(document).ready(function()
{
    $('#al_block_menu_add').blocksMenu('add');
    $('#al_close_block_menu').blocksMenu('close');    
    $('.al_block_adder').blocksMenu('initAdders');    
    $('#al_block_menu_delete').blocksMenu('remove');
});