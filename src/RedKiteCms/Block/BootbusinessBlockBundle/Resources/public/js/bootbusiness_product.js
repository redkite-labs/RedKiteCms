$(document).ready(function() 
{
    $(document).on("blockEditing", function(event, element){
        if (element.attr('data-type') != 'BootbusinessProductBlock') {
            return;
        }
        
        element.inlinelist('start', {
          addValue: '{"operation": "add", "value": { "type": "BootbusinessProductThumbnailBlock" }}'
        });
    });
    
    $(document).on("blockStopEditing", function(event, element){ 
        if (element.attr('data-type') != 'BootbusinessProductBlock') {
            return;
        }
                
        element.inlinelist('stop');
    });
});
