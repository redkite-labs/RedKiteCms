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


(function($){
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
        var last;
        element.find(settings.target).each(function(){
            var $this = $(this);
            if ($this.hasClass('al-empty')) {
                return;
            }
            
            var removeButton = document.createElement("div");
            $(removeButton)
                .addClass("al-delete-item-list")
                .attr('data-item', $this.attr('data-item'))
                .attr('data-slot-name', $this.attr('data-slot-name'))
                .append('<a class="btn btn-mini btn-danger"><i class="icon-trash icon-white" /></a>')
                .appendTo(element) 
            ;
            
            $(removeButton).position({
                    my: "right-5 top",
                    at: "right bottom",
                    of: $this
                })     
                .show()
            ;
            
            last = $this;
        });
        
        element.append('<div class="al-add-item-list"><a class="btn btn-mini btn-primary"><i class="icon-plus icon-white" /></a></div>');
        
        $('.al-add-item-list')
            .position({
                my: "left top",
                at: "right+10 top",
                of: last
            })    
        ;
        
        if (settings.addValue == null) {
            
            // Adds an included block
            $('.al-add-item-list').blocksMenu('add');
            $('.al_block_adder').unbind().each(function(){ 
                var addItemCallback = settings.addItemCallback;  
                $(this).click(function(){
                    var value = '{"operation": "add", "value": { "blockType" : "' + $(this).attr('rel') + '" }}';
                    $('body').EditBlock("Content", value, null, function(activeBlock)
                    {
                        activeBlock
                            .blocksEditor('stopEditElement')
                            .find('[data-editor="enabled"]')
                            .blocksEditor('startEditElement')
                        ; 
                       
                        Holder.run();
                    
                        addItemCallback();  
                    }); 
                                     
                    stopBlocksMenu = false;
                    $('#al_blocks_list').hide();
                    
                    return false;
                });
            });
        }
        else {
        
            // Adds a custom value
            $('.al-add-item-list').click(function() {    
                var addItemCallback = settings.addItemCallback;         
                $('body').EditBlock("Content", settings.addValue, null, function(activeBlock)
                {
                    activeBlock
                        .blocksEditor('stopEditElement')
                        .find('[data-editor="enabled"]')
                        .blocksEditor('startEditElement')
                    ;   
                    Holder.run();
                    
                    addItemCallback();                    
                });            
            });
        }
        
        $('.al-delete-item-list').click(function(){
            if (confirm('Are you sure to remove the active block')) {    
                var deleteItemCallback = settings.deleteItemCallback;  
                $('body').EditBlock("Content", '{"operation": "remove", "item": "' + $(this).attr('data-item') + '", "slotName": "' + $(this).attr('data-slot-name') + '"}', null, function(activeBlock)
                {
                    activeBlock
                        .blocksEditor('stopEditElement')
                        .find('[data-editor="enabled"]')
                        .blocksEditor('startEditElement')
                    ;
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
            $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
        }   
    };
})($);
