(function($){
    var methods = {
        addItem: function() {
            $(this).click(function(){    
                $('#al_save_item').data('element', null);
                var columns = $(".al_items_list table tr:last").find('.al-column'); 
                
                var newKey = $('.al_items_list table tr').length;
                $('.al_items_list table').append('<tr id="' + newKey + '"></tr>');

                var attributes = {};
                var newValue = "New value";
                var row = $(".al_items_list table tr:last");
                row = $(row);
                $(columns).each(function() {
                    var attributeValue = "";
                    var $this = $(this); 
                    var id = newKey + '_' + $this.name;
                    if ( ! $this.hasClass('al_hidden')) {
                        row.append('<td id="' + id + '" name="' + $this.name + '" class="al-column">' + newValue + '</td>');
                        attributeValue = newValue;
                    } else {
                        row.append('<td id="' + id + '" name="' + $this.name + '" class="al-column al_hidden">' + newValue + '</td>');
                    }
                    attributes[$this.attr('rel')] = attributeValue;
                });

                row.append('<td><a href="#" id="al_edit_item_' + newKey + '" rel="' + newKey + '" class="al_edit_item">Edit</a></td>');
                row.append('<td><a href="#" id="al_delete_item_' + newKey + '" class="al_delete_item" rel="' + newKey + '">Delete</a></td>');

                $('#al_edit_item_' + newKey).data('attributes', attributes);
                editItem($('#al_edit_item_' + newKey));
                deleteItem($('#al_delete_item_' + newKey));
                
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
                    element = '.al_edit_item';
                }
                
                if (values == null) {
                    values = attributesToJson(element);
                }
                $('body').EditBlock("Content", JSON.stringify(values));
            });
        },
        saveAttributes: function() {
            $(this).blur(function(){
                var columns = $('.al_active_row').find('.al-column');
                $(columns).each(function() { 
                    var $this = $(this);
                    if ( ! $this.hasClass('al_hidden')) {
                        $this.html($('#' + $this.attr('rel')).val());
                    }
                });
                
                var element = $('#al_save_item').data('element');                
                $(element).data('attributes', serializeFormToObject('#al_item_attributes_form'));
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
            
            $('#al_save_item').data('element', $this); 
            
            selectActiveItem($this);  
            
            return false;
        });
    }
    
    function deleteItem(element) {
        element.click(function(){                
            var $this = $(this); 
            selectActiveItem($this);
            
            if(confirm("Are you sure you want to remove the selected item?"))
            {
                var el = $this.attr('rel');
                $('#' + el).remove();                      
                $(".al_form_item").val('');
            }

            return false;
        });
    }
    
    function selectActiveItem(element) {
        $('.al_active_row').removeClass('al_active_row');
        $('#' + element.attr('rel')).addClass('al_active_row');
    }
    
    function attributesToJson(element) {
        var i = 0;
        var values = new Array();
        $(element).each(function(){                
            var attributes = $(this).data('attributes');
            if (attributes == null) {
                attributes = $(this).metadata();
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

    $.fn.list = function( method ) {    
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
        }   
    };
})(jQuery);