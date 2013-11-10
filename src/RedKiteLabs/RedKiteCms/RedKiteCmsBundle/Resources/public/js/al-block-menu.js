/*
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

;(function( $ ){
    var settings;
    var currentMenu = ".al-blocks-menu";

    var methods = {
        add: function() 
        {
            $(this).data('filter', settings.filter);
            $(this).click(function()
            {
                if ($('.al-blocks-menu').is(":visible") || $('.al-available-blocks-menu').is(":visible")) {
                    return false;
                }
                
                filter($(this).data('filter'));
                $('body').blocksEditor('lockBlocksMenu');
                var $this = $(this);
                var position = $this.offset();
                var top = position.top;
                var left = position.left;

                if (left >= ($(window).width() / 2)) {                
                    left = position.left - $(currentMenu).width();
                }
                
                var elHeight = $(currentMenu).height();
                if (top + elHeight >= $(document).height()) {  
                    top = position.top - elHeight;
                }
                
                $(currentMenu)
                    .css('top', top + 'px')                
                    .css('left', left + 'px')
                ;
                
                $(currentMenu).show();
                
                return false;
            });
        },        
        remove: function() 
        {
            $(this).click(function()
            {
                var parent = $('#al_block_menu_toolbar').data('parent');
                $(parent).DeleteBlock(); 
            
                return false;
            });
        },        
        initAdders: function() 
        {
            this.each(function(){
                $(this).click(function(){
                    var parent = $('#al_block_menu_toolbar').data('parent');
                    $(parent).AddBlock($(this).attr('rel'), {'included': parent.hasClass('al_included')}, function(){ Holder.run(); }); 
                    $('.al_blocks_list').hide();
                    $('body').blocksEditor('unlockBlocksMenu');
                    
                    return false;
                });
            });
        },        
        close: function() 
        {
            $(this).click(function()
            {
                $('body').blocksEditor('unlockBlocksMenu');

                $(currentMenu).hide();
            });
        }
    }
    
    function filter(filter){
        $('.al-available-blocks-list').empty();
        $('.al-blocks-menu .al_block_adder').each(function(){
            var $this = $(this); 
            var filterAttribute = $this.attr('data-filter');
            
            //$this.parent().show();
            if (filter == 'none' && ! filterAttribute.match(new RegExp(filter))) {
                $this.unbind().click(function(){return false;}); 
                $this.addClass('rk-disabled');
                currentMenu = ".al-blocks-menu";
            }
            
            if (filter != 'none' && filterAttribute.match(new RegExp(filter))) {
                var addItemCallback = settings.addItemCallback;
                $('.al-available-blocks-list').append(
                    $this
                        .clone()     
                        .removeClass('rk-disabled')                
                        .unbind()
                        .click(function(){ 
                            var value = '{"operation": "add", "item": "' + $(document).data('data-item') + '", "value": { "blockType" : "' + $(this).attr('rel') + '" }}';
                            $('body').EditBlock("Content", value, null, function()
                            {
                                Holder.run();                    
                                addItemCallback();
                                $('.inline-list').addClass('collapsed-list');
                            }); 

                            $('.al_close_block_menu').click();

                            return false;
                        })
                );
                currentMenu = ".al-available-blocks-menu";
            }
        });
        $('.al-available-blocks-list a').wrap("<li></li>");
    }
    
    $.fn.blocksMenu = function( method, options ) {   
        settings = $.extend( {
          filter                : 'none',
          addItemCallback       : function(){}
        }, options);
        
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.blocksMenu' );
        }   
    };
})( jQuery );

$(document).ready(function()
{
    $('#al_block_menu_add').blocksMenu('add');
    $('.al_close_block_menu').blocksMenu('close');    
    $('.al_block_adder').blocksMenu('initAdders');    
    $('#al_block_menu_delete').blocksMenu('remove');
});