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
            
            settings.startListCallback();
            
            return this;
        },    
        stop: function() 
        {
            $('.al-add-item-list').unbind().remove();        
            $('.al-delete-item-list').unbind().remove();
            $('.inline-list-commands-container').remove();
            $('body').data('al-active-inline-list', null);
            
            settings.stopListCallback();
            
            return this;
        }
    };
    
    function initList(element)
    {
        var buttonAddMarkup = '<button class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-plus"></span></button>';
        var buttonDeleteMarkup = '<button class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>';
        if (bootstrapVersion == "2.x") {
            buttonAddMarkup = '<a class="btn btn-mini btn-primary"><i class="icon-plus"></i></a>';
            buttonDeleteMarkup = '<a class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i></a>';
        }
        
        var addButton = document.createElement("div");
        $(addButton)
            .addClass("al-add-item-list")
            .attr('data-item', '-1')
            .append(buttonAddMarkup)
            .position({
                my: "left top",
                at: "left top",
                of: element,
                collision: 'fit fit'
            })
            .appendTo('body')
            .show()
        ;
        
        element
            .find(settings.target)
            .each(function(){
                var $this = $(this); 
                if ($this.hasClass('al-empty')) { 
                    return;
                }

                if ( ! $this.is(':visible')) {
                    return;
                }

                var containerDiv = $(document.createElement("div"));
                var addButton = document.createElement("div");
                $(addButton)
                    .addClass("al-add-item-list")
                    .attr('data-item', $this.attr('data-item'))
                    .append(buttonAddMarkup)             
                    .appendTo(containerDiv)
                    .css('left', '0')
                    .show()
                ;

                var removeButton = document.createElement("div");
                $(removeButton)
                    .addClass("al-delete-item-list")
                    .attr('data-item', $this.attr('data-item'))
                    .attr('data-slot-name', $this.attr('data-slot-name'))
                    .append(buttonDeleteMarkup)             
                    .appendTo(containerDiv) 
                    .css('left', '26px')
                    .show()
                ;
                
                containerDiv.position({
                    my: "center top",
                    at: settings.position,
                    of: $this,
                    collision: 'fit fit'
                })
                .addClass('inline-list-commands-container')
                .css('position', 'absolute')
                .appendTo('body') ;
            })
        ;
        
        $('.al-add-item-list').show();
        if (settings.addValue == null) {
            // Adds a custom value            
            $('.al-add-item-list')
                .click(function(event){
                    event.stopPropagation();
                    $(document).data('data-item', $(this).attr('data-item'));
                })
                .blocksMenu('add', {'filter' : settings.filterAdders})
            ;
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
                    $('.inline-list').addClass('collapsed-list');
                });            
            });
        }
        
        $('.al-delete-item-list').click(function(event){
            event.stopPropagation(); 
            if (confirm(translate('Are you sure to remove the active item'))) {   
                var $this = $(this);
                var deleteItemCallback = settings.deleteItemCallback;
                $('body').EditBlock("Content", '{"operation": "remove", "item": "' + $this.attr('data-item') + '", "slotName": "' + $this.attr('data-slot-name') + '"}', null, function()
                {
                    Holder.run();                    
                    deleteItemCallback(); 
                    $('.inline-list').addClass('collapsed-list');
                });
            }
        });
    }
    
    $.fn.inlinelist = function( method, options ) {    
    
        settings = $.extend( {
          target                : '> li',
          addValue              : null,
          position              : 'left bottom',
          filterAdders          : 'none',
          startListCallback     : function(){},  
          stopListCallback      : function(){},  
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