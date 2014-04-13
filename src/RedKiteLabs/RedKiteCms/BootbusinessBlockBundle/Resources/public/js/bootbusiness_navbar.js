$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){ 
        if (element.attr('data-type') == 'BootbusinessNavbarBlock') {
            $('.al-edit-item').unbind().click(function(){
                element.popover('hide');
                
                var filter = (bootstrapVersion == "2.x") ? 'navbar-block-2' : 'navbar-block-3';
                element.inlinelist('start', {'target': '.al-navbar-list', 'filterAdders': filter});
            });
        } 
    });
    
    $(document).on("stopEditingBlocks", function(event, element){ 
        if (element.attr('data-type') == 'BootbusinessNavbarBlock') {
            $('.al-navbar-list').inlinelist('stop');
        }

        $('.al_block_adder').unbind().blocksMenu('initAdders');
    });
});