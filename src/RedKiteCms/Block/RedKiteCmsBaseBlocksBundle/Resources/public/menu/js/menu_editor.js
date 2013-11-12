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
            .inlinelist('start', { 'target': 'li', 'position': position, 'filterAdders': 'menu' })
        ;
        
    });
    
    $(document).on("stopEditingBlocks", function(event, element){ 
        if (element.attr('data-type') != 'Menu' && element.attr('data-type') != 'MenuVertical') {
            return;
        }
                
        $(element)
            .inlinelist('stop')
            .blocksEditor('start')
            .find('[data-editor="enabled"]')
            .blocksEditor('start')
        ;
    });
});
