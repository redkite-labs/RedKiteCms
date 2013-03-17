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
    var methods = {
        addItem: function() {
            $(this).click(function(){
                var imageMarkup = 
                    '<li class="span2">' + 
                    '<a href="#" class="thumbnail al_img">' +
                    '<img src="holder.js/400x280" title="" alt=""/>' +
                    '</a>' +
                    '</li>'
                ;
                $('.images_contents .thumbnails').append(imageMarkup);
                
                var attributes = {};
                $(".al_form_item").each(function(){
                    attributes[$(this).attr('id')] = "";
                });
                
                var element = $('.al_img:last');
                element.data('attributes', attributes); //console.log(element.data('attributes'));
                
                editItem(element);
                Holder.run();
                
                return false;
            });
        },
        editItem: function() {
            editItem($(this));
        },
        deleteItem : function() { 
            deleteItem($(this));
        },
        save: function(element, values) { 
            $(this).click(function(){    
                if (element == null) {
                    element = '.al_img';
                }
                
                if (values == null) {
                    values = attributesToJson(element);
                }
                
                $('body').EditBlock("Content", JSON.stringify(values), null, function(){ Holder.run(); });
            });
        },
        saveAttributes: function() {
            $(this).blur(function(){ 
                var image = $('.al_img_selected');               
                image.data('attributes', serializeFormToObject('#al_item_attributes_form'));
            });
        }
    };
    
    function editItem(element) {
        element.click(function(){                
            var $this = $(this);
            
            var attributes = $this.data('attributes');
            if (attributes == null) {
                attributes = $this.metadata();
                $this.data('attributes', attributes);
            }

            $.each(attributes, function(key, value){
                $('#' + key).val(value);
            });
            
            selectActiveItem($this);  
            
            return false;
        });
    }
    
    function deleteItem(element) {
        element.click(function(){  
            if(confirm("Are you sure you want to remove the selected image?")) { 
                $('.al_img_selected').parent().remove();
                $(".al_form_item").val('');
            }

            return false;
        });
    }
    
    function selectActiveItem(element) {
        $('.al_img').removeClass('al_img_selected');
        element
            .addClass('al_img_selected')
        ;
    }
    
    function attributesToJson(element) {
        var i = 0;
        var values = new Array();
        $(element).each(function(){
            var $this = $(this);
            var attributes = $this.data('attributes');
            if (attributes == null) {
                attributes = $this.metadata();
            }

            values[i] = attributes;
            i += 1;
        });

        return values;
    }
    
    function serializeFormToObject(form)
    {
       var o = {};
       $(form).find(':input').each(function() {
           var id = $(this).attr('id');
           if (o[id]) {
               if (!o[id].push) {
                   o[id] = [o[id]];
               }
               o[id].push(this.value || '');
           } else {
               o[id] = this.value || '';
           }
       });
       
       return o;
    }

    $.fn.imagesList = function( method ) {    
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
        }   
    };
})(jQuery);
