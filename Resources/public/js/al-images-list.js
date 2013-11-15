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
        init: function() {
            $('.al_img').unbind().imagesList('editItem');
            $('.rk-image-remover').unbind().imagesList('deleteItem');
            $('.al_form_item').unbind().imagesList('saveAttributes');
            $('#al_item_attributes_form .form-control').unbind().imagesList('saveAttributes');
            
            $('#al_json_block_src').click(function(){
                $('<div />').dialogelfinder({
                    url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_elFinderMediaConnect',
                    lang : $('#al_available_languages option:selected').val(),
                    width : 840,
                    destroyOnClose : true,
                    commandsOptions : {
                        getfile: {
                            oncomplete: 'destroy'
                        }
                    },
                    getFileCallback : function(file, fm) {
                        $('#al_json_block_src').val(file.url);
                        $('.al_img_selected').html(file.url).find('img').attr('src', file.url);
                    }
                 });

                return false;
            });
            
        },
        addItem: function() {
            $(this).click(function(){
                var row = $('#rk-empty-image-row');
                row.clone()
                    .removeAttr('id')
                    .insertBefore(row)
                    .find('.al_empty_img')                  
                    .addClass('al_img')                    
                    .removeClass('al_empty_img')  
                ;
                
                $('body').imagesList('init');
                
                deselectActiveItem();
                resetForm();
                
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
                
                var c = 0;
                var values = {};
                $(element).each(function(){
                    var image = $(this);
                    
                    var imageValues = {};
                    $('#al_item_attributes_form').find(':input:not(input[type=submit])').each(function(){
                        var el = $(this);
                        var field = el.attr('id');
                        var value = image.attr('data-' + field);
                        if (null == value || value == "") {
                            value = " ";
                        }
                        imageValues[field] = encodeURIComponent(value);
                        //imageValues[field] = value;
                    });
                    
                    values[c] = imageValues;
                    c++;
                });
                
                $('body').EditBlock("Content", JSON.stringify(values), null, function(){ Holder.run(); });
            });
        },
        saveAttributes: function() {
            $(this).blur(function(){ 
                var image = $('.al_img_selected');
                $('#al_item_attributes_form').find(':input:not(input[type=submit])').each(function(){
                    var $this = $(this);
                    image.attr('data-' + $this.attr('id'), $this.val());
                });
            });
        }
    };
    
    function editItem(element) { 
        
        element.click(function(){                
            var $this = $(this);
            
            $('#al_item_attributes_form').find(':input:not(input[type=submit])').each(function(){
                var el = $(this);
                var value = $this.attr('data-' + el.attr('id'));
                if (null == value || value == "") {
                    value = " ";
                }
                el.val(decodeURIComponent(value));
            });
            
            selectActiveItem($this);  
            
            return false;
        });
    }
    
    function deleteItem(element) {
        element.click(function(){  
            if(confirm(translate("Are you sure you want to remove the selected image"))) {
                $(this).parent().parent().remove();
                
                deselectActiveItem();
                resetForm();
            }

            return false;
        });
    }
    
    function deselectActiveItem() {
        $('.al_img').removeClass('al_img_selected');
    }
    
    function selectActiveItem(element) {
        deselectActiveItem();
        element
            .addClass('al_img_selected')
        ;
    }
    
    function resetForm() {
        $('#al_item_attributes_form').find(':input:not(input[type=submit])').each(function(){
            $(this).val('');
        });
    }

    $.fn.imagesList = function( method, options ) {    
        settings = $.extend( {
          imageDimension  : '400x280',
          span: 'span2'
        }, options);
        
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.imagesList' );
        }   
    };
})(jQuery);
