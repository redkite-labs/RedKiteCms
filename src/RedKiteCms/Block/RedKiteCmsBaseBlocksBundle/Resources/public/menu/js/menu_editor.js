$(document).ready(function() {
    $(document).on("startEditingBlocks", function(event, element){
        if (element.attr('data-type') != 'Menu' && element.attr('data-type') != 'MenuVertical') {
            return;
        }
        
        var position = 'left bottom'
        if (element.attr('data-type') == 'MenuVertical') {
            position = "right";
        }
        
        $(element)
            .find('.al-menu-list')
            .inlinelist('start', { 'position': position})
        ;
    });
    
    $(document).on("stopEditingBlocks", function(event, element){ 
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
