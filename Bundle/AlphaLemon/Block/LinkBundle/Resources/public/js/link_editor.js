$(document).ready(function() {
    $(document).on("popoverShow", function(event, idBlock, blockType){
        if (blockType != 'Link') {            
            return;
        }
        
        $('#al_page_name').on('change', function(){
            $('#al_json_block_href').val($('#al_page_name option:selected').val());
            
            return false;
        });
    });
});
