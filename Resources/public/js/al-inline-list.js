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


;(function($){
    var settings;
    
    var methods = {
        start: function() 
        {
            var $this = $(this);
            initList($this);
            
            $('body').data('al-active-inline-list', $this);
            
            return this;
        },    
        stop: function() 
        {
            $('.al-add-item-list').unbind().remove();        
            $('.al-delete-item-list').unbind().remove();
            $('body').data('al-active-inline-list', null);
            
            return this;
        }
    };
    
    function initList(element)
    {
        var addButton = document.createElement("div");
        $(addButton)
            .addClass("al-add-item-list")
            .css("position", "static")
            .css("float", "left")
            .attr('data-item', '-1')
            .append('<a class="btn btn-mini btn-primary"><i class="icon-plus icon-white" /></a>')            
            .show()
        ;
        
        element
        .before(addButton)
        .find(settings.target).each(function(){
            var $this = $(this); 
            if ($this.hasClass('al-empty')) { 
                return;
            }
            
            if ( ! $this.is(':visible')) {
                return;
            }
            
            var addButton = document.createElement("div");
            $(addButton)
                .addClass("al-add-item-list")
                .attr('data-item', $this.attr('data-item'))
                .append('<a class="btn btn-mini btn-primary"><i class="icon-plus icon-white" /></a>')                
                .appendTo($this)
                .position({
                    my: "left+10 top",
                    at: "left bottom",
                    of: $this
                }) 
                .show()
            ;
            
            var removeButton = document.createElement("div");
            $(removeButton)
                .addClass("al-delete-item-list")
                .attr('data-item', $this.attr('data-item'))
                .attr('data-slot-name', $this.attr('data-slot-name'))
                .append('<a class="btn btn-mini btn-danger"><i class="icon-trash icon-white" /></a>')
                .appendTo($this) 
                .position({
                    my: "left+38 top",
                    at: "left bottom",
                    of: $this
                })       
                .show()
            ;
        });
        
        $('.al-add-item-list').show();
        if (settings.addValue == null) {
            
            // Adds a custom value            
            $('.al-add-item-list')
                .click(function(event){
                    event.stopPropagation(); 
                    $(document).data('data-item', $(this).attr('data-item'));
                })
                .blocksMenu('add')
            ;
            $('.al_block_adder').unbind().each(function(){ 
                var addItemCallback = settings.addItemCallback;  
                $(this).click(function(){ 
                    var value = '{"operation": "add", "item": "' + $(document).data('data-item') + '", "value": { "blockType" : "' + $(this).attr('rel') + '" }}';
                    $('body').EditBlock("Content", value, null, function()
                    {
                        Holder.run();                    
                        addItemCallback();  
                    }); 
                                    
                    $('#al_close_block_menu').click();
                    
                    return false;
                });
            });
        }
        else {
        
            // Adds an included block
            $('.al-add-item-list').click(function(event) {  
                event.stopPropagation(); 
                var value = settings.addValue;
                if ($.parseJSON(value) != null) {
                    value = value.substring(0, value.length-1) + ', "item": "' + $(this).attr('data-item') + '"}';
                }
                
                var addItemCallback = settings.addItemCallback;   
                
                $('body').EditBlock("Content", value, null, function()
                {
                    Holder.run();                    
                    addItemCallback();                    
                });            
            });
        }
        
        $('.al-delete-item-list').click(function(event){
            event.stopPropagation(); 
            if (confirm(translate('Are you sure to remove the active item'))) {   
                var deleteItemCallback = settings.deleteItemCallback;  
                $('body').EditBlock("Content", '{"operation": "remove", "item": "' + $(this).attr('data-item') + '", "slotName": "' + $(this).attr('data-slot-name') + '"}', null, function()
                {
                    Holder.run();                    
                    deleteItemCallback(); 
                });
            }
        });
    }
    
    $.fn.inlinelist = function( method, options ) {    
    
        settings = $.extend( {
          target            : '> li',
          addValue          : null,
          addItemCallback       : function(){},          
          deleteItemCallback    : function(){}
        }, options);
    
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.inlinelist' );
        }   
    };
})($);
