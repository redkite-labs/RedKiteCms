$(document).ready(function() {
    $(document).on("startEditingBlocks", function(event, element){
        if (element.attr('data-type') != 'BootstrapBreadcrumbBlock') {
            return;
        }
        
        $(element)
            .inlinelist('start', { 'target': 'li', 'position': 'left bottom', 'filterAdders': 'menu' })
        ;
        
    });
    
    $(document).on("stopEditingBlocks", function(event, element){ 
        if (element.attr('data-type') != 'BootstrapBreadcrumbBlock') {
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
