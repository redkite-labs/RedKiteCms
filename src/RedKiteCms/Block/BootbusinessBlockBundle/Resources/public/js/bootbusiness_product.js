$(document).ready(function() 
{
    $(document).on("startEditingBlocks", function(event, element){
        if (element.attr('data-type') != 'BootbusinessProductBlock') {
            return;
        }
        
        element.inlinelist('start', {
          addValue: '{"operation": "add", "value": { "type": "BootbusinessProductThumbnailBlock" }}'
        });
    });
    
    $(document).on("stopEditingBlocks", function(event, element){
        if (element.attr('data-type') != 'BootbusinessProductBlock') {
            return;
        }
                
        element.inlinelist('stop');
    });
});
