$(document).ready(function() {
    $(document).on("blockEditing", function(event, element){
        if (element.attr('data-type') != 'Menu' && element.attr('data-type') != 'MenuVertical') {
            return;
        }
        
        $(element)
            .find('.al-menu-list')
            .inlinelist('start')
        ;
    });
    
    $(document).on("blockStopEditing", function(event, element){ 
        if (element.attr('data-type') != 'Menu' && element.attr('data-type') != 'MenuVertical') {
            return;
        }
                
        $(element)
            .find('.al-menu-list')
            .inlinelist('stop')
        ;
        $('.al_block_adder').unbind().blocksMenu('initAdders')
    });
});
