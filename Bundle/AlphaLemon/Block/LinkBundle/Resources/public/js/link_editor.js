$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        if (element.attr('data-type') != 'Link') {
            return;
        }
        
        $('#al_page_name')
            .unbind()
            .on('change', function(){
                $('#al_json_block_href').val($('#al_page_name option:selected').val());
                
                return false;
            })
            .appendTo('.al_pages_selector')
            .show()
        ;
        
        $('#al_json_block_value').keydown(function(event){
            var $this = $(this);
            if ($this.val().match(/route:/g)) {
                if (event.which == 32) {
                    alert(translate('A space character is not accepted, when adding an internal route'));
                    
                    return false;
                }
            }
            
        });
    });
    
    $(document).on("blockStopEditing", function(event, element){
        $('#al_page_name')
            .appendTo('body')
            .val(0)
            .hide()
        ;
        
        $('#al_json_block_value').unbind();
    });
});
