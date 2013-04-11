$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        if (element.attr('data-type') != 'Link') {
            return;
        }
        
        $('#al_page_name')
            .on('change', function(){
                $('#al_json_block_href').val($('#al_page_name option:selected').val());
                
                return false;
            })
            .appendTo('.al_pages_selector')
            .show();
        ;
    });
    
    $(document).on("blockStopEditing", function(event, element){
        $('#al_page_name')
            .appendTo('body')
            .val(0)
            .hide()
        ;
    });
});
