$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        if (element.attr('data-type') != 'File') {
            return;
        }
        
        openFilesMediaLibrary();
    });
}); 

function openFilesMediaLibrary(element)
{
    if (element == null) {
        element = '#al_json_block_file';
    }
    
    $(element).click(function()
    {              
        $('<div/>').dialogelfinder({
            url : frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_elFinderFileConnect',
            lang : 'en',
            width : 840,
            destroyOnClose : true,
            commandsOptions : {
                getfile: {
                    oncomplete: 'destroy'
                }
            },
            getFileCallback : function(file, fm) {
                $('#al_json_block_file').val(file.path);
            }
        }).dialogelfinder('instance');
    });
}
